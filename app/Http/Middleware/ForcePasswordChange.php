<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $userType = Session::get('user_type');
        $force = Session::get('force_password_change');

        if ($force && in_array($userType, ['Admin', 'Teacher', 'Staff'], true)) {
            $routeName = optional($request->route())->getName();
            $path = ltrim($request->path(), '/');

            $allowedRouteNames = [
                'login',
                'auth',
                'auth.otp.verify',
                'auth.otp.resend',
                'logout',
                'teacher.profile',
                'teacher.change_password',
                'staff.profile',
                'staff.profile.update',
                'admin.change_password',
                'admin.change_password.store',
            ];

            $allowedPaths = [
                'login',
                'auth',
                'auth/otp/verify',
                'auth/otp/resend',
                'logout',
                'teacher/profile',
                'teacher/change-password',
                'staff/profile',
                'staff/profile/update',
                'admin/change-password',
            ];

            $isAllowed = ($routeName && in_array($routeName, $allowedRouteNames, true)) || in_array($path, $allowedPaths, true);

            if (!$isAllowed) {
                if ($userType === 'Teacher') {
                    return redirect()->route('teacher.profile')->with('error', 'Please change your password to continue.');
                }

                if ($userType === 'Staff') {
                    return redirect()->route('staff.profile')->with('error', 'Please change your password to continue.');
                }

                return redirect()->route('admin.change_password')->with('error', 'Please change your password to continue.');
            }
        }

        return $next($request);
    }
}
