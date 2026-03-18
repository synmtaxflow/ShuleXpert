<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\OtherStaff;
use App\Models\User;
use App\Models\Watchman;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Auth extends Controller
{
    private function isAjax(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson() || $request->expectsJson();
    }

    private function jsonFail(string $message, int $code = 400)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }

    private function sendOtp(string $phone, string $otp, string $context = 'Login')
    {
        $sms = new SmsService();
        $msg = "{$context} OTP: {$otp}. ShuleXpert";
        return $sms->sendSms($phone, $msg);
    }

    private function startOtpSession(array $payload): void
    {
        // payload: pending_user_id, pending_user_type, pending_username, schoolID?, teacherID?, staffID?, phone
        $otp = (string) random_int(100000, 999999);
        $token = (string) Str::uuid();

        Session::put('otp_pending', true);
        Session::put('otp_token', $token);
        Session::put('otp_code_hash', Hash::make($otp));
        Session::put('otp_expires_at', time() + (10 * 60));
        Session::put('otp_payload', $payload);

        $this->sendOtp($payload['phone'], $otp);
    }

    private function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) <= 5) return $digits;
        $start = substr($digits, 0, 6);
        $end = substr($digits, -2);
        return $start . '****' . $end;
    }

    private function requiresOtpForSchool(?int $schoolID): bool
    {
        if (!$schoolID) return false;
        $school = School::where('schoolID', $schoolID)->first();
        return $school ? (bool) $school->two_factor_enabled : false;
    }

    private function isLiveEnvironment(?int $schoolID): bool
    {
        if (!$schoolID) return false;
        $env = School::where('schoolID', $schoolID)->value('environment');
        return strtolower((string) $env) === 'live';
    }

    private function shouldForcePasswordChange(string $userType, ?int $schoolID, ?string $username): bool
    {
        if (!in_array($userType, ['Admin', 'Teacher', 'Staff'], true)) return false;
        if (!$this->isLiveEnvironment($schoolID)) return false;
        if (!$username) return false;

        $user = User::where('name', $username)->where('user_type', $userType)->first();
        if (!$user) return false;

        if ($userType === 'Admin') {
            return Hash::check('3345', $user->password) || Hash::check('Admin@3345', $user->password);
        }

        if ($userType === 'Teacher') {
            $teacher = Teacher::where('employee_number', $username)->first();
            if (!$teacher) return false;
            $default = (string) ($teacher->last_name ?? '');
            return $default !== '' && Hash::check($default, $user->password);
        }

        if ($userType === 'Staff') {
            $staff = OtherStaff::where('employee_number', $username)->first();
            if (!$staff) return false;
            $default = (string) ($staff->last_name ?? '');
            return $default !== '' && Hash::check($default, $user->password);
        }

        return false;
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Redirect if already logged in
        if (Session::has('user_type')) {
            $userType = Session::get('user_type');
            if ($userType === 'SuperAdmin') {
                return redirect()->route('superAdminDashboard');
            }
            if ($userType === 'Admin') {
                return redirect()->route('AdminDashboard');
            } elseif ($userType === 'Teacher') {
                return redirect()->route('teachersDashboard');
            } elseif ($userType === 'Staff') {
                return redirect()->route('staffDashboard');
            } elseif ($userType === 'Watchman') {
                return redirect()->route('watchman.visitors');
            }
        }

        return view('login');
    }

    /**
     * Authenticate user
     */
    public function auth(Request $request)
    {
        // Ensure default SuperAdmin exists
        $defaultSuperAdminUsername = 'Admin.ShuleXpert.com';
        $defaultSuperAdminPassword = 'Admin@3345';
        User::firstOrCreate(
            ['name' => $defaultSuperAdminUsername],
            [
                'email' => 'superadmin@shulexpert.local',
                'password' => Hash::make($defaultSuperAdminPassword),
                'user_type' => 'SuperAdmin',
            ]
        );

        // Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:1',
        ], [
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
        ]);

        if ($validator->fails()) {
            if ($this->isAjax($request)) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('username'));
        }

        $username = $request->username;
        $password = $request->password;

        // Rate limiting - prevent brute force attacks
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            if ($this->isAjax($request)) {
                return $this->jsonFail("Too many login attempts. Please try again in {$seconds} seconds.", 429);
            }
            return redirect()->back()
                ->with('error', "Too many login attempts. Please try again in {$seconds} seconds.")
                ->withInput($request->only('username'));
        }

        // User Authentication
        $userLogin = User::where('name', $username)->first();

        // Check if user exists and password is correct
        if (!$userLogin || !Hash::check($password, $userLogin->password)) {
            RateLimiter::hit($key, 60); // 60 seconds lockout
            if ($this->isAjax($request)) {
                return $this->jsonFail('Incorrect username or password!', 401);
            }
            return redirect()->back()
                ->with('error', 'Incorrect username or password!')
                ->withInput($request->only('username'));
        }

        // Clear rate limiter on successful login
        RateLimiter::clear($key);

        // Handle different user types
        // If school has 2FA enabled, Admin/Teacher/Staff must confirm OTP before session is created.
        if (in_array($userLogin->user_type, ['Admin', 'Teacher', 'Staff'], true)) {
            $pending = [
                'pending_user_id' => $userLogin->id,
                'pending_user_type' => $userLogin->user_type,
                'pending_username' => $userLogin->name,
                'pending_user_email' => $userLogin->email,
            ];

            if ($userLogin->user_type === 'Admin') {
                $school = School::where('registration_number', $username)->first();
                if (!$school) {
                    if ($this->isAjax($request)) return $this->jsonFail('School not found for this admin account.', 404);
                    return redirect()->back()->with('error', 'School not found for this admin account.')->withInput($request->only('username'));
                }
                if ($this->requiresOtpForSchool((int) $school->schoolID)) {
                    $pending['schoolID'] = (int) $school->schoolID;
                    if (empty($school->phone)) {
                        if ($this->isAjax($request)) return $this->jsonFail('School phone number not set. Please contact admin.', 422);
                        return redirect()->back()->with('error', 'School phone number not set. Please contact admin.')->withInput($request->only('username'));
                    }
                    $pending['phone'] = $school->phone;
                    $this->startOtpSession($pending);
                    if ($this->isAjax($request)) {
                        return response()->json([
                            'success' => true,
                            'requires_otp' => true,
                            'otp_token' => Session::get('otp_token'),
                            'masked_phone' => $this->maskPhone($pending['phone']),
                            'expires_in' => 600,
                        ]);
                    }
                    return redirect()->back()->with('success', 'OTP sent. Please login again using the OTP flow.')->withInput($request->only('username'));
                }
            }

            if ($userLogin->user_type === 'Teacher') {
                $teacher = Teacher::where('employee_number', $username)->first();
                if (!$teacher) {
                    if ($this->isAjax($request)) return $this->jsonFail('Teacher record not found.', 404);
                    return redirect()->back()->with('error', 'Teacher record not found.')->withInput($request->only('username'));
                }
                if ($this->requiresOtpForSchool((int) $teacher->schoolID)) {
                    $pending['schoolID'] = (int) $teacher->schoolID;
                    $pending['teacherID'] = (int) $teacher->id;
                    $pending['teacher_name'] = $teacher->first_name . ' ' . $teacher->last_name;
                    if (empty($teacher->phone_number)) {
                        if ($this->isAjax($request)) return $this->jsonFail('Teacher phone number not set. Please contact admin.', 422);
                        return redirect()->back()->with('error', 'Teacher phone number not set. Please contact admin.')->withInput($request->only('username'));
                    }
                    $pending['phone'] = $teacher->phone_number;
                    $this->startOtpSession($pending);
                    if ($this->isAjax($request)) {
                        return response()->json([
                            'success' => true,
                            'requires_otp' => true,
                            'otp_token' => Session::get('otp_token'),
                            'masked_phone' => $this->maskPhone($pending['phone']),
                            'expires_in' => 600,
                        ]);
                    }
                    return redirect()->back()->with('success', 'OTP sent. Please login again using the OTP flow.')->withInput($request->only('username'));
                }
            }

            if ($userLogin->user_type === 'Staff') {
                $staff = OtherStaff::where('employee_number', $username)->first();
                if (!$staff) {
                    if ($this->isAjax($request)) return $this->jsonFail('Staff record not found.', 404);
                    return redirect()->back()->with('error', 'Staff record not found.')->withInput($request->only('username'));
                }
                if ($this->requiresOtpForSchool((int) $staff->schoolID)) {
                    $pending['schoolID'] = (int) $staff->schoolID;
                    $pending['staffID'] = (int) $staff->id;
                    $pending['staff_name'] = $staff->first_name . ' ' . $staff->last_name;
                    $pending['profession_id'] = $staff->profession_id;
                    if (empty($staff->phone_number)) {
                        if ($this->isAjax($request)) return $this->jsonFail('Staff phone number not set. Please contact admin.', 422);
                        return redirect()->back()->with('error', 'Staff phone number not set. Please contact admin.')->withInput($request->only('username'));
                    }
                    $pending['phone'] = $staff->phone_number;
                    $this->startOtpSession($pending);
                    if ($this->isAjax($request)) {
                        return response()->json([
                            'success' => true,
                            'requires_otp' => true,
                            'otp_token' => Session::get('otp_token'),
                            'masked_phone' => $this->maskPhone($pending['phone']),
                            'expires_in' => 600,
                        ]);
                    }
                    return redirect()->back()->with('success', 'OTP sent. Please login again using the OTP flow.')->withInput($request->only('username'));
                }
            }
        }

        switch ($userLogin->user_type) {
            case 'SuperAdmin':
                Session::put('user_type', $userLogin->user_type);
                Session::put('userID', $userLogin->id);
                Session::put('user_name', $userLogin->name);
                Session::put('user_email', $userLogin->email);

                $request->session()->regenerate();

                if ($this->isAjax($request)) {
                    return response()->json(['success' => true, 'redirect' => route('superAdminDashboard')]);
                }

                return redirect()->route('superAdminDashboard')
                    ->with('success', 'You have logged in successfully as Super Admin!');
                break;

            case 'Admin':
                $school = School::where('registration_number', $username)->first();

                if (!$school) {
                    return redirect()->back()
                        ->with('error', 'School not found for this admin account.')
                        ->withInput($request->only('username'));
                }

                // Set session data
                Session::put('schoolID', $school->schoolID);
                Session::put('user_type', $userLogin->user_type);
                Session::put('userID', $userLogin->id);
                Session::put('user_name', $userLogin->name);
                Session::put('user_email', $userLogin->email);

                // Regenerate session ID for security
                $request->session()->regenerate();

                if ($this->shouldForcePasswordChange('Admin', (int) $school->schoolID, $username)) {
                    Session::put('force_password_change', true);
                }

                if (Session::get('force_password_change')) {
                    if ($this->isAjax($request)) {
                        return response()->json(['success' => true, 'redirect' => route('admin.change_password')]);
                    }
                    return redirect()->route('admin.change_password')->with('error', 'Please change your password to continue.');
                }

                if ($this->isAjax($request)) {
                    return response()->json(['success' => true, 'redirect' => route('AdminDashboard')]);
                }

                return redirect()->route('AdminDashboard')
                    ->with('success', 'You have logged in successfully as Admin!');
                break;

            case 'Teacher':
                $teacher = Teacher::where('employee_number', $username)->first();

                if (!$teacher) {
                    return redirect()->back()
                        ->with('error', 'Teacher record not found.')
                        ->withInput($request->only('username'));
                }

                // Set session data
                Session::put('schoolID', $teacher->schoolID);
                Session::put('teacherID', $teacher->id);
                Session::put('user_type', $userLogin->user_type);
                Session::put('userID', $userLogin->id);
                Session::put('user_name', $userLogin->name);
                Session::put('user_email', $userLogin->email);
                Session::put('teacher_name', $teacher->first_name . ' ' . $teacher->last_name);

                // Load teacher roles if Spatie is installed
                if (class_exists(\Spatie\Permission\Models\Permission::class) && method_exists($teacher, 'roles')) {
                    $roles = $teacher->roles()->pluck('name')->toArray();
                    Session::put('teacher_roles', $roles);
                }

                // Regenerate session ID for security
                $request->session()->regenerate();

                if ($this->shouldForcePasswordChange('Teacher', (int) $teacher->schoolID, $username)) {
                    Session::put('force_password_change', true);
                }

                if (Session::get('force_password_change')) {
                    if ($this->isAjax($request)) {
                        return response()->json(['success' => true, 'redirect' => route('teacher.profile') . '#change-password']);
                    }
                    return redirect()->route('teacher.profile')->with('error', 'Please change your password to continue.');
                }

                if ($this->isAjax($request)) {
                    return response()->json(['success' => true, 'redirect' => route('teachersDashboard')]);
                }

                return redirect()->route('teachersDashboard')
                    ->with('success', 'You have logged in successfully as Teacher!');
                break;

            case 'Staff':
                $staff = OtherStaff::where('employee_number', $username)->first();

                if (!$staff) {
                    return redirect()->back()
                        ->with('error', 'Staff record not found.')
                        ->withInput($request->only('username'));
                }

                // Set session data
                Session::put('schoolID', $staff->schoolID);
                Session::put('staffID', $staff->id);
                Session::put('user_type', $userLogin->user_type);
                Session::put('userID', $userLogin->id);
                Session::put('user_name', $userLogin->name);
                Session::put('user_email', $userLogin->email);
                Session::put('staff_name', $staff->first_name . ' ' . $staff->last_name);
                Session::put('profession_id', $staff->profession_id);

                // Load staff permissions based on profession
                if ($staff->profession_id) {
                    $permissions = \App\Models\StaffPermission::where('profession_id', $staff->profession_id)
                        ->pluck('name')
                        ->toArray();
                    Session::put('staff_permissions', $permissions);
                }

                // Regenerate session ID for security
                $request->session()->regenerate();

                if ($this->shouldForcePasswordChange('Staff', (int) $staff->schoolID, $username)) {
                    Session::put('force_password_change', true);
                }

                if (Session::get('force_password_change')) {
                    if ($this->isAjax($request)) {
                        return response()->json(['success' => true, 'redirect' => route('staff.profile')]);
                    }
                    return redirect()->route('staff.profile')->with('error', 'Please change your password to continue.');
                }

                if ($this->isAjax($request)) {
                    return response()->json(['success' => true, 'redirect' => route('staffDashboard')]);
                }

                return redirect()->route('staffDashboard')
                    ->with('success', 'You have logged in successfully as Staff!');
                break;

            case 'Watchman':
                $watchman = Watchman::where('phone_number', $username)->first();

                if (!$watchman) {
                    return redirect()->back()
                        ->with('error', 'Watchman record not found.')
                        ->withInput($request->only('username'));
                }

                Session::put('schoolID', $watchman->schoolID);
                Session::put('watchmanID', $watchman->id);
                Session::put('user_type', $userLogin->user_type);
                Session::put('userID', $userLogin->id);
                Session::put('user_name', $userLogin->name);
                Session::put('user_email', $userLogin->email);
                Session::put('watchman_name', $watchman->first_name . ' ' . $watchman->last_name);

                $request->session()->regenerate();

                return redirect()->route('watchman.visitors')
                    ->with('success', 'You have logged in successfully as Watchman!');
                break;

            case 'parent':
                $parent = ParentModel::where('phone',$username)->first();
                // Set session data
                Session::put('parentID', $parent->parentID);
                Session::put('schoolID', $parent->schoolID);
                Session::put('user_type', $userLogin->user_type);

                   return redirect()->route('parentDashboard')
                    ->with('success', 'You have logged in successfully as Parent!');
                    break;

            default:
                return redirect()->back()
                    ->with('error', 'Invalid user type.')
                    ->withInput($request->only('username'));
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Clear all sessions
        Session::flush();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out successfully.');
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|min:4|max:10',
            'otp_token' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (!Session::get('otp_pending') || !Session::get('otp_token') || !Session::get('otp_code_hash')) {
            return $this->jsonFail('OTP session expired. Please login again.', 401);
        }

        if ((string) Session::get('otp_token') !== (string) $request->otp_token) {
            return $this->jsonFail('Invalid OTP session. Please login again.', 401);
        }

        $expiresAt = (int) Session::get('otp_expires_at', 0);
        if (!$expiresAt || time() > $expiresAt) {
            Session::forget(['otp_pending', 'otp_token', 'otp_code_hash', 'otp_expires_at', 'otp_payload']);
            return $this->jsonFail('OTP expired. Please login again.', 401);
        }

        $otp = preg_replace('/\s+/', '', (string) $request->otp);
        if (!Hash::check($otp, (string) Session::get('otp_code_hash'))) {
            return $this->jsonFail('Incorrect OTP.', 401);
        }

        $payload = Session::get('otp_payload', []);
        $userType = $payload['pending_user_type'] ?? null;
        $userId = $payload['pending_user_id'] ?? null;

        if (!$userType || !$userId) {
            return $this->jsonFail('OTP payload missing. Please login again.', 401);
        }

        Session::forget(['otp_pending', 'otp_token', 'otp_code_hash', 'otp_expires_at', 'otp_payload']);

        Session::put('user_type', $userType);
        Session::put('userID', $userId);
        Session::put('user_name', $payload['pending_username'] ?? '');
        Session::put('user_email', $payload['pending_user_email'] ?? '');

        if (isset($payload['schoolID'])) Session::put('schoolID', $payload['schoolID']);
        if ($userType === 'Teacher') {
            if (isset($payload['teacherID'])) Session::put('teacherID', $payload['teacherID']);
            if (isset($payload['teacher_name'])) Session::put('teacher_name', $payload['teacher_name']);
        }
        if ($userType === 'Staff') {
            if (isset($payload['staffID'])) Session::put('staffID', $payload['staffID']);
            if (isset($payload['staff_name'])) Session::put('staff_name', $payload['staff_name']);
            if (isset($payload['profession_id'])) Session::put('profession_id', $payload['profession_id']);

            if (!empty($payload['profession_id'])) {
                $permissions = \App\Models\StaffPermission::where('profession_id', $payload['profession_id'])
                    ->pluck('name')
                    ->toArray();
                Session::put('staff_permissions', $permissions);
            }
        }

        $request->session()->regenerate();

        $pendingUsername = $payload['pending_username'] ?? null;
        $schoolID = $payload['schoolID'] ?? null;
        if ($pendingUsername && $schoolID && in_array($userType, ['Admin', 'Teacher', 'Staff'], true)) {
            if ($this->shouldForcePasswordChange($userType, (int) $schoolID, (string) $pendingUsername)) {
                Session::put('force_password_change', true);
            }
        }

        $redirect = route('login');
        if ($userType === 'Admin') $redirect = route('AdminDashboard');
        if ($userType === 'Teacher') $redirect = route('teachersDashboard');
        if ($userType === 'Staff') $redirect = route('staffDashboard');

        if (Session::get('force_password_change') && in_array($userType, ['Admin', 'Teacher', 'Staff'], true)) {
            if ($userType === 'Admin') $redirect = route('admin.change_password');
            if ($userType === 'Teacher') $redirect = route('teacher.profile') . '#change-password';
            if ($userType === 'Staff') $redirect = route('staff.profile');
        }

        return response()->json(['success' => true, 'redirect' => $redirect]);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_token' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (!Session::get('otp_pending') || !Session::get('otp_token') || !Session::get('otp_payload')) {
            return $this->jsonFail('OTP session expired. Please login again.', 401);
        }

        if ((string) Session::get('otp_token') !== (string) $request->otp_token) {
            return $this->jsonFail('Invalid OTP session. Please login again.', 401);
        }

        $payload = Session::get('otp_payload', []);
        $phone = $payload['phone'] ?? null;
        if (!$phone) {
            return $this->jsonFail('Phone not found for OTP.', 422);
        }

        $otp = (string) random_int(100000, 999999);
        Session::put('otp_code_hash', Hash::make($otp));
        Session::put('otp_expires_at', time() + (10 * 60));
        $this->sendOtp($phone, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent',
            'expires_in' => 600,
            'masked_phone' => $this->maskPhone($phone),
        ]);
    }
}
