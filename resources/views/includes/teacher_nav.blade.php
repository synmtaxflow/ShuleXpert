<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        window.$ = window.jQuery;
    </script>

    <style>
/* Badilisha rangi ya background ya sidebar */
#left-panel,
#left-panel .navbar,
#left-panel .navbar-default,
#left-panel .main-menu,
#left-panel .navbar-nav,
#left-panel ul {
    background-color: #ffffff !important; /* nyeupe */
    color: #2f2f2f !important;
    font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
}

/* Badilisha rangi ya maandishi (links) */
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

/* Rangi ya icon ndani ya links */
#left-panel .nav-link i,
#left-panel a.nav-link i,
#left-panel li a i,
#left-panel .navbar-nav li a i,
#left-panel .fa {
    color: #940000 !important;
}

/* Hover effect ya link */
#left-panel .nav-link:hover,
#left-panel a.nav-link:hover,
#left-panel li a:hover,
#left-panel .navbar-nav li a:hover,
#left-panel .navbar-nav > li > a:hover,
#left-panel li:hover {
    background-color: #f5f5f5 !important; /* kijivu chepesi */
    color: #2f2f2f !important;
}

/* Active link */
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

/* Active link icons - white when active */
#left-panel .nav-link.active i,
#left-panel a.nav-link.active i,
#left-panel li.active > a i,
#left-panel .nav-link.menu-active i,
#left-panel a.nav-link.menu-active i {
    color: #940000 !important;
}

/* Rangi ya jina la "Teacher" na maandishi ya ndani ya sidebar */
#left-panel p,
#left-panel .navbar-brand,
#left-panel .navbar-brand:hover {
    color: #2f2f2f !important;
    font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
}

/* Rangi ya navbar brand */
#left-panel .navbar-header .navbar-brand {
    color: #2f2f2f !important;
    font-weight: 700 !important;
}

/* Rangi ya toggle button */
#left-panel .navbar-toggler,
#left-panel .navbar-toggler i {
    color: #2f2f2f !important;
}

/* Background ya list items */
#left-panel .navbar-nav li {
    background-color: transparent !important;
}

/* Border na dividers */
#left-panel .navbar-nav li {
    border-bottom: 1px solid #f0f0f0 !important;
}

/* Ensure all text in sidebar is #940000 */
#left-panel * {
    color: #2f2f2f !important;
    font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
}

/* Apply Century Gothic to text elements only, not icons */
#left-panel, #left-panel p, #left-panel span, #left-panel a, #left-panel li, #left-panel .navbar-brand {
    font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
}

/* Exclude Font Awesome icons from font-family override */
#left-panel .fa, #left-panel .fa:before, #left-panel i.fa, #left-panel [class*="fa-"]:before, #left-panel [class^="fa-"]:before {
    font-family: 'FontAwesome' !important;
}

/* Exception for icons - keep them #940000 */
#left-panel i,
#left-panel .fa,
#left-panel [class*="fa-"],
#left-panel [class^="fa-"] {
    color: #940000 !important;
    font-family: 'FontAwesome' !important;
}

/* Overflow scroll kwa sidebar links container - with visible scrollbar */
.sidebar-links-container {
    overflow-y: hidden !important;
    overflow-x: hidden !important;
    max-height: calc(100vh - 200px) !important;
    width: 100% !important;
    /* Hide scrollbar until hover */
    scrollbar-width: none !important; /* Firefox */
    -ms-overflow-style: none !important; /* IE and Edge */
}

/* Custom scrollbar styling for WebKit browsers (Chrome, Safari, Opera) */
.sidebar-links-container::-webkit-scrollbar {
    width: 0 !important;
    display: none !important;
}

.sidebar-links-container:hover {
    overflow-y: auto !important;
    scrollbar-width: thin !important; /* Firefox */
    scrollbar-color: #cfcfcf #f0f0f0 !important; /* Firefox */
    -ms-overflow-style: scrollbar !important; /* IE and Edge */
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

/* Dropdown menu items styling */
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

.dropdown-nav-item .submenu li {
    border-bottom: none !important;
}

.dropdown-nav-item .submenu li a {
    padding-left: 15px !important;
    font-size: 0.9rem;
    color: #940000 !important;
}

.dropdown-nav-item .submenu li a:hover {
    background-color: #e9ecef !important;
    padding-left: 20px !important;
}

.dropdown-nav-item .submenu li a i {
    margin-right: 8px;
    font-size: 0.85rem;
}

/* Sidebar profile block */
.sidebar-profile {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 10px 12px;
    background: rgba(148, 0, 0, 0.08);
    border: 1px solid rgba(148, 0, 0, 0.35);
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

/* Ensure sidebar itself can scroll if needed */
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

/* Mobile navbar header */
.navbar-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    padding: 10px 14px;
    min-height: 52px;
    background: #940000;
    border-bottom: 2px solid rgba(255,255,255,0.15);
}

.mobile-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.45rem;
    font-weight: 800;
    color: #ffffff !important;
    font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif !important;
    text-decoration: none !important;
    letter-spacing: 0.5px;
    line-height: 1;
}

.mobile-brand:hover {
    color: #ffffff !important;
    text-decoration: none !important;
}

.mobile-brand i {
    color: #ffffff !important;
    font-size: 1.3rem;
}

/* Force white color inside navbar-header (overrides #left-panel * rule) */
#left-panel .navbar-header .mobile-brand,
#left-panel .navbar .navbar-header .mobile-brand {
    color: #ffffff !important;
}

