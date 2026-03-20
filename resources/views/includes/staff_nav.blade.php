<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff</title>
    <meta name="description" content="ShuleXpert Staff Portal">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet" type="text/css">

    <!-- jQuery and Bootstrap JS (Must be loaded before other scripts) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #left-panel,
        #left-panel .navbar,
        #left-panel .navbar-default,
        #left-panel .main-menu,
        #left-panel .navbar-nav,
        #left-panel ul {
            background-color: #ffffff !important;
            color: #2f2f2f !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .nav-link,
        #left-panel a.nav-link,
        #left-panel li a,
        #left-panel .navbar-nav li a,
        #left-panel .navbar-nav > li > a {
            color: #2f2f2f !important;
            font-weight: 600;
            background-color: transparent !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .nav-link i,
        #left-panel a.nav-link i,
        #left-panel li a i,
        #left-panel .navbar-nav li a i,
        #left-panel .fa {
            color: #940000 !important;
        }

        #left-panel .nav-link:hover,
        #left-panel a.nav-link:hover,
        #left-panel li a:hover,
        #left-panel .navbar-nav li a:hover,
        #left-panel .navbar-nav > li > a:hover,
        #left-panel li:hover {
            background-color: #f5f5f5 !important;
            color: #2f2f2f !important;
        }

        #left-panel .nav-link.active,
        #left-panel a.nav-link.active,
        #left-panel li.active > a,
        #left-panel .navbar-nav > li.active > a,
        #left-panel .nav-link.menu-active,
        #left-panel a.nav-link.menu-active,
        #left-panel li.menu-active > a {
            background-color: rgba(148, 0, 0, 0.08) !important;
            color: #2f2f2f !important;
            border-radius: 4px !important;
            padding: 8px 15px !important;
            margin: 2px 0 !important;
        }

        #left-panel .nav-link.active i,
        #left-panel a.nav-link.active i,
        #left-panel li.active > a i,
        #left-panel .nav-link.menu-active i,
        #left-panel a.nav-link.menu-active i {
            color: #940000 !important;
        }

        #left-panel p,
        #left-panel .navbar-brand,
        #left-panel .navbar-brand:hover {
            color: #2f2f2f !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .navbar-header .navbar-brand {
            color: #2f2f2f !important;
            font-weight: 700 !important;
        }

        #left-panel .navbar-toggler,
        #left-panel .navbar-toggler i {
            color: #2f2f2f !important;
        }

        #left-panel .navbar-nav li {
            background-color: transparent !important;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        #left-panel * {
            color: #2f2f2f !important;
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel, #left-panel p, #left-panel span, #left-panel a, #left-panel li, #left-panel .navbar-brand {
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
        }

        #left-panel .fa, #left-panel .fa:before, #left-panel i.fa, #left-panel [class*="fa-"]:before, #left-panel [class^="fa-"]:before {
            font-family: 'FontAwesome' !important;
        }

        #left-panel i,
        #left-panel .fa,
        #left-panel [class*="fa-"],
        #left-panel [class^="fa-"] {
            color: #940000 !important;
            font-family: 'FontAwesome' !important;
        }

        .sidebar-links-container {
            overflow-y: hidden !important;
            overflow-x: hidden !important;
            max-height: calc(100vh - 200px) !important;
            width: 100% !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        .sidebar-links-container::-webkit-scrollbar {
            width: 0 !important;
            display: none !important;
        }

        .sidebar-links-container:hover {
            overflow-y: auto !important;
            scrollbar-width: thin !important;
            scrollbar-color: #cfcfcf #f0f0f0 !important;
            -ms-overflow-style: scrollbar !important;
        }

        .sidebar-links-container:hover::-webkit-scrollbar {
            width: 8px !important;
            display: block !important;
        }

        .sidebar-links-container::-webkit-scrollbar-track {
            background: #f0f0f0 !important;
            border-radius: 4px !important;
        }

        .sidebar-links-container::-webkit-scrollbar-thumb {
            background: #cfcfcf !important;
            border-radius: 4px !important;
        }

        .sidebar-links-container::-webkit-scrollbar-thumb:hover {
            background: #bdbdbd !important;
        }

        .sidebar-links-container ul {
            width: 100% !important;
        }

        .dropdown-nav-item {
            position: relative;
        }

        .dropdown-nav-item .dropdown-toggle {
            cursor: pointer;
            position: relative;
        }

        .dropdown-nav-item .dropdown-toggle i.fa-chevron-down {
            transition: transform 0.3s ease;
            font-size: 0.75rem;
            margin-top: 3px;
        }

        .dropdown-nav-item .dropdown-toggle[aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .dropdown-nav-item .submenu {
            background-color: #f8f9fa !important;
            border-left: 2px solid #e0e0e0;
            margin-left: 10px;
        }

        .dropdown-nav-item .submenu li {
            border-bottom: none !important;
        }

        .dropdown-nav-item .submenu li a {
            padding-left: 15px !important;
            font-size: 0.9rem;
            color: #2f2f2f !important;
        }

        .dropdown-nav-item .submenu li a:hover {
            background-color: #f2f2f2 !important;
            padding-left: 20px !important;
        }

        .sidebar-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(148, 0, 0, 0.06);
            border: 1px solid rgba(148, 0, 0, 0.25);
            border-radius: 8px;
        }

        .sidebar-profile img.profile-image {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            background: rgba(148, 0, 0, 0.08);
            border: 2px solid rgba(148, 0, 0, 0.35);
        }

        .sidebar-profile .profile-name {
            font-weight: 700;
            color: #2f2f2f !important;
        }

        .sidebar-profile .profile-role {
            font-size: 0.8rem;
            color: #666666 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #left-panel {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: 100vh !important;
        }

        #left-panel::-webkit-scrollbar {
            width: 8px !important;
        }

        #left-panel::-webkit-scrollbar-track {
            background: #f0f0f0 !important;
        }

        #left-panel::-webkit-scrollbar-thumb {
            background: #940000 !important;
            border-radius: 4px !important;
        }

        #left-panel::-webkit-scrollbar-thumb:hover {
            background: #7a0000 !important;
        }
    </style>
