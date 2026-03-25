<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use App\Models\OtherStaff;
use App\Models\Watchman;
use App\Models\ParentModel;
use App\Models\Role;
use App\Models\StaffProfession;
use App\Models\SystemAlert;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class SuperAdminController extends Controller
{
    private function ensureSuperAdmin()
    {
        if (Session::get('user_type') !== 'SuperAdmin') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized access. Please login as Super Admin.'
                ], 403);
            }

            return redirect()->route('login')->with('error', 'Unauthorized access. Please login as Super Admin.');
        }

        return null;
    }

    public function dashboard()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $demoSchoolsCount = School::whereNull('environment')
            ->orWhere('environment', 'Demo')
            ->count();
        $liveSchoolsCount = School::where('environment', 'Live')->count();

        return view('SuperAdmin.dashboard', compact('demoSchoolsCount', 'liveSchoolsCount'));
    }

    public function schools()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $schools = School::orderBy('schoolID', 'desc')->get();

        $studentsBySchool = DB::table('students')
            ->select('schoolID', DB::raw('COUNT(*) as total'))
            ->groupBy('schoolID')
            ->pluck('total', 'schoolID');

        $teachersBySchool = DB::table('teachers')
            ->select('schoolID', DB::raw('COUNT(*) as total'))
            ->groupBy('schoolID')
            ->pluck('total', 'schoolID');

        $staffBySchool = DB::table('other_staff')
            ->select('schoolID', DB::raw('COUNT(*) as total'))
            ->groupBy('schoolID')
            ->pluck('total', 'schoolID');

        $parentsBySchool = DB::table('parents')
            ->select('schoolID', DB::raw('COUNT(*) as total'))
            ->groupBy('schoolID')
            ->pluck('total', 'schoolID');

        return view('SuperAdmin.schools', compact('schools', 'studentsBySchool', 'teachersBySchool', 'staffBySchool', 'parentsBySchool'));
    }

    public function changePasswordForm()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        return view('SuperAdmin.change_password');
    }

    public function changePassword(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = User::find(Session::get('userID'));
        if (!$user || $user->user_type !== 'SuperAdmin') {
            Session::flush();
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateSchoolSettings(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'environment' => 'nullable|in:Demo,Live',
            'status' => 'required|in:Active,Inactive',
            'two_factor_enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $school = School::find($request->schoolID);
        if (!$school) {
            return response()->json(['success' => false, 'message' => 'School not found'], 404);
        }

        $environment = $request->input('environment');
        if (!$environment) {
            $environment = 'Demo';
        }

        $school->environment = $environment;
        $school->status = $request->status;
        $school->two_factor_enabled = (bool)($request->input('two_factor_enabled', false));
        $school->save();

        return response()->json([
            'success' => true,
            'message' => 'School settings updated successfully.',
            'school' => [
                'schoolID' => $school->schoolID,
                'environment' => $school->environment ?: 'Demo',
                'status' => $school->status,
                'two_factor_enabled' => (bool)$school->two_factor_enabled,
            ]
        ]);
    }

    public function updateSchoolLogo(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        if (!$request->hasFile('school_logo')) {
            $errCode = isset($_FILES['school_logo']['error']) ? $_FILES['school_logo']['error'] : 'MISSING_ENTIRELY';
            $errMsg = "No valid file received. PHP Error Code: " . $errCode . " | Web Limit: " . ini_get('upload_max_filesize') . " | Post: " . ini_get('post_max_size');
            return response()->json(['success' => false, 'errors' => ['school_logo' => [$errMsg]]], 422);
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'school_logo' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $school = School::find($request->schoolID);
        if (!$school) {
            return response()->json(['success' => false, 'message' => 'School not found'], 404);
        }

        $basePath = base_path();
        $parentDir = dirname($basePath);
        $publicHtmlPath = $parentDir . '/public_html/logos';
        $docRootPath = (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/logos' : null);
        $localPublicPath = public_path('logos');

        if (file_exists($parentDir . '/public_html')) {
            $uploadPath = $publicHtmlPath;
        } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
            $uploadPath = $docRootPath;
        } else {
            $uploadPath = $localPublicPath;
        }

        if ($uploadPath && !file_exists($uploadPath)) {
            @mkdir($uploadPath, 0755, true);
        }

        if ($school->school_logo) {
            $possibleOldPaths = [
                $parentDir . '/public_html/' . $school->school_logo,
                (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/' . $school->school_logo : null),
                public_path($school->school_logo)
            ];
            foreach ($possibleOldPaths as $oldPath) {
                if ($oldPath && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $logo = $request->file('school_logo');
        $filename = time() . '_' . $logo->getClientOriginalName();
        $logo->move($uploadPath, $filename);
        $school->school_logo = 'logos/' . $filename;
        $school->save();

        return response()->json([
            'success' => true,
            'message' => 'School logo updated successfully.',
            'logo_url' => asset($school->school_logo),
        ]);
    }

    public function updateSchoolStamp(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        if (!$request->hasFile('school_stamp')) {
            $errCode = isset($_FILES['school_stamp']['error']) ? $_FILES['school_stamp']['error'] : 'MISSING_ENTIRELY';
            $errMsg = "No valid file received. PHP Error Code: " . $errCode . " | Web Limit: " . ini_get('upload_max_filesize') . " | Post: " . ini_get('post_max_size');
            return response()->json(['success' => false, 'errors' => ['school_stamp' => [$errMsg]]], 422);
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'school_stamp' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $school = School::find($request->schoolID);
        if (!$school) {
            return response()->json(['success' => false, 'message' => 'School not found'], 404);
        }

        $basePath = base_path();
        $parentDir = dirname($basePath);
        $publicHtmlPath = $parentDir . '/public_html/stamps';
        $docRootPath = (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/stamps' : null);
        $localPublicPath = public_path('stamps');

        if (file_exists($parentDir . '/public_html')) {
            $uploadPath = $publicHtmlPath;
        } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
            $uploadPath = $docRootPath;
        } else {
            $uploadPath = $localPublicPath;
        }

        if ($uploadPath && !file_exists($uploadPath)) {
            @mkdir($uploadPath, 0755, true);
        }

        if ($school->school_stamp) {
            $possibleOldPaths = [
                $parentDir . '/public_html/' . $school->school_stamp,
                (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/' . $school->school_stamp : null),
                public_path($school->school_stamp)
            ];
            foreach ($possibleOldPaths as $oldPath) {
                if ($oldPath && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $stamp = $request->file('school_stamp');
        $filename = time() . '_' . $stamp->getClientOriginalName();
        $stamp->move($uploadPath, $filename);
        $school->school_stamp = 'stamps/' . $filename;
        $school->save();

        return response()->json([
            'success' => true,
            'message' => 'School stamp updated successfully.',
            'stamp_url' => asset($school->school_stamp),
        ]);
    }

    public function updateSchoolSignature(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        if (!$request->hasFile('school_signature')) {
            $errCode = isset($_FILES['school_signature']['error']) ? $_FILES['school_signature']['error'] : 'MISSING_ENTIRELY';
            $errMsg = "No valid file received. PHP Error Code: " . $errCode . " | Web Limit: " . ini_get('upload_max_filesize') . " | Post: " . ini_get('post_max_size');
            return response()->json(['success' => false, 'errors' => ['school_signature' => [$errMsg]]], 422);
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'school_signature' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $school = School::find($request->schoolID);
        if (!$school) {
            return response()->json(['success' => false, 'message' => 'School not found'], 404);
        }

        $basePath = base_path();
        $parentDir = dirname($basePath);
        $publicHtmlPath = $parentDir . '/public_html/signatures';
        $docRootPath = (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/signatures' : null);
        $localPublicPath = public_path('signatures');

        if (file_exists($parentDir . '/public_html')) {
            $uploadPath = $publicHtmlPath;
        } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
            $uploadPath = $docRootPath;
        } else {
            $uploadPath = $localPublicPath;
        }

        if ($uploadPath && !file_exists($uploadPath)) {
            @mkdir($uploadPath, 0755, true);
        }

        if ($school->school_signature) {
            $possibleOldPaths = [
                $parentDir . '/public_html/' . $school->school_signature,
                (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/' . $school->school_signature : null),
                public_path($school->school_signature)
            ];
            foreach ($possibleOldPaths as $oldPath) {
                if ($oldPath && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $signature = $request->file('school_signature');
        $filename = time() . '_' . $signature->getClientOriginalName();
        $signature->move($uploadPath, $filename);
        $school->school_signature = 'signatures/' . $filename;
        $school->save();

        return response()->json([
            'success' => true,
            'message' => 'School signature updated successfully.',
            'signature_url' => asset($school->school_signature),
        ]);
    }

    public function userPassword()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $schools = School::orderBy('school_name', 'asc')->get(['schoolID', 'school_name', 'school_logo', 'registration_number']);
        $userTypes = ['All', 'Admin', 'Teacher', 'Staff', 'Watchman', 'parent'];

        return view('SuperAdmin.user_password', compact('schools', 'userTypes'));
    }

    public function customerCare()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $schools = School::orderBy('school_name', 'asc')->get(['schoolID', 'school_name', 'school_logo', 'registration_number']);
        $userTypes = ['All', 'Admin', 'Teacher', 'Staff', 'Watchman', 'parent'];

        return view('SuperAdmin.customer_care', compact('schools', 'userTypes'));
    }

    public function systemAlerts()
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $schools = School::orderBy('school_name', 'asc')->get(['schoolID', 'school_name', 'school_logo', 'registration_number']);
        $userTypes = ['Admin', 'Teacher', 'Staff', 'parent'];

        return view('SuperAdmin.system_alerts', compact('schools', 'userTypes'));
    }

    public function getSystemAlertOptions(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        try {
            $validator = Validator::make($request->all(), [
                'schoolID' => 'required|integer|exists:schools,schoolID',
                'user_type' => 'required|string|in:Admin,Teacher,Staff,parent',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $schoolID = (int) $request->schoolID;
            $userType = $request->user_type;

            if ($userType === 'Teacher') {
                $roles = Role::where('schoolID', $schoolID)
                    ->orderBy('role_name')
                    ->get(['id', 'role_name', 'name']);

                $roles = $roles->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'name' => $r->role_name,
                    ];
                });

                return response()->json(['success' => true, 'roles' => $roles]);
            }

            if ($userType === 'Staff') {
                $professions = StaffProfession::where('schoolID', $schoolID)
                    ->orderBy('name')
                    ->get(['id', 'name']);

                $professions = $professions->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                    ];
                });

                return response()->json(['success' => true, 'professions' => $professions]);
            }

            // Admin & parent: no roles/positions
            return response()->json(['success' => true, 'options' => []]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin getSystemAlertOptions failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listSystemAlerts(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        try {
            $validator = Validator::make($request->all(), [
                'schoolID' => 'required|integer|exists:schools,schoolID',
                'user_type' => 'required|string|in:Admin,Teacher,Staff,parent',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $schoolID = (int) $request->schoolID;
            $userType = $request->user_type;

            $alerts = SystemAlert::where('schoolID', $schoolID)
                ->where('target_user_type', $userType)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json(['success' => true, 'alerts' => $alerts]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin listSystemAlerts failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeSystemAlert(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        try {

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'target_user_type' => 'required|string|in:Admin,Teacher,Staff,parent',
            'applies_to_all' => 'nullable|boolean',
            'target_role_id' => 'nullable|integer',
            'target_profession_id' => 'nullable|integer',
            'alert_type' => 'required|string|in:info,warning,success,danger',
            'message' => 'required|string|max:1500',
            'is_marquee' => 'nullable|boolean',
            'width' => 'nullable|string|max:20',
            'bg_color' => 'nullable|string|max:30',
            'text_color' => 'nullable|string|max:30',
            'is_bold' => 'nullable|boolean',
            'font_size' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['applies_to_all'] = (bool) ($request->input('applies_to_all', false));
        $data['is_marquee'] = (bool) ($request->input('is_marquee', false));
        $data['is_bold'] = (bool) ($request->input('is_bold', false));
        $data['font_size'] = $request->input('font_size');
        $data['is_active'] = (bool) ($request->input('is_active', true));
        $data['created_by'] = Session::get('userID');

        if (in_array($data['target_user_type'], ['Admin', 'parent'], true)) {
            $data['applies_to_all'] = true;
            $data['target_role_id'] = null;
            $data['target_profession_id'] = null;
        } elseif ($data['target_user_type'] === 'Teacher') {
            $data['target_profession_id'] = null;
            if ($data['applies_to_all']) {
                $data['target_role_id'] = null;
            }
        } else {
            $data['target_role_id'] = null;
            if ($data['applies_to_all']) {
                $data['target_profession_id'] = null;
            }
        }

        $alert = SystemAlert::create($data);

        return response()->json(['success' => true, 'alert' => $alert]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin storeSystemAlert failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateSystemAlert(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        try {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:system_alerts,id',
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'target_user_type' => 'required|string|in:Admin,Teacher,Staff,parent',
            'applies_to_all' => 'nullable|boolean',
            'target_role_id' => 'nullable|integer',
            'target_profession_id' => 'nullable|integer',
            'alert_type' => 'required|string|in:info,warning,success,danger',
            'message' => 'required|string|max:1500',
            'is_marquee' => 'nullable|boolean',
            'width' => 'nullable|string|max:20',
            'bg_color' => 'nullable|string|max:30',
            'text_color' => 'nullable|string|max:30',
            'is_bold' => 'nullable|boolean',
            'font_size' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['applies_to_all'] = (bool) ($request->input('applies_to_all', false));
        $data['is_marquee'] = (bool) ($request->input('is_marquee', false));
        $data['is_bold'] = (bool) ($request->input('is_bold', false));
        $data['font_size'] = $request->input('font_size');
        $data['is_active'] = (bool) ($request->input('is_active', true));

        if (in_array($data['target_user_type'], ['Admin', 'parent'], true)) {
            $data['applies_to_all'] = true;
            $data['target_role_id'] = null;
            $data['target_profession_id'] = null;
        } elseif ($data['target_user_type'] === 'Teacher') {
            $data['target_profession_id'] = null;
            if ($data['applies_to_all']) {
                $data['target_role_id'] = null;
            }
        } else {
            $data['target_role_id'] = null;
            if ($data['applies_to_all']) {
                $data['target_profession_id'] = null;
            }
        }

        $alert = SystemAlert::find($data['id']);
        $alert->update($data);

        return response()->json(['success' => true, 'alert' => $alert]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin updateSystemAlert failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteSystemAlert($id)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        try {

        $alert = SystemAlert::find($id);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Alert not found'], 404);
        }

        $alert->delete();

        return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin deleteSystemAlert failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSchoolUsers(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'user_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $schoolID = (int) $request->schoolID;
        $filterType = $request->input('user_type', 'All');

        $school = School::find($schoolID);
        if (!$school) {
            return response()->json(['success' => false, 'message' => 'School not found'], 404);
        }

        $logoUrl = $school->school_logo ? asset($school->school_logo) : null;
        $schoolName = $school->school_name;

        $rows = [];

        // Admin
        if ($filterType === 'All' || $filterType === 'Admin') {
            $adminUsername = $school->registration_number;
            if ($adminUsername) {
                $rows[] = [
                    'user_type' => 'Admin',
                    'username' => $adminUsername,
                    'phone' => $school->phone,
                    'schoolID' => $schoolID,
                    'school_name' => $schoolName,
                    'school_logo' => $logoUrl,
                ];
            }
        }

        // Teacher
        if ($filterType === 'All' || $filterType === 'Teacher') {
            $teachers = Teacher::where('schoolID', $schoolID)
                ->select('employee_number', 'phone_number')
                ->orderBy('employee_number')
                ->get();
            foreach ($teachers as $t) {
                $rows[] = [
                    'user_type' => 'Teacher',
                    'username' => $t->employee_number,
                    'phone' => $t->phone_number,
                    'schoolID' => $schoolID,
                    'school_name' => $schoolName,
                    'school_logo' => $logoUrl,
                ];
            }
        }

        // Staff
        if ($filterType === 'All' || $filterType === 'Staff') {
            $staff = OtherStaff::where('schoolID', $schoolID)
                ->select('employee_number', 'phone_number')
                ->orderBy('employee_number')
                ->get();
            foreach ($staff as $s) {
                $rows[] = [
                    'user_type' => 'Staff',
                    'username' => $s->employee_number,
                    'phone' => $s->phone_number,
                    'schoolID' => $schoolID,
                    'school_name' => $schoolName,
                    'school_logo' => $logoUrl,
                ];
            }
        }

        // Watchman
        if ($filterType === 'All' || $filterType === 'Watchman') {
            $watchmen = Watchman::where('schoolID', $schoolID)
                ->select('phone_number')
                ->orderBy('id', 'desc')
                ->get();
            foreach ($watchmen as $w) {
                $rows[] = [
                    'user_type' => 'Watchman',
                    'username' => $w->phone_number,
                    'phone' => $w->phone_number,
                    'schoolID' => $schoolID,
                    'school_name' => $schoolName,
                    'school_logo' => $logoUrl,
                ];
            }
        }

        // Parent
        if ($filterType === 'All' || $filterType === 'parent') {
            $parents = ParentModel::where('schoolID', $schoolID)
                ->select('phone')
                ->orderBy('parentID', 'desc')
                ->get();
            foreach ($parents as $p) {
                $rows[] = [
                    'user_type' => 'parent',
                    'username' => $p->phone,
                    'phone' => $p->phone,
                    'schoolID' => $schoolID,
                    'school_name' => $schoolName,
                    'school_logo' => $logoUrl,
                ];
            }
        }

        // cleanup
        $rows = array_values(array_filter($rows, function ($r) {
            $u = trim((string)($r['username'] ?? ''));
            return $u !== '' && strtolower($u) !== 'null' && strtolower($u) !== 'undefined';
        }));

        return response()->json(['success' => true, 'school' => ['schoolID' => $schoolID, 'school_name' => $schoolName], 'users' => $rows]);
    }

    public function sendUserCredentialsSms(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'user_type' => 'required|string',
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'message' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $first = $errors->first();
            return response()->json([
                'success' => false,
                'error' => $first ?: 'Validation failed',
                'errors' => $errors
            ], 422);
        }

        try {
            $school = School::find((int) $request->schoolID);
            if (!$school) {
                return response()->json(['success' => false, 'error' => 'School not found'], 404);
            }

            $userType = $request->user_type;
            $username = $request->username;
            $phone = $request->phone;

            $plainPassword = null;

            if ($userType === 'Admin') {
                $plainPassword = 'Admin@3345';
            } elseif ($userType === 'Teacher') {
                $t = Teacher::where('schoolID', $school->schoolID)->where('employee_number', $username)->first();
                $plainPassword = $t ? $t->last_name : null;
            } elseif ($userType === 'Staff') {
                $s = OtherStaff::where('schoolID', $school->schoolID)->where('employee_number', $username)->first();
                $plainPassword = $s ? $s->last_name : null;
            } elseif ($userType === 'Watchman') {
                $w = Watchman::where('schoolID', $school->schoolID)->where('phone_number', $username)->first();
                $plainPassword = $w ? $w->last_name : null;
            } elseif ($userType === 'parent') {
                $p = ParentModel::where('schoolID', $school->schoolID)->where('phone', $username)->first();
                $plainPassword = $p ? $p->last_name : null;
            }

            if (!$plainPassword) {
                Log::warning('SuperAdmin sendUserCredentialsSms: default password not found', [
                    'schoolID' => $school->schoolID,
                    'user_type' => $userType,
                    'username' => $username,
                ]);
                return response()->json(['success' => false, 'error' => 'Could not determine default password for this user.']);
            }

            $user = User::where('name', $username)->where('user_type', $userType)->first();
            if (!$user) {
                // Auto-create missing login account (common for old data)
                $email = null;
                $fingerprintId = null;

                if ($userType === 'Admin') {
                    $email = $school->email ?: ($school->registration_number . '@admin.local');
                } elseif ($userType === 'Teacher') {
                    $t = Teacher::where('schoolID', $school->schoolID)->where('employee_number', $username)->first();
                    if (!$t) {
                        return response()->json(['success' => false, 'error' => 'Teacher record not found for this username.']);
                    }
                    $email = $t->email ?: ($username . '@teacher.local');
                    $fingerprintId = $t->fingerprint_id ?? $t->id;
                } elseif ($userType === 'Staff') {
                    $s = OtherStaff::where('schoolID', $school->schoolID)->where('employee_number', $username)->first();
                    if (!$s) {
                        return response()->json(['success' => false, 'error' => 'Staff record not found for this username.']);
                    }
                    $email = $s->email ?: ($username . '@staff.local');
                    $fingerprintId = $s->fingerprint_id ?? $s->id;
                } elseif ($userType === 'Watchman') {
                    $w = Watchman::where('schoolID', $school->schoolID)->where('phone_number', $username)->first();
                    if (!$w) {
                        return response()->json(['success' => false, 'error' => 'Watchman record not found for this username.']);
                    }
                    $email = $w->email ?: ($username . '@watchman.local');
                } elseif ($userType === 'parent') {
                    $p = ParentModel::where('schoolID', $school->schoolID)->where('phone', $username)->first();
                    if (!$p) {
                        return response()->json(['success' => false, 'error' => 'Parent record not found for this username.']);
                    }
                    $email = $p->email ?: ($username . '@parent.local');
                }

                Log::warning('SuperAdmin sendUserCredentialsSms: login account missing; creating', [
                    'schoolID' => $school->schoolID,
                    'user_type' => $userType,
                    'username' => $username,
                    'email' => $email,
                ]);

                // If email is already used by another account, do not reuse it (users.email is unique)
                if ($email && User::where('email', $email)->exists()) {
                    Log::warning('SuperAdmin sendUserCredentialsSms: email already taken; setting email to null', [
                        'email' => $email,
                        'username' => $username,
                        'user_type' => $userType,
                    ]);
                    $email = null;
                }

                try {
                    $user = User::create([
                        'name' => $username,
                        'email' => $email,
                        'password' => Hash::make($plainPassword),
                        'user_type' => $userType,
                        'fingerprint_id' => $fingerprintId,
                    ]);
                } catch (QueryException $qe) {
                    // Retry without email on duplicate key edge-cases
                    Log::error('SuperAdmin sendUserCredentialsSms: user create query exception; retrying without email', [
                        'message' => $qe->getMessage(),
                        'username' => $username,
                        'user_type' => $userType,
                        'email' => $email,
                    ]);
                    $user = User::create([
                        'name' => $username,
                        'email' => null,
                        'password' => Hash::make($plainPassword),
                        'user_type' => $userType,
                        'fingerprint_id' => $fingerprintId,
                    ]);
                }
            }

            $user->password = Hash::make($plainPassword);
            $user->save();

            $message = $request->message;
            $message = str_replace(
                ['{username}', '{password}', '{school}'],
                [$username, $plainPassword, ($school->school_name ?? 'ShuleXpert')],
                $message
            );

            $smsService = new SmsService();
            $smsResult = $smsService->sendSms($phone, $message);

            if (!($smsResult['success'] ?? false)) {
                Log::error('SuperAdmin sendUserCredentialsSms: gateway rejected/failed', [
                    'schoolID' => $school->schoolID,
                    'user_type' => $userType,
                    'username' => $username,
                    'phone' => $phone,
                    'sms_message' => $smsResult['message'] ?? null,
                    'http_code' => $smsResult['http_code'] ?? null,
                    'response' => $smsResult['response'] ?? null,
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $smsResult['message'] ?? 'Failed to send SMS',
                    'http_code' => $smsResult['http_code'] ?? null,
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('SuperAdmin sendUserCredentialsSms: exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function sendCustomerCareSms(Request $request)
    {
        if ($resp = $this->ensureSuperAdmin()) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'schoolID' => 'required|integer|exists:schools,schoolID',
            'user_type' => 'required|string',
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'message' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $first = $errors->first();
            return response()->json([
                'success' => false,
                'error' => $first ?: 'Validation failed',
                'errors' => $errors
            ], 422);
        }

        try {
            $school = School::find((int) $request->schoolID);
            if (!$school) {
                return response()->json(['success' => false, 'error' => 'School not found'], 404);
            }

            $phone = $request->phone;
            $message = $request->message;

            $message = str_replace(
                ['{username}', '{school}'],
                [$request->username, ($school->school_name ?? 'ShuleXpert')],
                $message
            );

            $smsService = new SmsService();
            $smsResult = $smsService->sendSms($phone, $message);

            if (!($smsResult['success'] ?? false)) {
                Log::error('SuperAdmin sendCustomerCareSms: gateway rejected/failed', [
                    'schoolID' => $school->schoolID,
                    'user_type' => $request->user_type,
                    'username' => $request->username,
                    'phone' => $phone,
                    'sms_message' => $smsResult['message'] ?? null,
                    'http_code' => $smsResult['http_code'] ?? null,
                    'response' => $smsResult['response'] ?? null,
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $smsResult['message'] ?? 'Failed to send SMS',
                    'http_code' => $smsResult['http_code'] ?? null,
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('SuperAdmin sendCustomerCareSms: exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