#left-panel .navbar-header .mobile-brand i,
#left-panel .navbar-header .mobile-brand .fa,
#left-panel .navbar .navbar-header .mobile-brand i {
    color: #ffffff !important;
    font-family: 'FontAwesome' !important;
}

#left-panel .navbar-header .nav-dot {
    background-color: #ffffff !important;
}

.navbar-toggler {
    border: none;
    padding: 6px 4px;
    border-radius: 4px;
    background: transparent;
    color: #ffffff;
    cursor: pointer;
    margin-left: auto;
    box-shadow: none;
    outline: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.navbar-toggler:focus {
    outline: none;
    box-shadow: none;
}

.nav-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background-color: #ffffff;
    display: block;
}

/* Hide mobile brand on desktop (when sidebar is always visible) */
@media (min-width: 768px) {
    .mobile-brand {
        display: none;
    }
    .navbar-header {
        justify-content: center;
        padding: 4px 0;
        background: transparent;
        border-bottom: none;
        min-height: unset;
    }
    .navbar-toggler {
        margin-left: 0;
    }
    .nav-dot {
        background-color: #940000;
    }
}

/* Mobile: remove aside padding so navbar-header is truly full width */
@media (max-width: 767px) {
    aside.left-panel,
    aside#left-panel {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #main-menu {
        padding-left: 25px;
        padding-right: 25px;
    }
}

    }
}
</style>
</head>
<body>
    <!-- Left Panel -->

  <!-- Left Panel -->
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header">
            <a class="mobile-brand" href="{{ route('teachersDashboard') }}">
                <i class="fa fa-graduation-cap"></i>
                ShuleXpert
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="nav-dot"></span>
                <span class="nav-dot"></span>
                <span class="nav-dot"></span>
            </button>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!-- Profile -->
                <li class="text-center mt-3 mb-2">
                    @php
                        // Get teacher profile image dynamically
                        $imgPath = isset($teacher) && $teacher && $teacher->image
                            ? asset('userImages/' . $teacher->image)
                            : (isset($teacher) && $teacher && $teacher->gender == 'Female'
                                ? asset('images/female.png')
                                : asset('images/male.png'));
                    @endphp
                    @php
                        // Get teacher's role name if exists
                        $teacherRoleName = 'Teacher'; // Default
                        if (isset($role) && $role->count() > 0) {
                            $firstRole = $role->first();
                            if ($firstRole && isset($firstRole->role_name) && !empty($firstRole->role_name)) {
                                $teacherRoleName = $firstRole->role_name;
                            }
                        }
                    @endphp
                    <div class="sidebar-profile">
                        <img src="{{ $imgPath }}" alt="Teacher" class="profile-image">
                        <div class="profile-meta text-left">
                            <div class="profile-role">User Type</div>
                            <div class="profile-name">{{ $teacherRoleName }}</div>
                        </div>
                    </div>
                </li>

                <!-- Sidebar Links -->
                <li class="sidebar-links-container">
                    <ul style="list-style: none; padding: 0; margin: 0; font-family: 'Century Gothic', CenturyGothic, AppleGothic, sans-serif;">
                        @php
                            $teacherID = Session::get('teacherID');
                            $schoolID = Session::get('schoolID');
                            $isOnDuty = false;

                            if ($teacherID && $schoolID) {
                                $today = \Carbon\Carbon::today();
                                $isOnDuty = \App\Models\TeacherDuty::where('schoolID', $schoolID)
                                    ->where('teacherID', $teacherID)
                                    ->whereDate('start_date', '<=', $today)
                                    ->whereDate('end_date', '>=', $today)
                                    ->exists();
                            }
                        @endphp

                        @if($isOnDuty)
                        <li class="p-2 text-center">
                            <div class="alert alert-warning mb-0 p-1" style="font-size: 0.8rem; font-weight: bold; border: 1px solid #ffc107; color: #856404;">
                                <i class="fa fa-exclamation-circle"></i> You are on duty this week!
                            </div>
                        </li>
                        @endif

                        <li><a href="{{ route('teachersDashboard') }}" class="nav-link"><i class="fa fa-building"></i> Dashboard</a></li>
                        <li><a href="{{ route('teacher.duty_book') }}" class="nav-link"><i class="fa fa-book"></i> Duty Book</a></li>

                        <!-- Teaching Activities -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#teachingActivities" aria-expanded="false">
                                <i class="fa fa-book"></i> Teaching Activities <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="teachingActivities" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('teacher.mySessions') }}" class="nav-link"><i class="fa fa-clock-o"></i> My Sessions</a></li>
                                <li><a href="{{ route('teacher.myTasks') }}" class="nav-link"><i class="fa fa-tasks"></i> My Tasks</a></li>
                                <li><a href="{{ route('teacherSubjects') }}" class="nav-link"><i class="fa fa-book"></i> My Subjects</a></li>
                                <li><a href="{{ route('teacher.schemeOfWork') }}" class="nav-link"><i class="fa fa-file-text-o"></i> Scheme of Work</a></li>
                                <li><a href="{{ route('teacher.lessonPlans') }}" class="nav-link"><i class="fa fa-book"></i> Lesson Plans</a></li>
                                <li><a href="{{ route('teacher.calendar') }}" class="nav-link"><i class="fa fa-calendar"></i> Calendar</a></li>
                            </ul>
                        </li>

                        <!-- Exams -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#exams" aria-expanded="false">
                                <i class="fa fa-graduation-cap"></i> Exams <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="exams" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('supervise_exams') }}" class="nav-link"><i class="fa fa-graduation-cap"></i> My Supervise Exams</a></li>
                                <li><a href="{{ route('exam_paper') }}" class="nav-link"><i class="fa fa-file-text"></i> My Exam Papers</a></li>
                                @php
                                    $teacherID = Session::get('teacherID');
                                    $teacherRoleIds = isset($role) ? $role->pluck('id')->toArray() : [];

                                    // Regular roles count
                                    $roleCount = 0;
                                    if (!empty($teacherRoleIds)) {
                                        $roleCount = \App\Models\PaperApprovalLog::whereIn('role_id', $teacherRoleIds)
                                            ->where('status', 'pending')
                                            ->count();
                                    }

                                    // Special roles count
                                    $classTeacherSubclassIds = \App\Models\Subclass::where('teacherID', $teacherID)->pluck('subclassID')->toArray();
                                    $coordinatorClassIds = \App\Models\ClassModel::where('teacherID', $teacherID)->pluck('classID')->toArray();

                                    $specialCount = \App\Models\PaperApprovalLog::where('status', 'pending')
                                        ->whereNotNull('special_role_type')
                                        ->get()
                                        ->filter(function($log) use ($classTeacherSubclassIds, $coordinatorClassIds) {
                                            if ($log->special_role_type === 'class_teacher') {
                                                return $log->examPaper && in_array($log->examPaper->classSubject->subclassID ?? 0, $classTeacherSubclassIds);
                                            } elseif ($log->special_role_type === 'coordinator') {
                                                return $log->examPaper && in_array($log->examPaper->classSubject->classID ?? 0, $coordinatorClassIds);
                                            }
                                            return false;
                                        })->count();

                                    $pendingPaperApprovals = $roleCount + $specialCount;
                                @endphp
                                <li>
                                    <a href="{{ route('admin.exam_paper_approval') }}" class="nav-link">
                                        <i class="fa fa-check-circle"></i> Exam Paper Approval
                                        @if($pendingPaperApprovals > 0)
                                            <span class="badge badge-danger ml-1" style="font-size: 10px; border-radius: 50%;">{{ $pendingPaperApprovals }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- My Class & Schedule -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#classSchedule" aria-expanded="false">
                                <i class="fa fa-users"></i> My Class & Schedule <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="classSchedule" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                @if (isset($hasAssignedClass) && $hasAssignedClass)
                                <li><a href="{{ route('AdmitedClasses') }}" class="nav-link"><i class="fa fa-users"></i> My Class</a></li>
                                @endif
                                <li><a href="#" class="nav-link"><i class="fa fa-table"></i> My TimeTable</a></li>
                            </ul>
                        </li>

                        <!-- Personal -->
                        <!-- <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#personal" aria-expanded="false">
                                <i class="fa fa-user"></i> Personal <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="personal" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="#" class="nav-link"><i class="fa fa-calculator"></i> My Salary</a></li>
                            </ul>
                        </li> -->

                        <!-- HR Operations -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#hrOperations" aria-expanded="false">
                                <i class="fa fa-briefcase"></i> HR Operations <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="hrOperations" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li>
                                    <a href="{{ route('teacher.permissions') }}" class="nav-link">
                                        <i class="fa fa-check-square-o"></i> Permission
                                    </a>
                                </li>
                                <li>
                                    <!-- <a href="#" class="nav-link">
                                        <i class="fa fa-calendar"></i> Leave
                                    </a> -->
                                </li>
                                <li>
                                    <!-- <a href="#" class="nav-link">
                                        <i class="fa fa-money"></i> Payroll
                                    </a> -->
                                </li>
                            </ul>
                        </li>

                        @if($isHOD)
                        <!-- Goal Management - HOD View -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#departmentGoals" aria-expanded="false">
                                <i class="fa fa-users"></i> Department Goals <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="departmentGoals" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('hod.goals.assigned') }}" class="nav-link"><i class="fa fa-tasks"></i> Team Assignments</a></li>
                            </ul>
                        </li>
                        @endif

                        <!-- Member Goals Section -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#memberGoals" aria-expanded="false">
                                <i class="fa fa-thumb-tack"></i> Member Portal <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="memberGoals" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('member.goals.myTasks', ['type' => 'direct']) }}" class="nav-link"><i class="fa fa-star"></i> Direct Goals/Tasks</a></li>
                                <li><a href="{{ route('member.goals.myTasks') }}" class="nav-link"><i class="fa fa-list-ul"></i> All Assigned Tasks</a></li>
                            </ul>
                        </li>

                        <!-- My Performance Tasks (For both HOD and normal Teachers) -->

                        <li class="dropdown-nav-item">
                            <!-- <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#myPerformance" aria-expanded="false">
                                <i class="fa fa-star"></i> My Tasks & KPIs <i class="fa fa-chevron-down float-right"></i>
                            </a> -->
                            <ul id="myPerformance" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                <li><a href="{{ route('sgpm.tasks.index') }}" class="nav-link"><i class="fa fa-tasks"></i> My Tasks</a></li>
                                <li><a href="{{ route('sgpm.performance.staff') }}" class="nav-link"><i class="fa fa-briefcase"></i> My Scorecard</a></li>
                            </ul>
                        </li>

                        <!-- Suggestions & Incidents -->
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#teacherFeedback" aria-expanded="false">
                                <i class="fa fa-commenting"></i> Suggestions & Incidents <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="teacherFeedback" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                @php
                                    $teacherID = Session::get('teacherID');
                                    $schoolID = Session::get('schoolID');
                                    $unreadTeacherSuggestions = 0;
                                    $unreadTeacherIncidents = 0;
                                    if ($teacherID && $schoolID) {
                                        $unreadTeacherSuggestions = \App\Models\TeacherFeedback::where('schoolID', $schoolID)
                                            ->where('teacherID', $teacherID)
                                            ->where('type', 'suggestion')
                                            ->where('is_read_by_teacher', false)
                                            ->count();
                                        $unreadTeacherIncidents = \App\Models\TeacherFeedback::where('schoolID', $schoolID)
                                            ->where('teacherID', $teacherID)
                                            ->where('type', 'incident')
                                            ->where('is_read_by_teacher', false)
                                            ->count();
                                    }
                                @endphp
                                <li>
                                    <a href="{{ route('teacher.suggestions') }}" class="nav-link">
                                        <i class="fa fa-lightbulb-o"></i> Suggestions
                                        @if($unreadTeacherSuggestions > 0)
                                            <span class="badge badge-danger ml-1">{{ $unreadTeacherSuggestions }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('teacher.incidents') }}" class="nav-link">
                                        <i class="fa fa-exclamation-triangle"></i> Incidents
                                        @if($unreadTeacherIncidents > 0)
                                            <span class="badge badge-danger ml-1">{{ $unreadTeacherIncidents }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @php
                            // Check permission categories - New format: category_action (e.g. examination_create, examination_update, etc.)
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
                            $hasSubjectAnalysisPermission = false;
                            $hasPrintingUnitPermission = false;
                            $hasWatchmanPermission = false;
                            $hasSchoolVisitorsPermission = false;
                            $hasSchemeOfWorkPermission = false;
                            $hasLessonPlansPermission = false;
                            $hasAcademicYearsPermission = false;
                            $hasSchoolPermission = false;
                            $hasSponsorPermission = false;
                            $hasStudentIdCardPermission = false;
                            $hasHrPermission = false;
                            $hasTeacherDutyPermission = false;
                            $hasFeedbackPermission = false;
                            $hasStaffFeedbackPermission = false;
                            $hasPerformancePermission = false;
                            $hasAccountantPermission = false;
                            $hasGoalPermission = false;
                            $hasDepartmentPermission = false;

                            if (isset($teacherPermissionsByCategory) && $teacherPermissionsByCategory->count() > 0) {
                                // Check examination category
                                if ($teacherPermissionsByCategory->has('examination')) {
                                    $hasExaminationPermission = $teacherPermissionsByCategory->get('examination')->count() > 0;
                                }

                                // Check subject category (note: 'subject' not 'subjects')
                                if ($teacherPermissionsByCategory->has('subject')) {
                                    $hasSubjectPermission = $teacherPermissionsByCategory->get('subject')->count() > 0;
                                }

                                // Check classes category
                                if ($teacherPermissionsByCategory->has('classes')) {
                                    $hasClassesPermission = $teacherPermissionsByCategory->get('classes')->count() > 0;
                                }

                                // Check result category
                                if ($teacherPermissionsByCategory->has('result')) {
                                    $hasResultPermission = $teacherPermissionsByCategory->get('result')->count() > 0;
                                }

                                // Check attendance category
                                if ($teacherPermissionsByCategory->has('attendance')) {
                                    $hasAttendancePermission = $teacherPermissionsByCategory->get('attendance')->count() > 0;
                                }

                                // Check student category
                                if ($teacherPermissionsByCategory->has('student')) {
                                    $hasStudentPermission = $teacherPermissionsByCategory->get('student')->count() > 0;
                                }

                                // Check parent category
                                if ($teacherPermissionsByCategory->has('parent')) {
                                    $hasParentPermission = $teacherPermissionsByCategory->get('parent')->count() > 0;
                                }

                                // Check timetable category
                                if ($teacherPermissionsByCategory->has('timetable')) {
                                    $hasTimetablePermission = $teacherPermissionsByCategory->get('timetable')->count() > 0;
                                }

                                // Check teacher category
                                if ($teacherPermissionsByCategory->has('teacher')) {
                                    $hasTeacherPermission = $teacherPermissionsByCategory->get('teacher')->count() > 0;
                                }

                                // Check fees category
                                if ($teacherPermissionsByCategory->has('fees')) {
                                    $hasFeesPermission = $teacherPermissionsByCategory->get('fees')->count() > 0;
                                }

                                // Check accommodation category
                                if ($teacherPermissionsByCategory->has('accommodation')) {
                                    $hasAccommodationPermission = $teacherPermissionsByCategory->get('accommodation')->count() > 0;
                                }

                                // Check library category
                                if ($teacherPermissionsByCategory->has('library')) {
                                    $hasLibraryPermission = $teacherPermissionsByCategory->get('library')->count() > 0;
                                }

                                // Check calendar category
                                if ($teacherPermissionsByCategory->has('calendar')) {
                                    $hasCalendarPermission = $teacherPermissionsByCategory->get('calendar')->count() > 0;
                                }

                                // Check fingerprint category
                                if ($teacherPermissionsByCategory->has('fingerprint')) {
                                    $hasFingerprintPermission = $teacherPermissionsByCategory->get('fingerprint')->count() > 0;
                                }

                                // Check task category
                                if ($teacherPermissionsByCategory->has('task')) {
                                    $hasTaskPermission = $teacherPermissionsByCategory->get('task')->count() > 0;
                                }

                                // Check sms category
                                if ($teacherPermissionsByCategory->has('sms')) {
                                    $hasSmsPermission = $teacherPermissionsByCategory->get('sms')->count() > 0;
                                }

                                // Check subject analysis category
                                if ($teacherPermissionsByCategory->has('subject_analysis')) {
                                    $hasSubjectAnalysisPermission = $teacherPermissionsByCategory->get('subject_analysis')->count() > 0;
                                }

                                // Check printing unit category
                                if ($teacherPermissionsByCategory->has('printing_unit')) {
                                    $hasPrintingUnitPermission = $teacherPermissionsByCategory->get('printing_unit')->count() > 0;
                                }

                                // Check watchman category
                                if ($teacherPermissionsByCategory->has('watchman')) {
                                    $hasWatchmanPermission = $teacherPermissionsByCategory->get('watchman')->count() > 0;
                                }

                                // Check school visitors category
                                if ($teacherPermissionsByCategory->has('school_visitors')) {
                                    $hasSchoolVisitorsPermission = $teacherPermissionsByCategory->get('school_visitors')->count() > 0;
                                }

                                // Check scheme of work category
                                if ($teacherPermissionsByCategory->has('scheme_of_work')) {
                                    $hasSchemeOfWorkPermission = $teacherPermissionsByCategory->get('scheme_of_work')->count() > 0;
                                }

                                // Check lesson plans category
                                if ($teacherPermissionsByCategory->has('lesson_plans')) {
                                    $hasLessonPlansPermission = $teacherPermissionsByCategory->get('lesson_plans')->count() > 0;
                                }

                                // Check academic years category
                                if ($teacherPermissionsByCategory->has('academic_years')) {
                                    $hasAcademicYearsPermission = $teacherPermissionsByCategory->get('academic_years')->count() > 0;
                                }

                                // Check school category
                                if ($teacherPermissionsByCategory->has('school')) {
                                    $hasSchoolPermission = $teacherPermissionsByCategory->get('school')->count() > 0;
                                }

                                // Check sponsor category
                                if ($teacherPermissionsByCategory->has('sponsor')) {
                                    $hasSponsorPermission = $teacherPermissionsByCategory->get('sponsor')->count() > 0;
                                }

                                // Check student id card category
                                if ($teacherPermissionsByCategory->has('student_id_card')) {
                                    $hasStudentIdCardPermission = $teacherPermissionsByCategory->get('student_id_card')->count() > 0;
                                }

                                // Check hr category
                                if ($teacherPermissionsByCategory->has('hr')) {
                                    $hasHrPermission = $teacherPermissionsByCategory->get('hr')->count() > 0;
                                }

                                // Check teacher duty category
                                if ($teacherPermissionsByCategory->has('teacher_duty')) {
                                    $hasTeacherDutyPermission = $teacherPermissionsByCategory->get('teacher_duty')->count() > 0;
                                }

                                // Check feedback category
                                if ($teacherPermissionsByCategory->has('feedback')) {
                                    $hasFeedbackPermission = $teacherPermissionsByCategory->get('feedback')->count() > 0;
                                }

                                // Check staff feedback category
                                if ($teacherPermissionsByCategory->has('staff_feedback')) {
                                    $hasStaffFeedbackPermission = $teacherPermissionsByCategory->get('staff_feedback')->count() > 0;
                                }

                                // Check performance category
                                if ($teacherPermissionsByCategory->has('performance')) {
                                    $hasPerformancePermission = $teacherPermissionsByCategory->get('performance')->count() > 0;
                                }

                                // Check accountant category
                                if ($teacherPermissionsByCategory->has('accountant')) {
                                    $hasAccountantPermission = $teacherPermissionsByCategory->get('accountant')->count() > 0;
                                }

                                // Check goal category
                                if ($teacherPermissionsByCategory->has('goal')) {
                                    $hasGoalPermission = $teacherPermissionsByCategory->get('goal')->count() > 0;
                                }

                                // Check department category
                                if ($teacherPermissionsByCategory->has('department')) {
                                    $hasDepartmentPermission = $teacherPermissionsByCategory->get('department')->count() > 0;
                                }
                            }
                        @endphp

                        <!-- Management (Permission-based) -->
                        @if($hasExaminationPermission || $hasSubjectPermission || $hasClassesPermission || $hasResultPermission || $hasAttendancePermission || $hasStudentPermission || $hasTimetablePermission || $hasFeesPermission || $hasAccommodationPermission || $hasLibraryPermission || $hasCalendarPermission || $hasFingerprintPermission || $hasTaskPermission || $hasSmsPermission || $hasTeacherPermission || $hasSubjectAnalysisPermission || $hasPrintingUnitPermission || $hasWatchmanPermission || $hasSchoolVisitorsPermission || $hasSchemeOfWorkPermission || $hasLessonPlansPermission || $hasAcademicYearsPermission || $hasSchoolPermission || $hasSponsorPermission || $hasStudentIdCardPermission || $hasHrPermission || $hasTeacherDutyPermission || $hasFeedbackPermission || $hasStaffFeedbackPermission || $hasPerformancePermission || $hasAccountantPermission || $hasGoalPermission || $hasDepartmentPermission)
                        <li class="dropdown-nav-item">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#management" aria-expanded="false">
                                <i class="fa fa-cogs"></i> Management <i class="fa fa-chevron-down float-right"></i>
                            </a>
                            <ul id="management" class="collapse submenu" style="list-style: none; padding-left: 20px; margin: 0;">
                                @if($hasExaminationPermission)
                                    <li><a href="{{ route('manageExamination') }}" class="nav-link"><i class="fa fa-pencil-square-o"></i> Examinations</a></li>
                                @endif

                                @if($hasSubjectPermission)
                                    <li><a href="{{ route('manageSubjects') }}" class="nav-link"><i class="fa fa-bookmark"></i> Subjects</a></li>
                                @endif

                                @if($hasSubjectAnalysisPermission)
                                    <li><a href="{{ route('admin.subject_analysis') }}" class="nav-link"><i class="fa fa-line-chart"></i> Subject Analysis</a></li>
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
                                    <li><a href="{{ route('manageTeachers') }}" class="nav-link"><i class="fa fa-users"></i> Teachers And Staff</a></li>
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

                                @if($hasSchoolPermission)
                                    <li><a href="{{ route('school') }}" class="nav-link"><i class="fa fa-building"></i> School</a></li>
                                @endif

                                @if($hasSponsorPermission)
                                    <li><a href="{{ route('manage_sponsors') }}" class="nav-link"><i class="fa fa-handshake-o"></i> Sponsors</a></li>
                                @endif

                                @if($hasStudentIdCardPermission)
                                    <li><a href="{{ route('admin.student_id_cards') }}" class="nav-link"><i class="fa fa-id-card-o"></i> Student ID Cards</a></li>
                                @endif

                                @if($hasHrPermission)
                                    <li><a href="{{ route('admin.hr.permission') }}" class="nav-link"><i class="fa fa-check-square-o"></i> HR Permissions</a></li>
                                @endif

                                @if($hasTeacherDutyPermission)
                                    <li><a href="{{ route('admin.teacher_duties') }}" class="nav-link"><i class="fa fa-calendar-check-o"></i> Teacher Duties</a></li>
                                    <li><a href="{{ route('admin.teacher_duties.report') }}" class="nav-link"><i class="fa fa-file-text-o"></i> Duty Reports</a></li>
                                @endif

                                @if($hasFeedbackPermission)
                                    <li><a href="{{ route('admin.suggestions') }}" class="nav-link"><i class="fa fa-lightbulb-o"></i> Suggestions</a></li>
                                    <li><a href="{{ route('admin.incidents') }}" class="nav-link"><i class="fa fa-exclamation-triangle"></i> Incidents</a></li>
                                @endif

                                @if($hasStaffFeedbackPermission)
                                    <li><a href="{{ route('admin.staff.suggestions') }}" class="nav-link"><i class="fa fa-lightbulb-o"></i> Staff Suggestions</a></li>
                                    <li><a href="{{ route('admin.staff.incidents') }}" class="nav-link"><i class="fa fa-exclamation-triangle"></i> Staff Incidents</a></li>
                                @endif

                                @if($hasPerformancePermission)
                                    <li><a href="{{ route('admin.performance') }}" class="nav-link"><i class="fa fa-line-chart"></i> Performance</a></li>
                                @endif

                                @if($hasAccountantPermission)
                                    <li><a href="{{ route('accountant.expenses.index') }}" class="nav-link"><i class="fa fa-money"></i> Expenses</a></li>
                                    <li><a href="{{ route('accountant.income.index') }}" class="nav-link"><i class="fa fa-usd"></i> Income</a></li>
                                    <li><a href="{{ route('accountant.budget.index') }}" class="nav-link"><i class="fa fa-pie-chart"></i> Budget</a></li>
                                    <li><a href="{{ route('accountant.reports.index') }}" class="nav-link"><i class="fa fa-line-chart"></i> Financial Reports</a></li>
                                @endif

                                @if($hasGoalPermission)
                                    <li><a href="{{ route('admin.goals.index') }}" class="nav-link"><i class="fa fa-bullseye"></i> Goal Management</a></li>
                                    <li><a href="{{ route('admin.goals.reports') }}" class="nav-link"><i class="fa fa-bar-chart"></i> Goal Reports</a></li>
                                @endif

                                @if($hasDepartmentPermission)
                                    <li><a href="{{ route('sgpm.departments.index') }}" class="nav-link"><i class="fa fa-sitemap"></i> Departments</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif

                    </ul>
                </li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks header-icon-muted"></i></a>
                    <div class="header-left">
                        <div class="form-inline"></div>

                        <div class="dropdown for-notification">
                            @php
                                $notifications = $teacherNotifications ?? collect();
                                $notificationCount = $notifications->count();
                            @endphp
                            <button class="btn btn-secondary dropdown-toggle position-relative" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell-o header-icon-muted"></i>
                                @if($notificationCount > 0)
                                    <span class="count bg-danger notification-count">{{ $notificationCount }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu" aria-labelledby="notification" style="max-width: 400px; min-width: 300px;">
                                @if($notificationCount > 0)
                                    <p class="red px-3 py-2 mb-0" style="font-weight: bold; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                        You have {{ $notificationCount }} Notification{{ $notificationCount > 1 ? 's' : '' }}
                                    </p>
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        @foreach($notifications as $notification)
                                            @php
                                                $bgClass = 'bg-flat-color-1';
                                                if(isset($notification['color'])) {
                                                    if($notification['color'] === 'danger') {
                                                        $bgClass = 'bg-flat-color-5';
                                                    } elseif($notification['color'] === 'warning') {
                                                        $bgClass = 'bg-flat-color-4';
                                                    } elseif($notification['color'] === 'info') {
                                                        $bgClass = 'bg-flat-color-1';
                                                    } elseif($notification['color'] === 'success') {
                                                        $bgClass = 'bg-flat-color-2';
                                                    }
                                                }
                                            @endphp
                                            <a class="dropdown-item media {{ $bgClass }}" href="{{ $notification['link'] ?? '#' }}" style="padding: 12px 15px; border-bottom: 1px solid rgba(0,0,0,0.1); transition: background 0.2s;">
                                                <i class="fa {{ $notification['icon'] ?? 'fa-bell' }}" style="margin-right: 12px; font-size: 1.2rem; color: #940000;"></i>
                                                <div class="media-body" style="flex: 1;">
                                                    <strong style="display: block; margin-bottom: 4px; color: #333; font-size: 0.9rem;">{{ $notification['title'] ?? 'Notification' }}</strong>
                                                    <p style="margin: 0; font-size: 0.85rem; color: #666; line-height: 1.4;">{{ $notification['message'] ?? '' }}</p>
                                                    @if(isset($notification['date']))
                                                        <small style="font-size: 0.75rem; opacity: 0.7; color: #888; margin-top: 4px; display: block;">
                                                            <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($notification['date'])->diffForHumans() }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="px-3 py-4 mb-0 text-muted" style="text-align: center;">
                                        <i class="fa fa-bell-slash"></i> No notifications
                                    </p>
                                @endif
                            </div>
                        </div>

                        @include('includes.sgpm_notifications')
                        @include('includes.goal_notifications')

                        <div class="dropdown for-message">
                            <button class="btn btn-secondary dropdown-toggle position-relative" type="button"
                                id="message"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- <i class="ti-email header-icon-muted"></i> -->
                                <!-- <span class="count bg-primary">9</span> -->
                            </button>
                            <div class="dropdown-menu" aria-labelledby="message">
                                <p class="red">You have 4 Mails</p>
                                <a class="dropdown-item media bg-flat-color-1" href="#">
                                <span class="photo media-left"><img alt="avatar" src="{{ asset('images/avatar/1.jpg') }}"></span>
                                <span class="message media-body">
                                    <span class="name float-left">Jonathan Smith</span>
                                    <span class="time float-right">Just now</span>
                                        <p>Hello, this is an example msg</p>
                                </span>
                            </a>
                                <a class="dropdown-item media bg-flat-color-4" href="#">
                                <span class="photo media-left"><img alt="avatar" src="{{ asset('images/avatar/2.jpg') }}"></span>
                                <span class="message media-body">
                                    <span class="name float-left">Jack Sanders</span>
                                    <span class="time float-right">5 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                </span>
                            </a>
                                <a class="dropdown-item media bg-flat-color-5" href="#">
                                <span class="photo media-left"><img alt="avatar" src="{{ asset('images/avatar/3.jpg') }}"></span>
                                <span class="message media-body">
                                    <span class="name float-left">Cheryl Wheeler</span>
                                    <span class="time float-right">10 minutes ago</span>
                                        <p>Hello, this is an example msg</p>
                                </span>
                            </a>
                                <a class="dropdown-item media bg-flat-color-3" href="#">
                                <span class="photo media-left"><img alt="avatar" src="{{ asset('images/avatar/4.jpg') }}"></span>
                                <span class="message media-body">
                                    <span class="name float-left">Rachel Santos</span>
                                    <span class="time float-right">15 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                </span>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @php
                                $headerImgPath = isset($teacher) && $teacher && $teacher->image
                                    ? asset('userImages/' . $teacher->image)
                                    : (isset($teacher) && $teacher && $teacher->gender == 'Female'
                                        ? asset('images/female.png')
                                        : asset('images/male.png'));
                            @endphp
                            <img class="user-avatar rounded-circle" src="{{ $headerImgPath }}" alt="User Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                        </a>

                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ route('teacher.profile') }}">
                                <i class="fa fa-user"></i> View Profile
                            </a>
                            <a class="nav-link" href="{{ route('teacher.profile') }}#change-password">
                                <i class="fa fa-lock"></i> Change Password
                            </a>
                            <a class="nav-link" href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>

                    <div class="language-select dropdown" id="language-select">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">
                        <span class="flag-icon flag-icon-tz"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="language">
                            <div class="dropdown-item">
                                <span class="flag-icon flag-icon-fr"></span>
                            </div>
                            <div class="dropdown-item">
                                <i class="flag-icon flag-icon-es"></i>
                            </div>
                            <div class="dropdown-item">
                                <i class="flag-icon flag-icon-us"></i>
                            </div>
                            <div class="dropdown-item">
                                <i class="flag-icon flag-icon-it"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

        @php
            $systemAlerts = collect();
            try {
                $navSchoolID = \Illuminate\Support\Facades\Session::get('schoolID');
                $navTeacherID = \Illuminate\Support\Facades\Session::get('teacherID');
                $navRoleId = null;

                if (isset($role) && $role && $role->count() > 0) {
                    $firstRole = $role->first();
                    if ($firstRole && isset($firstRole->id)) {
                        $navRoleId = $firstRole->id;
                    }
                }

                if (!$navRoleId && $navTeacherID && $navSchoolID) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('role_user', 'teacher_id')) {
                        $navRoleId = (int) \Illuminate\Support\Facades\DB::table('role_user')
                            ->where('teacher_id', $navTeacherID)
                            ->value('role_id');
                    }
                }

                if ($navSchoolID) {
                    $baseAlerts = \App\Models\SystemAlert::where('schoolID', $navSchoolID)
                        ->where('target_user_type', 'Teacher')
                        ->where('is_active', 1)
                        ->orderBy('id', 'desc')
                        ->get();

                    $systemAlerts = $baseAlerts->filter(function ($a) use ($navRoleId) {
                        if ($a->applies_to_all) return true;
                        if (!$navRoleId) return false;
                        return (int) $a->target_role_id === (int) $navRoleId;
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
// Function to initialize menu and dropdowns
function initializeMenuDropdowns() {
    // Use jQuery explicitly to avoid conflicts
    const $ = jQuery;

    // Get all sidebar menu links
    const menuLinks = document.querySelectorAll('#left-panel .nav-link');

    // Remove active class from all links
    function removeActiveClass() {
        menuLinks.forEach(link => {
            link.classList.remove('menu-active');
        });
    }

    // Reset all dropdowns to closed state first
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

    // Initialize all collapse elements
    document.querySelectorAll('.dropdown-nav-item .collapse').forEach(collapse => {
        // Initialize collapse if not already initialized
        if (!$(collapse).data('bs.collapse')) {
            $(collapse).collapse({
                toggle: false
            });
        }
    });

    // Add click event listener to each link
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Handle dropdown toggle
            if (this.classList.contains('dropdown-toggle')) {
                e.preventDefault();
                e.stopPropagation();

                const targetId = this.getAttribute('data-target');
                const target = document.querySelector(targetId);

                if (!target) return;

                const $target = $(target);
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                // Close all other dropdowns first
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

                // Toggle current dropdown after a small delay to ensure others are closed
                setTimeout(() => {
                    $target.collapse('toggle');
                    this.setAttribute('aria-expanded', !isExpanded);
                }, 50);

                return false;
            }

            // Don't prevent default if it's not a hash link
            if (this.getAttribute('href') !== '#') {
                // Remove active class from all links
                removeActiveClass();
                // Add active class to clicked link
                this.classList.add('menu-active');
            }
        });
    });

    // Set active based on current URL on page load
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;

    // Function to check if URL matches
    function urlMatches(linkHref, currentUrl, currentPath) {
        if (!linkHref || linkHref === '#') return false;

        // Remove query strings and fragments for comparison
        let linkPath = linkHref.split('?')[0].split('#')[0].replace(/\/$/, '');
        let currentPathClean = currentPath.split('?')[0].split('#')[0].replace(/\/$/, '');
        let currentUrlClean = currentUrl.split('?')[0].split('#')[0].replace(/\/$/, '');

        // Normalize paths
        linkPath = linkPath.toLowerCase();
        currentPathClean = currentPathClean.toLowerCase();
        currentUrlClean = currentUrlClean.toLowerCase();

        // Check exact match
        if (currentPathClean === linkPath || currentUrlClean === linkPath) {
            return true;
        }

        // Check if current URL/path ends with link path
        if (currentPathClean.endsWith(linkPath) || currentUrlClean.endsWith(linkPath)) {
            return true;
        }

        // Check if current URL/path contains link path
        if (linkPath && (currentPathClean.includes(linkPath) || currentUrlClean.includes(linkPath))) {
            return true;
        }

        return false;
    }

    // First reset all dropdowns
    resetAllDropdowns();

    // Then set active link and expand parent dropdown if needed
    setTimeout(() => {
        let activeLinkFound = false;

        menuLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            if (urlMatches(linkHref, currentUrl, currentPath)) {
                link.classList.add('menu-active');
                activeLinkFound = true;

                // If link is in a submenu, expand the parent dropdown and make it active
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

        // Ensure all parent dropdowns of active links are open and highlighted
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

    // Initialize Bootstrap collapse events for dropdowns
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

function loadScriptOnce(src, onLoad) {
    if (document.querySelector('script[src="' + src + '"]')) {
        if (typeof onLoad === 'function') {
            onLoad();
        }
        return;
    }
    var script = document.createElement('script');
    script.src = src;
    script.onload = function() {
        if (typeof onLoad === 'function') {
            onLoad();
        }
    };
    document.head.appendChild(script);
}

function ensureJqueryAndBootstrap(callback) {
    if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.collapse === 'function') {
        callback();
        return;
    }

    if (!window.jQuery) {
        loadScriptOnce('https://code.jquery.com/jquery-3.6.0.min.js', function() {
            if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.collapse === 'function') {
                callback();
            } else {
                loadScriptOnce('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', callback);
            }
        });
        return;
    }

    loadScriptOnce('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', callback);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    ensureJqueryAndBootstrap(initializeMenuDropdowns);
});

// Also re-initialize when page is shown (for back/forward navigation)
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        ensureJqueryAndBootstrap(initializeMenuDropdowns);
    }
});

// Re-initialize after a short delay to ensure everything is loaded
setTimeout(function() {
    ensureJqueryAndBootstrap(initializeMenuDropdowns);
}, 500);
</script>

<script>
(function() {
    const IDLE_MS = 60 * 1000;
    const WARN_SECONDS = 30;
    const LOGOUT_URL = '{{ route('logout') }}';

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
        window.location.href = LOGOUT_URL;
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