</head>
<body>
@php
    $sessionUserType = \Illuminate\Support\Facades\Session::get('user_type');
    if ($sessionUserType === 'Staff') {
        if (!isset($staff)) {
            $staffIdFromSession = \Illuminate\Support\Facades\Session::get('staffID');
            $staff = $staffIdFromSession ? \App\Models\OtherStaff::find($staffIdFromSession) : null;
        }

        if (!isset($staffProfession)) {
            $staffProfession = ($staff && $staff->profession_id)
                ? \App\Models\StaffProfession::find($staff->profession_id)
                : null;
        }

        if (!isset($staffPermissionsByCategory)) {
            $staffPermissionsByCategory = collect();
            if ($staff && $staff->profession_id) {
                $staffPermissionsByCategory = \App\Models\StaffPermission::where('profession_id', $staff->profession_id)
                    ->get()
                    ->groupBy('permission_category');
            }
        }
    }
@endphp
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand hidden" href="#">SL</a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="text-center mt-3 mb-2">
                    @php
                        $imgPath = isset($staff) && $staff && $staff->image
                            ? asset('userImages/' . $staff->image)
                            : (isset($staff) && $staff && $staff->gender == 'Female'
                                ? asset('images/female.png')
                                : asset('images/male.png'));
                        $staffRoleName = isset($staffProfession) && $staffProfession
                            ? $staffProfession->name
                            : 'Staff';
                    @endphp
                    <div class="sidebar-profile">
                        <img src="{{ $imgPath }}" alt="Staff" class="profile-image">
                        <div class="profile-meta text-left">
                            <div class="profile-role">User Type</div>
                            <div class="profile-name">{{ $staffRoleName }}</div>
                        </div>
                    </div>
                </li>

                <li class="sidebar-links-container">
                    <ul style="list-style: none; padding: 0; margin: 0; font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif;">
                        <li><a href="{{ route('staffDashboard') }}" class="nav-link"><i class="fa fa-tachometer"></i> Dashboard</a></li>

                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#staffFeedback" aria-expanded="false">
                                <i class="fa fa-commenting"></i> Suggestions & Incidents <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="staffFeedback" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                @php
                                    $staffID = \Illuminate\Support\Facades\Session::get('staffID');
                                    $schoolID = \Illuminate\Support\Facades\Session::get('schoolID');
                                    $unreadStaffSuggestions = 0;
                                    $unreadStaffIncidents = 0;
                                    if ($staffID && $schoolID) {
                                        $unreadStaffSuggestions = \App\Models\StaffFeedback::where('schoolID', $schoolID)
                                            ->where('staffID', $staffID)
                                            ->where('type', 'suggestion')
                                            ->where('is_read_by_staff', false)
                                            ->count();
                                        $unreadStaffIncidents = \App\Models\StaffFeedback::where('schoolID', $schoolID)
                                            ->where('staffID', $staffID)
                                            ->where('type', 'incident')
                                            ->where('is_read_by_staff', false)
                                            ->count();
                                    }
                                @endphp
                                <li>
                                    <a href="{{ route('staff.suggestions') }}" class="nav-link">
                                        <i class="fa fa-lightbulb-o"></i> Suggestions
                                        @if($unreadStaffSuggestions > 0)
                                            <span class="badge badge-danger ml-1">{{ $unreadStaffSuggestions }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('staff.incidents') }}" class="nav-link">
                                        <i class="fa fa-exclamation-triangle"></i> Incidents
                                        @if($unreadStaffIncidents > 0)
                                            <span class="badge badge-danger ml-1">{{ $unreadStaffIncidents }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#staffRequests" aria-expanded="false">
                                <i class="fa fa-briefcase"></i> HR Requests <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="staffRequests" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('staff.permissions') }}" class="nav-link"><i class="fa fa-check-square-o"></i> Permission</a></li>
                                <li><a href="{{ route('staff.leave') }}" class="nav-link"><i class="fa fa-calendar"></i> Leave</a></li>
                                <li><a href="{{ route('staff.payroll') }}" class="nav-link"><i class="fa fa-money"></i> Payroll</a></li>
                            </ul>
                        </li>

                        @if($isHOD)
                        <!-- Goal Management - HOD View -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#departmentGoals" aria-expanded="false">
                                <i class="fa fa-bullseye"></i> Department Goals <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="departmentGoals" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('hod.goals.assigned') }}" class="nav-link"><i class="fa fa-tasks"></i> Assigned Tasks</a></li>
                                <li><a href="{{ route('hod.goals.assignMebers') }}" class="nav-link"><i class="fa fa-users"></i> Assign to Members</a></li>
                                <li><a href="{{ route('hod.goals.progress') }}" class="nav-link"><i class="fa fa-line-chart"></i> Department Progress</a></li>
                                <li><a href="{{ route('hod.goals.review') }}" class="nav-link"><i class="fa fa-upload"></i> Submit for Review</a></li>
                            </ul>
                        </li>
                        @endif

                        <!-- My Assigned Tasks -->
                        <li>
                            <a href="{{ route('member.goals.myTasks') }}" class="nav-link">
                                <i class="fa fa-thumb-tack"></i> My Assigned Tasks
                            </a>
                        </li>

                        <!-- My Performance Tasks -->

                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#myPerformance" aria-expanded="false">
                                <i class="fa fa-star"></i> My Tasks & KPIs <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="myPerformance" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('sgpm.tasks.index') }}" class="nav-link"><i class="fa fa-tasks"></i> My Tasks</a></li>
                                <li><a href="{{ route('sgpm.performance.staff') }}" class="nav-link"><i class="fa fa-briefcase"></i> My Scorecard</a></li>
                            </ul>
                        </li>

                        @php
                            // Fallback to fetch permissions if not passed from controller
                            if (!isset($staffPermissionsByCategory) || $staffPermissionsByCategory->isEmpty()) {
                                $navStaffID = \Illuminate\Support\Facades\Session::get('staffID');
                                if ($navStaffID) {
                                    $navStaff = \App\Models\OtherStaff::find($navStaffID);
                                    if ($navStaff && $navStaff->profession_id) {
                                        $staffPermissionsByCategory = \App\Models\StaffPermission::where('profession_id', $navStaff->profession_id)
                                            ->get()
                                            ->groupBy('permission_category');
                                    }
                                }
                            }

                            if (!isset($staffPermissionsByCategory)) {
                                $staffPermissionsByCategory = collect();
                            }

                            $hasExaminationPermission = false;
                            $hasSubjectPermission = false;
                            $hasClassesPermission = false;
                            $hasResultPermission = false;
                            $hasAttendancePermission = false;
                            $hasStudentPermission = false;
                            $hasParentPermission = false;
                            $hasTimetablePermission = false;
                            $hasTeacherPermission = false;
                            $hasFeesPermission = false;
                            $hasAccommodationPermission = false;
                            $hasLibraryPermission = false;
                            $hasCalendarPermission = false;
                            $hasFingerprintPermission = false;
                            $hasTaskPermission = false;
                            $hasSmsPermission = false;
                            $hasRevenuePermission = false;
                            $hasExpensesPermission = false;
                            $hasResourcesPermission = false;
                            $hasSubjectAnalysisPermission = false;
                            $hasPrintingUnitPermission = false;
                            $hasWatchmanPermission = false;
                            $hasSchoolVisitorsPermission = false;
                            $hasSchemeOfWorkPermission = false;
                            $hasLessonPlansPermission = false;
                            $hasAcademicYearsPermission = false;
                             $hasStaffPermission = false;
                             $hasAccountantPermission = false;
                             $hasGoalPermission = false;
                             $hasHRPermission = false;
                             $hasSchoolPermission = false;
                             $hasSponsorPermission = false;
                             $hasDepartmentPermission = false;
                             $hasStudentIDCardPermission = false;
                             $hasTeacherDutyPermission = false;
                             $hasFeedbackPermission = false;
                             $hasStaffFeedbackPermission = false;
                             $hasPerformancePermission = false;

                            if (isset($staffPermissionsByCategory) && $staffPermissionsByCategory->count() > 0) {
                                $hasExaminationPermission = $staffPermissionsByCategory->has('examination');
                                $hasSubjectPermission = $staffPermissionsByCategory->has('subject');
                                $hasClassesPermission = $staffPermissionsByCategory->has('classes');
                                $hasResultPermission = $staffPermissionsByCategory->has('result');
                                $hasAttendancePermission = $staffPermissionsByCategory->has('attendance');
                                $hasStudentPermission = $staffPermissionsByCategory->has('student');
                                $hasParentPermission = $staffPermissionsByCategory->has('parent');
                                $hasTimetablePermission = $staffPermissionsByCategory->has('timetable');
                                $hasTeacherPermission = $staffPermissionsByCategory->has('teacher');
                                $hasFeesPermission = $staffPermissionsByCategory->has('fees');
                                $hasAccommodationPermission = $staffPermissionsByCategory->has('accommodation');
                                $hasLibraryPermission = $staffPermissionsByCategory->has('library');
                                $hasCalendarPermission = $staffPermissionsByCategory->has('calendar');
                                $hasFingerprintPermission = $staffPermissionsByCategory->has('fingerprint');
                                $hasTaskPermission = $staffPermissionsByCategory->has('task');
                                $hasSmsPermission = $staffPermissionsByCategory->has('sms');
                                $hasRevenuePermission = $staffPermissionsByCategory->has('revenue');
                                $hasExpensesPermission = $staffPermissionsByCategory->has('expenses');
                                $hasResourcesPermission = $staffPermissionsByCategory->has('resources');
                                $hasSubjectAnalysisPermission = $staffPermissionsByCategory->has('subject_analysis');
                                $hasPrintingUnitPermission = $staffPermissionsByCategory->has('printing_unit');
                                $hasWatchmanPermission = $staffPermissionsByCategory->has('watchman');
                                $hasSchoolVisitorsPermission = $staffPermissionsByCategory->has('school_visitors');
                                $hasSchemeOfWorkPermission = $staffPermissionsByCategory->has('scheme_of_work');
                                $hasLessonPlansPermission = $staffPermissionsByCategory->has('lesson_plans');
                                $hasAcademicYearsPermission = $staffPermissionsByCategory->has('academic_years');
                                 $hasStaffPermission = $staffPermissionsByCategory->has('staff');
                                 $hasAccountantPermission = $staffPermissionsByCategory->has('accountant');
                                 $hasGoalPermission = $staffPermissionsByCategory->has('goal');
                                 $hasHRPermission = $staffPermissionsByCategory->has('hr');
                                 $hasSchoolPermission = $staffPermissionsByCategory->has('school');
                                 $hasSponsorPermission = $staffPermissionsByCategory->has('sponsor');
                                 $hasDepartmentPermission = $staffPermissionsByCategory->has('department');
                                 $hasStudentIDCardPermission = $staffPermissionsByCategory->has('student_id_card');
                                 $hasTeacherDutyPermission = $staffPermissionsByCategory->has('teacher_duty');
                                 $hasFeedbackPermission = $staffPermissionsByCategory->has('feedback');
                                 $hasStaffFeedbackPermission = $staffPermissionsByCategory->has('staff_feedback');
                                 $hasPerformancePermission = $staffPermissionsByCategory->has('performance');
                            }
                        @endphp

                        @if($hasExaminationPermission || $hasSubjectPermission || $hasClassesPermission || $hasResultPermission || $hasAttendancePermission || $hasStudentPermission || $hasParentPermission || $hasTimetablePermission || $hasFeesPermission || $hasAccommodationPermission || $hasLibraryPermission || $hasCalendarPermission || $hasFingerprintPermission || $hasTaskPermission || $hasSmsPermission || $hasTeacherPermission || $hasRevenuePermission || $hasExpensesPermission || $hasResourcesPermission || $hasSubjectAnalysisPermission || $hasPrintingUnitPermission || $hasWatchmanPermission || $hasSchoolVisitorsPermission || $hasSchemeOfWorkPermission || $hasLessonPlansPermission || $hasAcademicYearsPermission || $hasStaffPermission || $hasAccountantPermission || $hasGoalPermission || $hasHRPermission || $hasSchoolPermission || $hasSponsorPermission || $hasDepartmentPermission || $hasStudentIDCardPermission || $hasTeacherDutyPermission || $hasFeedbackPermission || $hasStaffFeedbackPermission || $hasPerformancePermission)
                            <li class="dropdown-nav-item">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#management" aria-expanded="false">
                                    <i class="fa fa-cogs"></i> Management <i class="fa fa-chevron-down float-right"></i>
                                </a>
                                <ul id="management" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                    @if($hasExaminationPermission)
                                        <li><a href="{{ route('manageExamination') }}" class="nav-link"><i class="fa fa-pencil-square-o"></i> Examinations</a></li>
                                        @php
                                            $staffID = Session::get('staffID');
                                            $staffRoleIds = DB::table('role_user')->where('staff_id', $staffID)->pluck('role_id')->toArray();
                                            $pendingPaperApprovals = \App\Models\PaperApprovalLog::whereIn('role_id', $staffRoleIds)
                                                ->where('status', 'pending')
                                                ->count();
                                        @endphp
                                        <li>
                                            <a href="{{ route('admin.exam_paper_approval') }}" class="nav-link">
                                                <i class="fa fa-check-circle"></i> Exam Paper Approval
                                                @if($pendingPaperApprovals > 0)
                                                    <span class="badge badge-danger ml-1" style="font-size: 10px; border-radius: 50%;">{{ $pendingPaperApprovals }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                    @if($hasSubjectPermission)
                                        <li><a href="{{ route('manageSubjects') }}" class="nav-link"><i class="fa fa-bookmark"></i> Subjects</a></li>
                                    @endif
                                    @if($hasClassesPermission)
                                        <li><a href="{{ route('manageClasses') }}" class="nav-link"><i class="fa fa-users"></i> Classes</a></li>
                                    @endif
                                    @if($hasResultPermission)
                                        <li><a href="{{ route('manageResults') }}" class="nav-link"><i class="fa fa-trophy"></i> Results</a></li>
                                    @endif
                                    @if($hasAttendancePermission)
                                        <li><a href="{{ route('manageAttendance') }}" class="nav-link"><i class="fa fa-check-square-o"></i> Attendance</a></li>
                                    @endif
                                    @if($hasStudentPermission)
                                        <li><a href="{{ route('manage_student') }}" class="nav-link"><i class="fa fa-user"></i> Students</a></li>
                                    @endif
                                    @if($hasParentPermission)
                                        <li><a href="{{ route('manage_parents') }}" class="nav-link"><i class="fa fa-users"></i> Parents</a></li>
                                    @endif
                                    @if($hasTimetablePermission)
                                        <li><a href="{{ route('timeTable') }}" class="nav-link"><i class="fa fa-clock-o"></i> Timetable</a></li>
                                    @endif
                                    @if($hasFeesPermission)
                                        <li><a href="{{ route('manage_fees') }}" class="nav-link"><i class="fa fa-money"></i> Fees</a></li>
                                    @endif
                                    @if($hasAccommodationPermission)
                                        <li><a href="{{ route('manage_accomodation') }}" class="nav-link"><i class="fa fa-bed"></i> Accommodation</a></li>
                                    @endif
                                    @if($hasLibraryPermission)
                                        <li><a href="{{ route('manage_library') }}" class="nav-link"><i class="fa fa-book"></i> Library</a></li>
                                    @endif
                                    @if($hasCalendarPermission)
                                        <li><a href="{{ route('admin.calendar') }}" class="nav-link"><i class="fa fa-calendar"></i> Calendar</a></li>
                                    @endif
                                    @if($hasFingerprintPermission)
                                        <li><a href="{{ route('fingerprint_device_settings') }}" class="nav-link"><i class="fa fa-id-card"></i> Fingerprint</a></li>
                                    @endif
                                    @if($hasTaskPermission)
                                        <li><a href="{{ route('taskManagement') }}" class="nav-link"><i class="fa fa-tasks"></i> Tasks</a></li>
                                    @endif
                                    @if($hasSmsPermission)
                                        <li><a href="{{ route('sms_notification') }}" class="nav-link"><i class="fa fa-envelope"></i> SMS Information</a></li>
                                    @endif
                                    @if($hasTeacherPermission)
                                        <li><a href="{{ route('manageTeachers') }}" class="nav-link"><i class="fa fa-user-secret"></i> Teachers Management</a></li>
                                    @endif
                                    @if($hasStaffPermission)
                                        <li><a href="{{ route('manageTeachers') }}#section-staff" class="nav-link"><i class="fa fa-users"></i> Staff List</a></li>
                                        <li><a href="{{ route('manageTeachers') }}#section-add-staff" class="nav-link"><i class="fa fa-user-plus"></i> Add New Staff</a></li>
                                        <li><a href="{{ route('manageTeachers') }}#section-assign-position" class="nav-link"><i class="fa fa-id-badge"></i> Assign Position to Staff</a></li>
                                        <li><a href="{{ route('manageTeachers') }}#section-view-positions" class="nav-link"><i class="fa fa-eye"></i> View Staff Positions</a></li>
                                        <li><a href="{{ route('manageTeachers') }}#section-manage-positions" class="nav-link"><i class="fa fa-shield"></i> Manage Positions and Permission</a></li>
                                    @endif
                                    @if($hasRevenuePermission)
                                        <li><a href="{{ route('manage_revenue') }}" class="nav-link"><i class="fa fa-line-chart"></i> Revenue</a></li>
                                    @endif
                                    @if($hasExpensesPermission)
                                        <li><a href="{{ route('manage_expenses') }}" class="nav-link"><i class="fa fa-credit-card"></i> Expenses</a></li>
                                    @endif
                                     @if($hasResourcesPermission)
                                         <li><a href="{{ route('manage_incoming_resources') }}" class="nav-link"><i class="fa fa-cubes"></i> Resources</a></li>
                                     @endif
                                     @if($hasAccountantPermission)
                                         <li><a href="{{ route('accountant.expenses.index') }}" class="nav-link"><i class="fa fa-calculator"></i> Accountant Module</a></li>
                                     @endif
                                     @if($hasGoalPermission)
                                         <li><a href="{{ route('admin.goals.index') }}" class="nav-link"><i class="fa fa-bullseye"></i> Goal Management</a></li>
                                     @endif
                                     @if($hasHRPermission)
                                         <li><a href="{{ route('admin.hr.permission') }}" class="nav-link"><i class="fa fa-briefcase"></i> HR Operations</a></li>
                                     @endif
                                     @if($hasSchoolPermission)
                                         <li><a href="{{ route('school') }}" class="nav-link"><i class="fa fa-building"></i> School Management</a></li>
                                     @endif
                                     @if($hasSponsorPermission)
                                         <li><a href="{{ route('manage_sponsors') }}" class="nav-link"><i class="fa fa-handshake-o"></i> Sponsors Management</a></li>
                                     @endif
                                     @if($hasDepartmentPermission)
                                         <li><a href="{{ route('sgpm.departments.index') }}" class="nav-link"><i class="fa fa-sitemap"></i> Departments Management</a></li>
                                     @endif
                                     @if($hasStudentIDCardPermission)
                                         <li><a href="{{ route('manage_student') }}" class="nav-link"><i class="fa fa-id-card"></i> Student ID Card</a></li>
                                     @endif
                                     @if($hasTeacherDutyPermission)
                                         <li><a href="{{ route('admin.teacher_duties') }}" class="nav-link"><i class="fa fa-book"></i> Teacher Duty</a></li>
                                     @endif
                                     @if($hasFeedbackPermission)
                                         <li><a href="{{ route('admin.suggestions') }}" class="nav-link"><i class="fa fa-comments"></i> Feedback Management</a></li>
                                     @endif
                                     @if($hasStaffFeedbackPermission)
                                         <li><a href="{{ route('admin.staff.suggestions') }}" class="nav-link"><i class="fa fa-comments-o"></i> Staff Feedback</a></li>
                                     @endif
                                     @if($hasPerformancePermission)
                                         <li><a href="{{ route('admin.performance') }}" class="nav-link"><i class="fa fa-line-chart"></i> Performance Management</a></li>
                                     @endif
                                     @if($hasSubjectAnalysisPermission)
                                        <li><a href="{{ route('admin.subject_analysis') }}" class="nav-link"><i class="fa fa-line-chart"></i> Subject Analysis</a></li>
                                    @endif
                                    @if($hasPrintingUnitPermission)
                                        <li><a href="{{ route('admin.printing_unit') }}" class="nav-link"><i class="fa fa-print"></i> Printing Unit</a></li>
                                    @endif
                                    @if($hasWatchmanPermission)
                                        <li><a href="{{ route('manage_watchman') }}" class="nav-link"><i class="fa fa-shield"></i> Watchman</a></li>
                                    @endif
                                    @if($hasSchoolVisitorsPermission)
                                        <li><a href="{{ route('admin.school_visitors') }}" class="nav-link"><i class="fa fa-id-badge"></i> School Visitors</a></li>
                                    @endif
                                    @if($hasSchemeOfWorkPermission)
                                        <li><a href="{{ route('admin.schemeOfWork') }}" class="nav-link"><i class="fa fa-book"></i> Scheme of Work</a></li>
                                    @endif
                                    @if($hasLessonPlansPermission)
                                        <li><a href="{{ route('admin.lessonPlans') }}" class="nav-link"><i class="fa fa-file-text"></i> Lesson Plans</a></li>
                                    @endif
                                    @if($hasAcademicYearsPermission)
                                        <li><a href="{{ route('admin.academicYears') }}" class="nav-link"><i class="fa fa-calendar-check-o"></i> Academic Years</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<div id="right-panel" class="right-panel">
    <header id="header" class="header">
        <div class="header-menu">
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                <div class="header-left">
                    <button class="search-trigger"><i class="fa fa-search"></i></button>
                    <div class="form-inline">
                        <form class="search-form">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                            <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                        </form>
                    </div>

                    <div class="dropdown for-notification">
                        @php
                            $notifications = $teacherNotifications ?? collect();
                            $notificationCount = $notifications->count();
                        @endphp
                        <button class="btn btn-secondary dropdown-toggle position-relative" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: transparent; border: none;">
                            <i class="fa fa-bell-o" style="color: #666; font-size: 1.2rem;"></i>
                            @if($notificationCount > 0)
                                <span class="count bg-danger" style="position: absolute; top: -5px; right: -5px; border-radius: 50%; padding: 2px 5px; font-size: 0.7rem; color: white;">{{ $notificationCount }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu" aria-labelledby="notification" style="max-width: 350px; min-width: 250px; padding: 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 10px; border: none;">
                            @if($notificationCount > 0)
                                <p class="px-3 py-2 mb-0" style="font-weight: bold; border-bottom: 1px solid #f0f0f0;">Notifications</p>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @foreach($notifications as $notification)
                                        <a class="dropdown-item media" href="{{ $notification['link'] ?? '#' }}" style="padding: 10px 15px; border-bottom: 1px solid #f8f8f8;">
                                            <i class="fa {{ $notification['icon'] ?? 'fa-bell' }}" style="margin-right: 10px; color: #940000;"></i>
                                            <div class="media-body">
                                                <p style="margin: 0; font-size: 0.85rem; color: #333; white-space: normal;">{{ $notification['message'] }}</p>
                                                <small style="color: #888;">{{ \Carbon\Carbon::parse($notification['date'])->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="px-3 py-3 mb-0 text-center text-muted">No new notifications</p>
                            @endif
                        </div>
                    </div>
                    @include('includes.sgpm_notifications')
                    @include('includes.goal_notifications')
                </div>
            </div>

            <div class="col-sm-5">

                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-avatar rounded-circle" src="{{ $imgPath }}" alt="User Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                    </a>

                    <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="{{ route('staff.profile') }}"><i class="fa fa-user"></i> My Profile</a>
                        <a class="nav-link" href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @php
        $systemAlerts = collect();
        try {
            $navSchoolID = \Illuminate\Support\Facades\Session::get('schoolID');
            $navStaffID = \Illuminate\Support\Facades\Session::get('staffID');
            $navProfessionId = null;

            if ($navStaffID) {
                $navStaff = \App\Models\OtherStaff::find($navStaffID);
                if ($navStaff && $navStaff->profession_id) {
                    $navProfessionId = (int) $navStaff->profession_id;
                }
            }

            if ($navSchoolID) {
                $baseAlerts = \App\Models\SystemAlert::where('schoolID', $navSchoolID)
                    ->where('target_user_type', 'Staff')
                    ->where('is_active', 1)
                    ->orderBy('id', 'desc')
                    ->get();

                $systemAlerts = $baseAlerts->filter(function ($a) use ($navProfessionId) {
                    if ($a->applies_to_all) return true;
                    if (!$navProfessionId) return false;
                    return (int) $a->target_profession_id === (int) $navProfessionId;
                })->values();
            }
        } catch (\Throwable $e) {
            $systemAlerts = collect();
        }

        $alertIcons = [
            'info' => 'fa-info-circle',
            'warning' => 'fa-exclamation-triangle',
            'success' => 'fa-check-circle',
            'danger' => 'fa-times-circle',
        ];
    @endphp

    @if($systemAlerts->count() > 0)
        <div class="px-3 pt-2">
            @foreach($systemAlerts as $a)
                @php
                    $type = $a->alert_type ?: 'info';
                    $icon = $alertIcons[$type] ?? 'fa-info-circle';
                    $bg = $a->bg_color;
                    $tc = $a->text_color;
                    $w = $a->width;
                    $style = '';
                    if ($bg) $style .= 'background-color:' . $bg . ' !important;';
                    if ($tc) $style .= 'color:' . $tc . ' !important;';
                    if (!$bg && !$tc && in_array($type, ['danger', 'success', 'info'], true)) $style .= 'color:#ffffff !important;';
                    if ($a->is_bold) $style .= 'font-weight:700;';
                    if ($a->font_size) $style .= 'font-size:' . $a->font_size . ';';
                    if ($w) $style .= 'width:' . $w . ';';
                @endphp
                <div class="alert alert-{{ $type }}" role="alert" style="margin-bottom: 8px; {!! $style !!}">
                    @if($a->is_marquee)
                        <marquee behavior="scroll" direction="left" scrollamount="6" style="white-space:nowrap; width:100%;">{{ $a->message }}</marquee>
                    @else
                        <i class="fa {{ $icon }}" style="margin-right: 8px;"></i>
                        {{ $a->message }}
                    @endif
                </div>
            @endforeach
        </div>
    @endif

<script>
function initializeMenuDropdowns() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeMenuDropdowns, 100);
        return;
    }

    const menuLinks = document.querySelectorAll('#left-panel .nav-link');

    function removeActiveClass() {
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });
    }

    function resetAllDropdowns() {
        document.querySelectorAll('.dropdown-nav-item .collapse').forEach(collapse => {
            const $collapse = $(collapse);
            if ($collapse.hasClass('show')) {
                $collapse.collapse('hide');
            }
            const toggle = collapse.previousElementSibling;
            if (toggle && toggle.classList.contains('dropdown-toggle')) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('.dropdown-nav-item .collapse').forEach(collapse => {
        if (!$(collapse).data('bs.collapse')) {
            $(collapse).collapse({
                toggle: false
            });
        }
    });

    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.classList.contains('dropdown-toggle')) {
                e.preventDefault();
                e.stopPropagation();

                const targetId = this.getAttribute('data-target');
                const target = document.querySelector(targetId);
                if (!target) return;

                const $target = $(target);
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                document.querySelectorAll('.dropdown-nav-item .collapse').forEach(collapse => {
                    const $collapse = $(collapse);
                    if (collapse.id !== targetId.replace('#', '') && $collapse.hasClass('show')) {
                        $collapse.collapse('hide');
                        const otherToggle = collapse.previousElementSibling;
                        if (otherToggle && otherToggle.classList.contains('dropdown-toggle')) {
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                setTimeout(() => {
                    $target.collapse('toggle');
                    this.setAttribute('aria-expanded', !isExpanded);
                }, 50);

                return false;
            }

            if (this.getAttribute('href') !== '#') {
                removeActiveClass();
                this.classList.add('menu-active');
            }
        });
    });

    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;

    function urlMatches(linkHref, currentUrlValue, currentPathValue) {
        if (!linkHref || linkHref === '#') return false;

        let linkPath = linkHref.split('?')[0].split('#')[0].replace(/\/$/, '');
        let currentPathClean = currentPathValue.split('?')[0].split('#')[0].replace(/\/$/, '');
        let currentUrlClean = currentUrlValue.split('?')[0].split('#')[0].replace(/\/$/, '');

        linkPath = linkPath.toLowerCase();
        currentPathClean = currentPathClean.toLowerCase();
        currentUrlClean = currentUrlClean.toLowerCase();

        if (currentPathClean === linkPath || currentUrlClean === linkPath) {
            return true;
        }

        if (currentPathClean.endsWith(linkPath) || currentUrlClean.endsWith(linkPath)) {
            return true;
        }

        if (linkPath && (currentPathClean.includes(linkPath) || currentUrlClean.includes(linkPath))) {
            return true;
        }

        return false;
    }

    resetAllDropdowns();

    setTimeout(() => {
        let activeLinkFound = false;

        menuLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (urlMatches(linkHref, currentUrl, currentPath)) {
                link.classList.add('menu-active');
                activeLinkFound = true;

                const submenu = link.closest('.submenu');
                if (submenu) {
                    const $submenu = $(submenu);
                    const parentLi = submenu.closest('li.dropdown-nav-item');
                    if (parentLi) {
                        const dropdownToggle = parentLi.querySelector('.dropdown-toggle');
                        if (dropdownToggle) {
                            $submenu.collapse('show');
                            dropdownToggle.setAttribute('aria-expanded', 'true');
                            dropdownToggle.classList.add('menu-active');
                        }
                    }
                }
            }
        });

        if (activeLinkFound) {
            document.querySelectorAll('#left-panel .nav-link.menu-active').forEach(activeLink => {
                const submenu = activeLink.closest('.submenu');
                if (submenu) {
                    const parentLi = submenu.closest('li.dropdown-nav-item');
                    if (parentLi) {
                        const dropdownToggle = parentLi.querySelector('.dropdown-toggle');
                        if (dropdownToggle && !dropdownToggle.classList.contains('menu-active')) {
                            dropdownToggle.classList.add('menu-active');
                        }
                    }
                }
            });
        }
    }, 300);

    $('.dropdown-nav-item .collapse').off('show.bs.collapse hide.bs.collapse').on('show.bs.collapse', function() {
        const toggle = $(this).prev('.dropdown-toggle');
        if (toggle.length) {
            toggle.attr('aria-expanded', 'true');
        }
    }).on('hide.bs.collapse', function() {
        const toggle = $(this).prev('.dropdown-toggle');
        if (toggle.length) {
            toggle.attr('aria-expanded', 'false');
        }
    });
}

document.addEventListener('DOMContentLoaded', initializeMenuDropdowns);
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        initializeMenuDropdowns();
    }
});
setTimeout(initializeMenuDropdowns, 500);
</script>

<script>
(function() {
    const IDLE_MS = 270 * 1000;
    const WARN_SECONDS = 30;
    const LOGOUT_URL = '{{ route('logout') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    let idleTimer = null;
    let countdownTimer = null;
    let remaining = WARN_SECONDS;
    let overlay = null;

    function ensureOverlay() {
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'idle-logout-overlay';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;z-index:99999;';
        overlay.innerHTML =
            '<div style="width:min(520px,92vw);background:#fff;border-radius:12px;padding:18px 18px 14px;box-shadow:0 20px 60px rgba(0,0,0,.25);font-family:inherit;">'
            + '<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">'
            + '<div style="font-weight:800;color:#940000;">Security Warning</div>'
            + '<button type="button" id="idleStayBtn" style="border:1px solid rgba(148,0,0,.3);background:#fff;color:#940000;border-radius:8px;padding:6px 10px;cursor:pointer;">Stay Logged In</button>'
            + '</div>'
            + '<div style="margin-top:10px;color:#333;line-height:1.4;">System is idle. You will be logged out after <b id="idleCountdown">30</b> seconds.</div>'
            + '<div style="margin-top:10px;color:#666;font-size:.9rem;">Move the mouse, type, or click to continue.</div>'
            + '</div>';
        document.body.appendChild(overlay);
        const stayBtn = overlay.querySelector('#idleStayBtn');
        if (stayBtn) stayBtn.addEventListener('click', resetAll);
        return overlay;
    }

    function showWarning() {
        ensureOverlay();
        remaining = WARN_SECONDS;
        overlay.style.display = 'flex';
        const c = overlay.querySelector('#idleCountdown');
        if (c) c.textContent = String(remaining);
        if (countdownTimer) clearInterval(countdownTimer);
        countdownTimer = setInterval(() => {
            remaining -= 1;
            if (c) c.textContent = String(Math.max(0, remaining));
            if (remaining <= 0) {
                logoutNow();
            }
        }, 1000);
    }

    function hideWarning() {
        if (overlay) overlay.style.display = 'none';
        if (countdownTimer) clearInterval(countdownTimer);
        countdownTimer = null;
    }

    function logoutNow() {
        hideWarning();
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = LOGOUT_URL;
        form.style.display = 'none';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = CSRF_TOKEN;
        form.appendChild(token);

        document.body.appendChild(form);
        form.submit();
    }

    function scheduleIdle() {
        if (idleTimer) clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_MS);
    }

    function resetAll() {
        hideWarning();
        scheduleIdle();
    }

    ['mousemove','mousedown','keydown','scroll','touchstart','click'].forEach(evt => {
        window.addEventListener(evt, resetAll, { passive: true });
    });

    scheduleIdle();
})();
</script>
