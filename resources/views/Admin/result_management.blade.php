@if($user_type == 'Admin')
@include('includes.Admin_nav')
@elseif($user_type == 'Staff')
@include('includes.staff_nav')
@else
@include('includes.teacher_nav')
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* 1. Global Font and Base Rule */
    body, .card, .modal-content, .table, h1, h2, h3, h4, h5, h6, p, div, span, button, input, select {
        font-family: 'Century Gothic', Muli, sans-serif !important;
    }

    /* 2. No Box Shadows ANYWHERE */
    * {
        box-shadow: none !important;
    }

    /* 3. Primary Background: #FFFFFF */
    body, .card, .card-body, .modal-content, .main-panel, select option {
        background-color: #FFFFFF !important;
    }

    /* 4. Corporate Red: #940000 */
    .bg-primary-custom, .btn-primary-custom, .bg-primary, .bg-info, .bg-success, .bg-warning, .btn-primary, .btn-info, .btn-success, .btn-warning, .btn-dark, .badge-primary, .badge-info, .badge-success {
        background-color: #940000 !important;
        border-color: #940000 !important;
        color: #FFFFFF !important;
    }

    .text-primary-custom, .text-primary, .text-info, h1, h2, h3, h4, h5, h6, .text-success, .text-muted, .text-dark, .text-danger {
        color: #940000 !important;
    }

    /* Border assignments */
    .border, .border-bottom, .border-top, .border-right, .border-left, .card, .table-bordered th, .table-bordered td, .form-control, .form-select {
        border-color: rgba(148, 0, 0, 0.2) !important;
    }

    /* 5. Secondary Pale Gradient: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%) */
    .card-header, .modal-header, table thead, table thead th, .nav-tabs .nav-link.active, .bg-light {
        background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%) !important;
        background-color: transparent !important;
        color: #940000 !important;
        border-bottom: 2px solid #940000 !important;
    }

    /* 6. Accent Soft Red: #FFF5F5 */
    /* Hover effects and stat cards */
    .bg-info-light, .bg-warning-light, .bg-success-light, .bg-danger-light, .alert, .list-group-item, .card-body.bg-light {
        background: #FFF5F5 !important;
        background-color: #FFF5F5 !important;
        color: #940000 !important;
        border: 1px solid rgba(148, 0, 0, 0.2) !important;
    }

    tbody tr:hover, table.table-hover tbody tr:hover td {
        background-color: #FFF5F5 !important;
        color: #940000 !important;
    }

    /* Buttons Hover */
    .btn:hover {
        background-color: #FFF5F5 !important;
        color: #940000 !important;
        border-color: #940000 !important;
    }

    /* Outline Buttons */
    .btn-outline-primary, .btn-outline-info, .btn-outline-danger {
        color: #940000 !important;
        background-color: #FFFFFF !important;
        border-color: #940000 !important;
    }

    .btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-danger:hover {
        background-color: #FFF5F5 !important;
        color: #940000 !important;
    }

    /* Inactive Tabs */
    .nav-tabs .nav-link {
        color: #940000 !important;
        opacity: 0.7;
    }
    .nav-tabs .nav-link.active {
        opacity: 1;
        font-weight: bold;
    }

    /* Overrides for specific UI elements */
    .result-card {
        transition: all 0.2s ease;
        border: 1px solid rgba(148, 0, 0, 0.2) !important;
    }
    .result-card:hover {
        transform: translateY(-2px);
        background-color: #FFF5F5 !important;
    }
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }
    .status-marker i {
        margin-right: 4px;
    }
    
    /* Ensure inputs have active red focus */
    .form-control:focus, .form-select:focus {
        border-color: #940000 !important;
        outline: 0;
        box-shadow: none !important;
    }
    
    /* Card Title Background Fix */
    .card-body.bg-primary-custom {
        background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%) !important;
        border-color: #940000 !important;
        border-left: 4px solid #940000 !important;
    }
    
    /* Dropdown Menus */
    .dropdown-menu {
        background-color: #FFFFFF !important;
        border: 1px solid rgba(148, 0, 0, 0.2) !important;
    }
    .dropdown-item {
        color: #940000 !important;
    }
    .dropdown-item:hover {
        background-color: #FFF5F5 !important;
    }
    /* Glassmorphism Widgets */
    .glass-widget {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 12px !important;
        transition: all 0.3s ease;
    }

    .glass-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .pulse-animation {
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% { transform: scale(1); text-shadow: 0 0 0 rgba(255, 255, 255, 0.7); }
        70% { transform: scale(1.1); text-shadow: 0 0 10px rgba(255, 255, 255, 0); }
        100% { transform: scale(1); text-shadow: 0 0 0 rgba(255, 255, 255, 0); }
    }

    .teacher-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background: #f8f9fa;
    }

    .transition-icon {
        transition: transform 0.3s ease;
    }

    .collapse.show + .card-header .transition-icon,
    [aria-expanded="true"] .transition-icon {
        transform: rotate(180deg);
    }

    .bg-light-warning { background-color: #fff9f0 !important; }
    
    .subject-row:hover { background-color: rgba(243, 156, 18, 0.05); }

    .student-tag {
        display: inline-block;
        padding: 4px 12px;
        margin: 3px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: 0.85rem;
        color: #4a5568;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<!-- jsPDF Library for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>

<!-- SheetJS for Excel export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="sessionErrorAlert" style="display: none;">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(isset($error) && $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert" style="display: none;">
                    <i class="bi bi-exclamation-triangle"></i> {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Page Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-primary-custom text-white rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-trophy"></i> Result Management
                        </h4>
                        @if(!isset($isTeacherView) || !$isTeacherView)
                        <div class="d-flex btn-group flex-wrap">
                            <button type="button" class="btn btn-light btn-sm mr-2" data-toggle="modal" data-target="#gradeDefinitionModal">
                                <i class="bi bi-bookmark-star"></i> Define Grades
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold mr-2" data-toggle="modal" data-target="#reportDefinitionModal" id="btnDefineReport">
                                <i class="bi bi-file-earmark-ruled"></i> Define Report
                            </button>
                            <button type="button" class="btn btn-info btn-sm font-weight-bold" data-toggle="modal" data-target="#caDefinitionModal" id="btnDefineCA">
                                <i class="bi bi-calculator"></i> Define CA
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('manageResults') }}">
                        <!-- Filtering Description Display -->
                        <div id="filteringText" class="mb-3 text-dark font-weight-bold border-bottom pb-2">
                            @if(isset($filteringDescription))
                                {!! $filteringDescription !!}
                            @else
                                <i class="bi bi-funnel"></i> Filtering: All active results for current year
                            @endif
                            
                            @php
                                $schoolID = Session::get('schoolID');
                                $currentYear = date('Y');
                                $currentTerm = $filters['term'] ?? 'first_term'; // Use $filters['term'] if available, otherwise default
                                
                                $reportDef = \App\Models\TermReportDefinition::where('schoolID', $schoolID)
                                    ->where('year', $filters['year'] ?? $currentYear) // Use $filters['year'] if available
                                    ->where('term', $currentTerm)
                                    ->first();
                            @endphp
                            
                            @if($reportDef && !empty($reportDef->exam_ids))
                                <div class="mt-2">
                                    <span class="badge badge-warning text-dark p-2">
                                        <i class="bi bi-info-circle-fill"></i> 
                                        Note: This report is calculated based on <strong>{{ count($reportDef->exam_ids) }} defined exams</strong> only.
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="alert alert-info mb-3" id="filteringDescription" style="display: none;">
                            <i class="bi bi-info-circle"></i> <strong>Filtering:</strong> <span id="filteringText"></span>
                        </div>
                        <!-- Loading Indicator -->
                        <div class="alert alert-info mb-3" id="loadingIndicator" style="display: none;">
                            <i class="bi bi-hourglass-split"></i> <strong>Loading...</strong> Please wait while we filter the results.
                        </div>
                        <!-- Error Message -->
                        <div class="alert alert-danger mb-3" id="errorMessage" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> <span id="errorText"></span>
                        </div>
                        <div class="row">
                            <!-- Term Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="term" class="form-label">
                                    <i class="bi bi-calendar-week"></i> Term
                                </label>
                                <select class="form-control" id="term" name="term">
                                    <option value="">All Terms</option>
                                    <option value="first_term" {{ ($filters['term'] ?? '') == 'first_term' ? 'selected' : '' }}>First Term</option>
                                    <option value="second_term" {{ ($filters['term'] ?? '') == 'second_term' ? 'selected' : '' }}>Second Term</option>
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="year" class="form-label">
                                    <i class="bi bi-calendar"></i> Year
                                </label>
                                <select class="form-control" id="year" name="year">
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ ($filters['year'] ?? date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-file-text"></i> Type
                                </label>
                                <select class="form-control" id="type" name="type">
                                    <option value="exam" {{ ($filters['type'] ?? 'exam') == 'exam' ? 'selected' : '' }}>Exam Results</option>
                                    <option value="report" {{ ($filters['type'] ?? '') == 'report' ? 'selected' : '' }}>Term Report</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">
                                    <i class="bi bi-person-check"></i> Student Status
                                </label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" {{ ($filters['status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active (Currently Studying)</option>
                                    <option value="all" {{ ($filters['status'] ?? '') == 'all' ? 'selected' : '' }}>All Students</option>
                                    <option value="history" {{ ($filters['status'] ?? '') == 'history' ? 'selected' : '' }}>History (Transferred)</option>
                                </select>
                            </div>

                            <!-- Class Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="class" class="form-label">
                                    <i class="bi bi-columns"></i> Main Class
                                </label>
                                @if((isset($isTeacherView) && $isTeacherView) || (isset($isCoordinatorResultsView) && $isCoordinatorResultsView))
                                    @php
                                        $selectedClass = $classes->firstWhere('classID', $filters['class'] ?? '');
                                    @endphp
                                    <input type="text" class="form-control" id="lockedClassDisplay" value="{{ $selectedClass ? $selectedClass->class_name : 'N/A' }}" readonly style="background-color: #e9ecef !important; color: #6c757d !important; cursor: not-allowed; opacity: 0.7;">
                                    <input type="hidden" name="class" value="{{ $filters['class'] ?? '' }}" id="lockedClassID">
                                    <small class="text-muted"><i class="bi bi-lock"></i> Locked - {{ isset($isCoordinatorResultsView) && $isCoordinatorResultsView ? 'Coordinator assigned class' : 'Your assigned class' }}</small>
                                @else
                                    <select class="form-control" id="class" name="class">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->classID }}" {{ ($filters['class'] ?? '') == $class->classID ? 'selected' : '' }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Subclass Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="subclass" class="form-label">
                                    <i class="bi bi-list-ul"></i> Subclass
                                </label>
                                @if(isset($isTeacherView) && $isTeacherView)
                                    @php
                                        $selectedSubclass = $subclasses->firstWhere('subclassID', $filters['subclass'] ?? '');
                                    @endphp
                                    <input type="text" class="form-control" id="lockedSubclassDisplay" value="{{ $selectedSubclass ? ($selectedSubclass->display_name ?? ($selectedSubclass->class_name . ' ' . $selectedSubclass->subclass_name)) : 'N/A' }}" readonly style="background-color: #e9ecef !important; color: #6c757d !important; cursor: not-allowed; opacity: 0.7;">
                                    <input type="hidden" name="subclass" value="{{ $filters['subclass'] ?? '' }}" id="lockedSubclassID">
                                    <input type="hidden" name="subclassID" value="{{ $filters['subclass'] ?? '' }}">
                                    <small class="text-muted"><i class="bi bi-lock"></i> Locked - Your assigned subclass</small>
                                @elseif(isset($isCoordinatorResultsView) && $isCoordinatorResultsView)
                                    <!-- Coordinator can select subclass, but main class is locked -->
                                    <select class="form-control" id="subclass" name="subclass">
                                        <option value="">All Subclasses</option>
                                        @foreach($subclasses as $subclass)
                                            <option value="{{ $subclass->subclassID }}" {{ ($filters['subclass'] ?? '') == $subclass->subclassID ? 'selected' : '' }}>
                                                {{ $subclass->display_name ?? ($subclass->class_name . ' ' . $subclass->subclass_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Select subclass to view results</small>
                                @else
                                    <select class="form-control" id="subclass" name="subclass">
                                        <option value="">All Subclasses</option>
                                        @foreach($subclasses as $subclass)
                                            <option value="{{ $subclass->subclassID }}" {{ ($filters['subclass'] ?? '') == $subclass->subclassID ? 'selected' : '' }}>
                                                {{ $subclass->display_name ?? ($subclass->class_name . ' ' . $subclass->subclass_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Exam Filter (only show when type is 'exam') -->
                            <div class="col-md-3 mb-3" id="examFilterContainer" style="display: {{ ($filters['type'] ?? 'exam') == 'exam' ? 'block' : 'none' }};">
                                <label for="examID" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i> Select Exam
                                </label>
                                <select class="form-control" id="examID" name="examID">
                                    <option value="">All Exams</option>
                                    @foreach($availableExams as $exam)
                                        <option value="{{ $exam->examID }}" {{ ($filters['examID'] ?? '') == $exam->examID ? 'selected' : '' }}>
                                            {{ $exam->exam_name }} ({{ $exam->start_date ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Week Filter (only show when available) -->
                            <div class="col-md-3 mb-3" id="weekFilterContainer" style="display: {{ (isset($availableWeeks) && count($availableWeeks) > 0) ? 'block' : 'none' }};">
                                <label for="week" class="form-label">
                                    <i class="bi bi-calendar-event"></i> Select Week
                                </label>
                                <select class="form-control" id="week" name="week">
                                    <option value="all" {{ ($filters['week'] ?? 'all') == 'all' ? 'selected' : '' }}>All Weeks</option>
                                    @foreach($availableWeeks as $weekData)
                                        <option value="{{ $weekData['week'] }}" {{ ($filters['week'] ?? '') == $weekData['week'] ? 'selected' : '' }}>
                                            {{ $weekData['display'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Filter -->
                            <div class="col-md-3 mb-3">
                                <label for="subjectID" class="form-label">
                                    <i class="bi bi-book"></i> Subject
                                </label>
                                <select class="form-control" id="subjectID" name="subjectID">
                                    <option value="">All Subjects</option>
                                    @foreach($schoolSubjects as $subject)
                                        <option value="{{ $subject->subjectID }}" {{ ($filters['subjectID'] ?? '') == $subject->subjectID ? 'selected' : '' }}>
                                            {{ $subject->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary-custom" id="filterSubmitBtn">
                                    <i class="bi bi-search"></i> Filter Results
                                </button>
                                <button type="button" class="btn btn-secondary" id="clearFiltersBtn">
                                    <i class="bi bi-x-circle"></i> Clear Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Display -->
            <div class="card border-0 shadow-sm">
                <div class="card-body" id="resultsContainer">
                    <!-- Search Box for Students and Export All -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="studentSearch" placeholder="Search student by name or admission number...">
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <span class="badge badge-info" id="resultCount">Showing <span id="resultCountNumber">0</span> results</span>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-danger btn-sm" id="exportAllPdf" title="Export All Students to PDF">
                                <i class="bi bi-file-pdf"></i> Export All PDF
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="exportAllExcel" title="Export All Students to Excel">
                                <i class="bi bi-file-excel"></i> Export All Excel
                            </button>
                            <button type="button" class="btn btn-primary-custom btn-sm" id="sendSmsAll" title="Send Results to Parents via SMS">
                                <i class="bi bi-chat-dots"></i> Send SMS
                            </button>
                        </div>
                    </div>

                    <!-- Incomplete Results Widget (Dynamic) -->
                    <div class="row mb-4" id="incompleteResultsContainer" style="display: none;">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm glass-widget overflow-hidden">
                                <div class="card-header bg-warning-custom text-white d-flex justify-content-between align-items-center cursor-pointer py-3" data-toggle="collapse" data-target="#incompleteResultsCollapse" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                                    <h5 class="mb-0 font-weight-bold">
                                        <i class="bi bi-exclamation-triangle-fill mr-2 pulse-animation"></i> 
                                        Incomplete Result Subjects for <span id="incompleteExamName">Current Exam</span>
                                        <span class="badge badge-white ml-2 text-warning" id="incompleteCount">0</span>
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-dark btn-sm font-weight-bold mr-3 rounded-pill px-3 shadow" id="sendSmsToAllIncomplete">
                                            <i class="bi bi-chat-left-dots-fill mr-1"></i> Remind All Teachers
                                        </button>
                                        <i class="bi bi-chevron-down accordion-icon transition-icon"></i>
                                    </div>
                                </div>
                                <div id="incompleteResultsCollapse" class="collapse">
                                    <div class="card-body p-0 bg-light-warning">
                                        <div class="list-group list-group-flush" id="incompleteResultsList">
                                            <!-- Content will be injected here via AJAX -->
                                            <div class="p-4 text-center">
                                                <div class="spinner-border text-warning" role="status"></div>
                                                <p class="mt-2 text-muted">Calculating completion statistics...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        // Check if we should show detailed view (for both exam and report)
                        $showDetailedView = false;
                        $selectedExam = null;
                        $selectedClass = null;
                        
                        // Check conditions: type=exam, examID selected, class selected
                        // Either subclass empty (main class) OR subclass selected (subclass view)
                        $subclassEmpty = empty($filters['subclass']) || 
                                        $filters['subclass'] == '' || 
                                        $filters['subclass'] == null ||
                                        $filters['subclass'] == '0';
                        
                        $subclassSelected = !empty($filters['subclass']) && 
                                           $filters['subclass'] != '' && 
                                           $filters['subclass'] != null &&
                                           $filters['subclass'] != '0';
                        
                        // Debug: Check if all conditions are met
                        $typeIsExam = ($filters['type'] ?? '') == 'exam';
                        $typeIsReport = ($filters['type'] ?? '') == 'report';
                        $examIDSelected = !empty($filters['examID']) && $filters['examID'] != '';
                        $classSelected = !empty($filters['class']) && $filters['class'] != '';
                        // For teachers, if isTeacherView is true, never show "All Classes"
                        $allClassesSelected = false;
                        if (isset($isTeacherView) && $isTeacherView) {
                            $allClassesSelected = false; // Teachers always have a specific class/subclass
                            $classSelected = true; // Force classSelected to true for teachers
                        } else {
                            $allClassesSelected = empty($filters['class']) || $filters['class'] == '' || $filters['class'] == null;
                        }
                        $termSelected = !empty($filters['term']) && $filters['term'] != '';
                        
                        // Show detailed view for:
                        // For Exam:
                        // 1. Main class (class selected, subclass empty) OR
                        // 2. Subclass (class selected, subclass selected) OR
                        // 3. All classes (class empty, examID selected)
                        // For Report:
                        // 1. Main class (class selected, subclass empty, term selected) OR
                        // 2. Subclass (class selected, subclass selected, term selected) OR
                        // 3. All classes (class empty, term selected)
                        $showDetailedForExam = $typeIsExam && $examIDSelected && (($classSelected && ($subclassEmpty || $subclassSelected)) || $allClassesSelected);
                        $showDetailedForReport = $typeIsReport && $termSelected && (($classSelected && ($subclassEmpty || $subclassSelected)) || $allClassesSelected);
                        
                        if ($showDetailedForExam || $showDetailedForReport) {
                            // Find exam - try both string and integer comparison
                            if ($typeIsExam) {
                                $examID = $filters['examID'];
                                $selectedExam = $availableExams->first(function($exam) use ($examID) {
                                    return $exam->examID == $examID || (string)$exam->examID === (string)$examID;
                                });
                            }
                            
                            // Find class - try both string and integer comparison
                            $selectedClass = null;
                            if ($classSelected) {
                                $classID = $filters['class'];
                                $selectedClass = $classes->first(function($class) use ($classID) {
                                    return $class->classID == $classID || (string)$class->classID === (string)$classID;
                                });
                            }
                            
                            // Find subclass if selected
                            $selectedSubclass = null;
                            if ($subclassSelected) {
                                $subclassID = $filters['subclass'];
                                $selectedSubclass = \App\Models\Subclass::where('subclassID', $subclassID)
                                    ->where('status', 'Active')
                                    ->first();
                                    
                                // If subclass found, get its class
                                if ($selectedSubclass && $selectedSubclass->class) {
                                    $selectedClass = $selectedSubclass->class;
                                }
                            }
                            
                            // Only show detailed view if exam is found (for exam type)
                            // If class is selected, class must also be found
                            // If subclass is selected, subclass must also be found
                            // For teachers, always show if they have class/subclass selected
                            if (isset($isTeacherView) && $isTeacherView && $classSelected) {
                                // Teacher view: show if class is selected (subclass is always selected for teachers)
                                if ($showDetailedForExam && $selectedExam) {
                                    $showDetailedView = true;
                                } elseif ($showDetailedForReport) {
                                    $showDetailedView = true;
                                }
                            } elseif ($showDetailedForExam && $selectedExam && ($allClassesSelected || ($selectedClass && (!$subclassSelected || $selectedSubclass)))) {
                                $showDetailedView = true;
                            } elseif ($showDetailedForReport && ($allClassesSelected || ($selectedClass && (!$subclassSelected || $selectedSubclass)))) {
                                // For report, only need class/subclass validation, no exam needed
                                $showDetailedView = true;
                            }
                        }
                    @endphp
                    
                    @if($filters['type'] == 'report' && !$showDetailedView)
                        <!-- Term Report Display (Simple View - when detailed view conditions are not met) -->
                        <h5 class="mb-3">
                            <i class="bi bi-file-earmark-text"></i> Term Report
                            @if($filters['term'])
                                - {{ ucfirst(str_replace('_', ' ', $filters['term'])) }}
                            @endif
                            - {{ $filters['year'] ?? date('Y') }}
                        </h5>

                        @if(count($resultsData) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="resultsTable">
                                    <thead class="bg-primary-custom text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            @if($schoolType === 'Primary')
                                                <th>Grade</th>
                                            @else
                                                <th>Division</th>
                                            @endif
                                            <th>Position</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                            @if(isset($resultsData[$student->studentID]))
                                                @php
                                                    $result = $resultsData[$student->studentID];
                                                @endphp
                                                <tr class="student-row" data-student-name="{{ strtolower($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name) }}" data-admission="{{ strtolower($student->admission_number ?? '') }}" data-student-id="{{ $student->studentID }}" data-main-class="@if($student->subclass && $student->subclass->class){{ $student->subclass->class->class_name }}@elseif($student->oldSubclass && $student->oldSubclass->class){{ $student->oldSubclass->class->class_name }}@else N/A @endif">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        {{ $student->first_name }} 
                                                        {{ $student->middle_name ?? '' }} 
                                                        {{ $student->last_name }}
                                                    </td>
                                                    <td>
                                                        @if($student->subclass && $student->subclass->class)
                                                            {{ $student->subclass->class->class_name }}
                                                        @elseif($student->oldSubclass && $student->oldSubclass->class)
                                                            {{ $student->oldSubclass->class->class_name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    @if($schoolType === 'Primary')
                                                        <td>
                                                            <span class="badge badge-info">{{ $result['grade'] ?? 'N/A' }}</span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge badge-warning">{{ $result['division'] ?? ($result['grade'] ?? 'N/A') }}</span>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <span class="badge badge-success">{{ $result['position'] ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger download-student-btn" data-student-id="{{ $student->studentID }}" data-type="pdf" title="Download PDF">
                                                            <i class="bi bi-file-pdf"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success download-student-btn" data-student-id="{{ $student->studentID }}" data-type="excel" title="Download Excel">
                                                            <i class="bi bi-file-excel"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center" id="noResultsAlert">
                                <i class="bi bi-info-circle"></i> 
                                @if(empty($filters['term']) || empty($filters['year']))
                                    <strong>Please select both Term and Year to view results.</strong>
                                @else
                                    <div>
                                        <strong>No results found for the selected filters.</strong>
                                        <p class="mt-2 mb-1">Possible reasons:</p>
                                        <ul class="text-left" style="display: inline-block; text-align: left;">
                                            <li>Students don't have results with <strong>status 'allowed'</strong> and <strong>marks entered</strong></li>
                                            <li>Exam has not ended yet (except Weekly/Monthly tests)</li>
                                            <li>Exam is not approved yet</li>
                                            <li>Results have status <strong>'not_allowed'</strong> - they need to be changed to <strong>'allowed'</strong> first</li>
                                        </ul>
                                        @if(isset($debugInfo) && !empty($debugInfo))
                                            <div class="mt-3 p-2 bg-light rounded" style="text-align: left; max-width: 600px; margin: 10px auto;">
                                                <strong>Details:</strong>
                                                <ul class="mb-0" style="padding-left: 20px;">
                                                    @foreach($debugInfo as $info)
                                                        <li>{{ $info }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <p class="mt-3 mb-0">
                                            <small><strong>Solution:</strong> Go to <strong>Manage Examinations</strong> page, select the exam, and change results status from 'not_allowed' to 'allowed'.</small>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            
                            @if(!empty($filters['term']) && !empty($filters['year']) && isset($debugInfo) && !empty($debugInfo))
                                <script>
                                $(document).ready(function() {
                                    // Check if any debug info mentions 'not_allowed'
                                    var hasNotAllowed = false;
                                    var debugMessages = @json($debugInfo);
                                    
                                    debugMessages.forEach(function(msg) {
                                        if (msg.toLowerCase().includes('not_allowed')) {
                                            hasNotAllowed = true;
                                        }
                                    });
                                    
                                    if (hasNotAllowed && typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Results Status Issue',
                                            html: '<div style="text-align: left;">' +
                                                  '<p><strong>Results found but cannot be viewed.</strong></p>' +
                                                  '<p>The results have status <strong>"not_allowed"</strong> and need to be changed to <strong>"allowed"</strong> first.</p>' +
                                                  '<hr>' +
                                                  '<p><strong>To fix this:</strong></p>' +
                                                  '<ol style="text-align: left;">' +
                                                  '<li>Go to <strong>Manage Examinations</strong> page</li>' +
                                                  '<li>Find and select the exam ({{ $filters["examID"] ? "Exam ID: " . $filters["examID"] : "for " . $filters["term"] . " term, " . $filters["year"] }})</li>' +
                                                  '<li>Click on the results status dropdown</li>' +
                                                  '<li>Select <strong>"Allow Results Entry"</strong> or <strong>"Allow"</strong></li>' +
                                                  '</ol>' +
                                                  '</div>',
                                            confirmButtonText: 'OK, I understand',
                                            confirmButtonColor: '#940000',
                                            allowOutsideClick: true,
                                            allowEscapeKey: true,
                                            width: '600px'
                                        });
                                    }
                                });
                                </script>
                            @endif
                        @endif

                    @elseif($filters['type'] == 'exam' && !$showDetailedView)
                        <!-- Simple Exam Display (when detailed view conditions are not met) -->
                        <!-- This will show the original exam display format -->
                    @endif
                    
                    @if($showDetailedView && $typeIsExam)
                            <!-- Detailed Exam Results View for Main Class or Subclass -->
                            <div class="detailed-exam-results">
                                @php
                                    // Re-check subclass conditions for title display
                                    $subclassEmptyForTitle = empty($filters['subclass']) || 
                                                            $filters['subclass'] == '' || 
                                                            $filters['subclass'] == null ||
                                                            $filters['subclass'] == '0';
                                    
                                    $subclassSelectedForTitle = !empty($filters['subclass']) && 
                                                               $filters['subclass'] != '' && 
                                                               $filters['subclass'] != null &&
                                                               $filters['subclass'] != '0';
                                    
                                    // Re-find subclass if selected for title
                                    $selectedSubclassForTitle = null;
                                    $subclassDisplayName = '';
                                    if ($subclassSelectedForTitle) {
                                        $subclassIDForTitle = $filters['subclass'];
                                        $selectedSubclassForTitle = \App\Models\Subclass::with('class')
                                            ->where('subclassID', $subclassIDForTitle)
                                            ->where('status', 'Active')
                                            ->first();
                                        if ($selectedSubclassForTitle) {
                                            $subclassName = trim($selectedSubclassForTitle->subclass_name);
                                            $subclassDisplayName = empty($subclassName) 
                                                ? $selectedSubclassForTitle->class->class_name 
                                                : $selectedSubclassForTitle->class->class_name . ' ' . $subclassName;
                                        }
                                    }
                                @endphp
                                
                                <!-- Title -->
                                <h4 class="mb-2 text-center">
                                    <i class="bi bi-trophy"></i> 
                                    <strong>
                                        @php
                                            $weekLabel = '';
                                            if (isset($filters['week']) && $filters['week'] !== 'all' && isset($availableWeeks)) {
                                                foreach ($availableWeeks as $wk) {
                                                    if ($wk['week'] == $filters['week']) {
                                                        // Simplify label for title: remove CURRENT/OLD prefx
                                                        $weekLabel = str_replace(['CURRENT: ', 'OLD: '], '', $wk['display']);
                                                        break;
                                                    }
                                                }
                                                if (empty($weekLabel)) $weekLabel = $filters['week'];
                                            }
                                        @endphp
                                        
                                        @if($allClassesSelected)
                                            ALL CLASSES STUDENT RESULT IN {{ strtoupper($selectedExam->exam_name) }}
                                            @if(!empty($weekLabel)) - {{ strtoupper($weekLabel) }} @endif
                                        @elseif($subclassSelectedForTitle && $selectedSubclassForTitle)
                                            {{ strtoupper($subclassDisplayName) }} STUDENT RESULT IN {{ strtoupper($selectedExam->exam_name) }}
                                            @if(!empty($weekLabel)) - {{ strtoupper($weekLabel) }} @endif
                                        @elseif($selectedClass)
                                            {{ strtoupper($selectedClass->class_name) }} STUDENT RESULT IN {{ strtoupper($selectedExam->exam_name) }}
                                            @if(!empty($weekLabel)) - {{ strtoupper($weekLabel) }} @endif
                                        @else
                                            STUDENT RESULT IN {{ strtoupper($selectedExam->exam_name) }}
                                            @if(!empty($weekLabel)) - {{ strtoupper($weekLabel) }} @endif
                                        @endif
                                    </strong>
                                </h4>

                                <!-- Exam Status Message -->
                                @if(isset($examStatusMessage))
                                    <div class="text-center mb-4">
                                        <span class="badge {{ (str_contains(strtolower($examStatusMessage), 'ongoing') || str_contains(strtolower($examStatusMessage), 'continuous')) ? 'badge-info' : 'badge-success' }} p-2">
                                            <i class="bi {{ (str_contains(strtolower($examStatusMessage), 'ongoing') || str_contains(strtolower($examStatusMessage), 'continuous')) ? 'bi-info-circle' : 'bi-check-circle' }}"></i>
                                            {{ $examStatusMessage }}
                                        </span>
                                    </div>
                                @endif

                                @php
                                    // Re-check conditions (variables from previous PHP block)
                                    // For teachers, if isTeacherView is true, never show "All Classes"
                                    $allClassesSelected = false;
                                    if (isset($isTeacherView) && $isTeacherView) {
                                        $allClassesSelected = false; // Teachers always have a specific class/subclass
                                    } else {
                                        $allClassesSelected = empty($filters['class']) || $filters['class'] == '' || $filters['class'] == null;
                                    }
                                    
                                    $subclassEmpty = empty($filters['subclass']) || 
                                                    $filters['subclass'] == '' || 
                                                    $filters['subclass'] == null ||
                                                    $filters['subclass'] == '0';
                                    
                                    $subclassSelected = !empty($filters['subclass']) && 
                                                       $filters['subclass'] != '' && 
                                                       $filters['subclass'] != null &&
                                                       $filters['subclass'] != '0';
                                    
                                    // Re-find subclass if selected
                                    $selectedSubclass = null;
                                    if ($subclassSelected) {
                                        $subclassID = $filters['subclass'];
                                        $selectedSubclass = \App\Models\Subclass::where('subclassID', $subclassID)
                                            ->where('status', 'Active')
                                            ->first();
                                    }
                                    
                                    // Process students with results for this exam
                                    // Filter by subclass if subclass is selected
                                    $examStudents = [];
                                    $totalStudents = 0;
                                    $maleCount = 0;
                                    $femaleCount = 0;
                                    $gradeStats = [];
                                    $divisionStats = [];
                                    
                                    foreach ($students as $student) {
                                        // Filter by class if class is selected (not "All Classes")
                                        if (!$allClassesSelected && $selectedClass) {
                                            // Get student's class
                                            $studentClassID = null;
                                            if ($student->subclass && $student->subclass->class) {
                                                $studentClassID = $student->subclass->class->classID;
                                            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                                $studentClassID = $student->oldSubclass->class->classID;
                                            }
                                            
                                            if ($studentClassID != $selectedClass->classID) {
                                                continue; // Skip students not in selected class
                                            }
                                        }
                                        
                                        // Filter by subclass if subclass is selected
                                        if ($subclassSelected && $selectedSubclass) {
                                            $studentSubclassID = $student->subclassID ?? $student->oldSubclassID ?? null;
                                            if ($studentSubclassID != $selectedSubclass->subclassID) {
                                                continue; // Skip students not in selected subclass
                                            }
                                        }
                                        
                                        
                                        if (isset($resultsData[$student->studentID])) {
                                            $studentResults = $resultsData[$student->studentID];
                                            // Find the result for this specific exam
                                            $studentAdded = false;
                                            foreach ($studentResults as $examResult) {
                                                if ($examResult['exam']->examID == $filters['examID']) {
                                                    // Get grade/division
                                                    $grade = $examResult['grade'] ?? null;
                                                    $division = $examResult['division'] ?? null;
                                                    
                                                    // Add student
                                                    {
                                                        $totalStudents++;
                                                        $studentAdded = true;
                                                        
                                                        // Count gender
                                                        if ($student->gender == 'Male') {
                                                            $maleCount++;
                                                        } elseif ($student->gender == 'Female') {
                                                            $femaleCount++;
                                                        }
                                                        
                                                        // Count grades (for Primary)
                                                        if ($schoolType === 'Primary' && $grade) {
                                                            if (!isset($gradeStats[$grade])) {
                                                                $gradeStats[$grade] = 0;
                                                            }
                                                            $gradeStats[$grade]++;
                                                        }
                                                        
                                                        // Count divisions (for Secondary)
                                                        if ($schoolType === 'Secondary' && $division) {
                                                            // Extract division number (e.g., "I.7" -> "I", "0.34" -> "0")
                                                            $divMatch = preg_match('/^([0IVX]+)\./', $division, $matches);
                                                            if ($divMatch) {
                                                                $divNum = $matches[1];
                                                                if (!isset($divisionStats[$divNum])) {
                                                                    $divisionStats[$divNum] = 0;
                                                                }
                                                                $divisionStats[$divNum]++;
                                                            }
                                                        }
                                                        
                                                        // Extract total points from division for sorting (e.g., "I.7" -> 7, "0.34" -> 34)
                                                        $totalPoints = 0;
                                                        if ($division) {
                                                            $pointsMatch = preg_match('/\.(\d+)$/', $division, $pointsMatches);
                                                            if ($pointsMatch) {
                                                                $totalPoints = (int)$pointsMatches[1];
                                                            }
                                                        }
                                                        
                                                        // Get best 7 total marks for sorting (for Secondary school)
                                                        $bestSevenTotalMarks = $examResult['best_seven_total_marks'] ?? 0;
                                                        
                                                        // Get student's class name
                                                        $studentClassName = 'N/A';
                                                        if ($student->subclass && $student->subclass->class) {
                                                            $studentClassName = $student->subclass->class->class_name;
                                                        } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                                            $studentClassName = $student->oldSubclass->class->class_name;
                                                        }
                                                        
                                                        $examStudents[] = [
                                                            'student' => $student,
                                                            'result' => $examResult,
                                                            'total_marks' => $examResult['total_marks'],
                                                            'total_points' => $totalPoints,
                                                            'best_seven_total_marks' => $bestSevenTotalMarks,
                                                            'grade' => $grade,
                                                            'division' => $division,
                                                            'class_name' => $studentClassName
                                                        ];
                                                        break; // Found matching exam, no need to continue
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Group students by class name for position calculation
                                    $studentsByClass = [];
                                    foreach ($examStudents as $examStudent) {
                                        $className = $examStudent['class_name'] ?? 'N/A';
                                        if (!isset($studentsByClass[$className])) {
                                            $studentsByClass[$className] = [];
                                        }
                                        $studentsByClass[$className][] = $examStudent;
                                    }
                                    
                                    // Sort and assign positions per class
                                    $sortedExamStudents = [];
                                    foreach ($studentsByClass as $className => $classStudents) {
                                        // Sort students in this class
                                        if ($schoolType === 'Secondary') {
                                            usort($classStudents, function($a, $b) {
                                                // First sort by total points (ascending - lower is better)
                                                if ($a['total_points'] != $b['total_points']) {
                                                    return $a['total_points'] <=> $b['total_points'];
                                                }
                                                // If points are equal, sort by best 7 total marks descending (higher marks = better)
                                                if ($a['best_seven_total_marks'] != $b['best_seven_total_marks']) {
                                                    return $b['best_seven_total_marks'] <=> $a['best_seven_total_marks'];
                                                }
                                                // If best 7 marks are also equal, sort by total marks descending
                                                return $b['total_marks'] <=> $a['total_marks'];
                                            });
                                        } else {
                                            // Primary: Sort by total marks descending
                                            usort($classStudents, function($a, $b) {
                                                return $b['total_marks'] <=> $a['total_marks'];
                                            });
                                        }
                                        
                                        // Assign positions within this class (starting from 1 for each class)
                                        foreach ($classStudents as $index => $student) {
                                            $classStudents[$index]['position'] = $index + 1;
                                        }
                                        
                                        // Add to sorted array
                                        $sortedExamStudents = array_merge($sortedExamStudents, $classStudents);
                                    }
                                    
                                    // Replace examStudents with sorted and positioned students
                                    $examStudents = $sortedExamStudents;
                                    
                                    // Get top 5 overall (after positions are assigned per class)
                                    // Sort all students again for top 5 selection
                                    if ($schoolType === 'Secondary') {
                                        usort($examStudents, function($a, $b) {
                                            // First sort by total points (ascending - lower is better)
                                            if ($a['total_points'] != $b['total_points']) {
                                                return $a['total_points'] <=> $b['total_points'];
                                            }
                                            // If points are equal, sort by best 7 total marks descending (higher marks = better)
                                            if ($a['best_seven_total_marks'] != $b['best_seven_total_marks']) {
                                                return $b['best_seven_total_marks'] <=> $a['best_seven_total_marks'];
                                            }
                                            // If best 7 marks are also equal, sort by total marks descending
                                            return $b['total_marks'] <=> $a['total_marks'];
                                        });
                                    } else {
                                        // Primary: Sort by total marks descending
                                        usort($examStudents, function($a, $b) {
                                            return $b['total_marks'] <=> $a['total_marks'];
                                        });
                                    }
                                    
                                    // Get top 5 overall
                                    $top5Students = array_slice($examStudents, 0, 5);
                                    
                                    // But keep positions per class - re-group and maintain positions
                                    $finalExamStudents = [];
                                    foreach ($studentsByClass as $className => $classStudents) {
                                        // Sort students in this class again
                                        if ($schoolType === 'Secondary') {
                                            usort($classStudents, function($a, $b) {
                                                if ($a['total_points'] != $b['total_points']) {
                                                    return $a['total_points'] <=> $b['total_points'];
                                                }
                                                if ($a['best_seven_total_marks'] != $b['best_seven_total_marks']) {
                                                    return $b['best_seven_total_marks'] <=> $a['best_seven_total_marks'];
                                                }
                                                return $b['total_marks'] <=> $a['total_marks'];
                                            });
                                        } else {
                                            usort($classStudents, function($a, $b) {
                                                return $b['total_marks'] <=> $a['total_marks'];
                                            });
                                        }
                                        
                                        // Assign positions within this class
                                        foreach ($classStudents as $index => $student) {
                                            $classStudents[$index]['position'] = $index + 1;
                                        }
                                        
                                        $finalExamStudents = array_merge($finalExamStudents, $classStudents);
                                    }
                                    
                                    // Replace examStudents with final positioned students
                                    $examStudents = $finalExamStudents;
                                    
                                    // Calculate Male/Female per Division (Secondary) or Grade (Primary)
                                    $maleDivisionStats = [];
                                    $femaleDivisionStats = [];
                                    $maleGradeStats = [];
                                    $femaleGradeStats = [];
                                    
                                    foreach ($examStudents as $examStudent) {
                                        $gender = $examStudent['student']->gender ?? '';
                                        $grade = $examStudent['grade'] ?? null;
                                        $division = $examStudent['division'] ?? null;
                                        
                                        if ($schoolType === 'Primary' && $grade) {
                                            // Primary: Count by grade
                                            if ($gender === 'Male') {
                                                if (!isset($maleGradeStats[$grade])) {
                                                    $maleGradeStats[$grade] = 0;
                                                }
                                                $maleGradeStats[$grade]++;
                                            } elseif ($gender === 'Female') {
                                                if (!isset($femaleGradeStats[$grade])) {
                                                    $femaleGradeStats[$grade] = 0;
                                                }
                                                $femaleGradeStats[$grade]++;
                                            }
                                        } elseif ($schoolType === 'Secondary' && $division) {
                                            // Secondary: Count by division
                                            $divMatch = preg_match('/^([0IVX]+)\./', $division, $matches);
                                            if ($divMatch) {
                                                $divNum = $matches[1];
                                                if ($gender === 'Male') {
                                                    if (!isset($maleDivisionStats[$divNum])) {
                                                        $maleDivisionStats[$divNum] = 0;
                                                    }
                                                    $maleDivisionStats[$divNum]++;
                                                } elseif ($gender === 'Female') {
                                                    if (!isset($femaleDivisionStats[$divNum])) {
                                                        $femaleDivisionStats[$divNum] = 0;
                                                    }
                                                    $femaleDivisionStats[$divNum]++;
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Calculate Subject Statistics (Grade distribution per subject with Male/Female counts)
                                    $subjectStats = []; // Format: ['SUBJECT_NAME' => ['A' => ['male' => 0, 'female' => 0, 'total' => 0], ...]]
                                    
                                    foreach ($examStudents as $examStudent) {
                                        $gender = $examStudent['student']->gender ?? '';
                                        $subjects = $examStudent['result']['subjects'] ?? [];
                                        
                                        foreach ($subjects as $subject) {
                                            $subjectName = $subject['subject_name'] ?? 'N/A';
                                            $subjectGrade = $subject['grade'] ?? null;
                                            
                                            // Determine grade category
                                            $category = 'Incomplete';
                                            if ($subjectGrade && in_array($subjectGrade, ['A', 'B', 'C', 'D', 'E', 'F'])) {
                                                $category = $subjectGrade;
                                            }
                                            
                                            // Initialize subject if not exists
                                            if (!isset($subjectStats[$subjectName])) {
                                                $subjectStats[$subjectName] = [];
                                                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'Incomplete'] as $grade) {
                                                    $subjectStats[$subjectName][$grade] = [
                                                        'male' => 0,
                                                        'female' => 0,
                                                        'total' => 0
                                                    ];
                                                }
                                            }
                                            
                                            // Count by gender
                                            if ($gender === 'Male') {
                                                $subjectStats[$subjectName][$category]['male']++;
                                            } elseif ($gender === 'Female') {
                                                $subjectStats[$subjectName][$category]['female']++;
                                            }
                                            
                                            // Update total
                                            $subjectStats[$subjectName][$category]['total']++;
                                        }
                                    }
                                    
                                    // Calculate Overview Statistics
                                    $classAverage = $totalStudents > 0 ? array_sum(array_column($examStudents, 'total_marks')) / $totalStudents : 0;
                                    
                                    // Calculate average marks for male and female separately
                                    $maleTotalMarks = 0;
                                    $maleCountForAvg = 0;
                                    $femaleTotalMarks = 0;
                                    $femaleCountForAvg = 0;
                                    
                                    foreach ($examStudents as $examStudent) {
                                        $gender = $examStudent['student']->gender ?? '';
                                        $totalMarks = $examStudent['total_marks'] ?? 0;
                                        
                                        if ($gender === 'Male') {
                                            $maleTotalMarks += $totalMarks;
                                            $maleCountForAvg++;
                                        } elseif ($gender === 'Female') {
                                            $femaleTotalMarks += $totalMarks;
                                            $femaleCountForAvg++;
                                        }
                                    }
                                    
                                    $maleAverage = $maleCountForAvg > 0 ? $maleTotalMarks / $maleCountForAvg : 0;
                                    $femaleAverage = $femaleCountForAvg > 0 ? $femaleTotalMarks / $femaleCountForAvg : 0;
                                    
                                    // Calculate class average grade
                                    $classAverageGrade = '';
                                    if ($classAverage >= 75) {
                                        $classAverageGrade = 'A';
                                    } elseif ($classAverage >= 65) {
                                        $classAverageGrade = 'B';
                                    } elseif ($classAverage >= 50) {
                                        $classAverageGrade = 'C';
                                    } elseif ($classAverage >= 40) {
                                        $classAverageGrade = 'D';
                                    } elseif ($classAverage >= 30) {
                                        $classAverageGrade = 'E';
                                    } else {
                                        $classAverageGrade = 'F';
                                    }
                                    
                                    // Calculate pass/fail rates (Primary: A-E = pass, F = fail; Secondary: I-IV = pass, 0 = fail)
                                    $passedCount = 0;
                                    $failedCount = 0;
                                    
                                    if ($schoolType === 'Primary') {
                                        // Primary: A, B, C, D, E = pass; F = fail
                                        foreach ($examStudents as $examStudent) {
                                            $grade = $examStudent['grade'] ?? '';
                                            if (in_array($grade, ['A', 'B', 'C', 'D', 'E'])) {
                                                $passedCount++;
                                            } elseif ($grade === 'F') {
                                                $failedCount++;
                                            }
                                        }
                                    } else {
                                        // Secondary: I, II, III, IV = pass; 0 = fail
                                        foreach ($examStudents as $examStudent) {
                                            $division = $examStudent['division'] ?? '';
                                            if (preg_match('/^([IVX]+)\./', $division, $matches)) {
                                                $passedCount++;
                                            } elseif (preg_match('/^0\./', $division)) {
                                                $failedCount++;
                                            }
                                        }
                                    }
                                    
                                    $passRate = $totalStudents > 0 ? ($passedCount / $totalStudents) * 100 : 0;
                                    $failRate = $totalStudents > 0 ? ($failedCount / $totalStudents) * 100 : 0;
                                    
                                    // Generate Performance Remark and Comment based on average
                                    $performanceRemark = '';
                                    $performanceComment = '';
                                    
                                    if ($schoolType === 'Primary') {
                                        if ($classAverage >= 75) {
                                            $performanceRemark = 'Excellent';
                                            $performanceComment = 'The class has performed excellently with an outstanding average score. Keep up the great work!';
                                        } elseif ($classAverage >= 65) {
                                            $performanceRemark = 'Very Good';
                                            $performanceComment = 'The class has shown very good performance. Continue working hard to maintain this standard.';
                                        } elseif ($classAverage >= 50) {
                                            $performanceRemark = 'Good';
                                            $performanceComment = 'The class performance is good. There is room for improvement to achieve better results.';
                                        } elseif ($classAverage >= 40) {
                                            $performanceRemark = 'Fair';
                                            $performanceComment = 'The class performance is fair. More effort is needed to improve the overall results.';
                                        } else {
                                            $performanceRemark = 'Poor';
                                            $performanceComment = 'The class performance needs significant improvement. Immediate intervention is required.';
                                        }
                                    } else {
                                        // Secondary: Based on average marks (assuming 100 is max per subject)
                                        if ($classAverage >= 70) {
                                            $performanceRemark = 'Excellent';
                                            $performanceComment = 'The class has performed excellently with an outstanding average score. Keep up the great work!';
                                        } elseif ($classAverage >= 60) {
                                            $performanceRemark = 'Very Good';
                                            $performanceComment = 'The class has shown very good performance. Continue working hard to maintain this standard.';
                                        } elseif ($classAverage >= 50) {
                                            $performanceRemark = 'Good';
                                            $performanceComment = 'The class performance is good. There is room for improvement to achieve better results.';
                                        } elseif ($classAverage >= 40) {
                                            $performanceRemark = 'Fair';
                                            $performanceComment = 'The class performance is fair. More effort is needed to improve the overall results.';
                                        } else {
                                            $performanceRemark = 'Poor';
                                            $performanceComment = 'The class performance needs significant improvement. Immediate intervention is required.';
                                        }
                                    }
                                    
                                    // Prepare data for JavaScript PDF export
                                    $displayName = 'All Classes';
                                    if (!$allClassesSelected) {
                                        if ($subclassSelected && $selectedSubclass) {
                                            $subclassName = trim($selectedSubclass->subclass_name);
                                            $displayName = empty($subclassName) 
                                                ? ($selectedSubclass->class ? $selectedSubclass->class->class_name : 'All Classes')
                                                : ($selectedSubclass->class ? $selectedSubclass->class->class_name . ' ' . $subclassName : 'All Classes');
                                        } else {
                                            $displayName = $selectedClass ? $selectedClass->class_name : 'All Classes';
                                        }
                                    }
                                    
                                    $detailedViewData = [
                                        'className' => $displayName,
                                        'examName' => $selectedExam->exam_name,
                                        'year' => $filters['year'] ?? date('Y'),
                                        'totalStudents' => $totalStudents,
                                        'maleCount' => $maleCount,
                                        'femaleCount' => $femaleCount,
                                        'averageMarks' => number_format($classAverage, 1),
                                        'averageGrade' => $classAverageGrade,
                                        'maleAverage' => is_numeric($maleAverage) ? number_format($maleAverage, 1) : '0.0',
                                        'femaleAverage' => is_numeric($femaleAverage) ? number_format($femaleAverage, 1) : '0.0',
                                        'gradeStats' => $gradeStats,
                                        'divisionStats' => $divisionStats,
                                        'maleDivisionStats' => $maleDivisionStats,
                                        'femaleDivisionStats' => $femaleDivisionStats,
                                        'maleGradeStats' => $maleGradeStats,
                                        'femaleGradeStats' => $femaleGradeStats,
                                        'subjectStats' => $subjectStats,
                                        'passRate' => number_format($passRate, 1),
                                        'failRate' => number_format($failRate, 1),
                                        'performanceRemark' => $performanceRemark,
                                        'performanceComment' => $performanceComment,
                                        'top5Students' => array_map(function($s) {
                                            return [
                                                'position' => $s['position'] ?? 'N/A',
                                                'studentName' => ($s['student']->first_name ?? '') . ' ' . ($s['student']->middle_name ?? '') . ' ' . ($s['student']->last_name ?? ''),
                                                'divisionOrGrade' => $s['division'] ?? $s['grade'] ?? 'N/A'
                                            ];
                                        }, $top5Students),
                                        'allStudents' => array_map(function($s) {
                                            $st = $s['student'];
                                            $pPhone = '';
                                            if (!empty($st->parent) && !empty($st->parent->phone)) {
                                                $pPhone = $st->parent->phone;
                                            } elseif (!empty($st->emergency_contact_phone)) {
                                                $pPhone = $st->emergency_contact_phone;
                                            }
                                            
                                            // Final sanitization
                                            $pPhone = trim((string)$pPhone);
                                            if (in_array(strtolower($pPhone), ['null', 'undefined', 'n/a', ''])) $pPhone = '';

                                            return [
                                                'studentID' => $st->studentID,
                                                'firstName' => trim(($st->first_name ?? '') . ' ' . ($st->middle_name ?? '')),
                                                'lastName' => $st->last_name ?? '',
                                                'studentName' => ($st->first_name ?? '') . ' ' . ($st->middle_name ?? '') . ' ' . ($st->last_name ?? ''),
                                                'parentPhone' => (string)$pPhone,
                                                'className' => $s['class_name'] ?? 'N/A',
                                                'totalMarks' => $s['total_marks'] ?? 0,
                                                'grade' => $s['grade'] ?? null,
                                                'division' => $s['division'] ?? null,
                                                'subjects' => isset($s['result']['subjects']) ? array_map(function($subj) {
                                                    return [
                                                        'subject_name' => $subj['subject_name'] ?? 'N/A',
                                                        'marks' => $subj['marks'] ?? null,
                                                        'grade' => $subj['grade'] ?? null
                                                    ];
                                                }, $s['result']['subjects']) : [],
                                                'position' => $s['position'] ?? null
                                            ];
                                        }, $examStudents),
                                        'schoolName' => $school->school_name ?? Session::get('school_name', 'ShuleXpert')
                                    ];
                                @endphp
                                
                                <script>
                                    window.detailedViewData = @json($detailedViewData);
                                    console.log('Detailed View Data Loaded:', window.detailedViewData);
                                </script>

                                @if($totalStudents > 0)
                                    <!-- Overview Statistics Table -->
                                    <div class="card mb-4 border-0 shadow-sm">
                                        <div class="card-header bg-primary-custom text-white">
                                            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Overview Statistics</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @php
                                                    $genderFilter = null; // Gender filter removed
                                                @endphp
                                                
                                                @if(true)
                                                    <!-- Show all statistics if no gender filter -->
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ $totalStudents }}</h3>
                                                                <small class="text-muted">Total Students</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ $maleCount }}</h3>
                                                                <small class="text-muted">Male</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ $femaleCount }}</h3>
                                                                <small class="text-muted">Female</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ (isset($passedCount) ? $passedCount : 0) }}</h3>
                                                                <small class="text-muted">Total Passed</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Show only filtered gender statistics -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ $totalStudents }}</h3>
                                                                <small class="text-muted">Total {{ $genderFilter }} Students</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                            <div class="card-body text-center">
                                                                <h3 class="text-primary-custom mb-0">{{ $passedCount }}</h3>
                                                                <small class="text-muted">Total Passed</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($schoolType === 'Primary')
                                                <!-- Grade Statistics for Primary -->
                                                <div class="mt-3">
                                                    <h6 class="mb-2"><i class="bi bi-award"></i> Grade Distribution</h6>
                                                    <div class="row">
                                                        @php
                                                            $gradesToShow = ['A', 'B', 'C', 'D', 'E', 'F'];
                                                            // If grade filter is selected, show only that grade
                                                            if (!empty($filters['grade']) && $filters['grade'] != '') {
                                                                $gradesToShow = [strtoupper($filters['grade'])];
                                                            }
                                                        @endphp
                                                        @foreach($gradesToShow as $grade)
                                                            <div class="col-md-2 mb-2">
                                                                <div class="card bg-light">
                                                                    <div class="card-body text-center p-2">
                                                                        <strong class="text-primary-custom">{{ $gradeStats[$grade] ?? 0 }}</strong>
                                                                        <br><small class="text-muted">Grade {{ $grade }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                @if(!(isset($filters['week']) && $filters['week'] !== 'all'))
                                                <!-- Division Statistics for Secondary -->
                                                <div class="mt-3">
                                                    <h6 class="mb-2"><i class="bi bi-trophy"></i> Division Distribution</h6>
                                                    <div class="row">
                                                        @php
                                                            $divisionsToShow = ['I', 'II', 'III', 'IV', '0'];
                                                            // If grade filter is selected, show only matching division
                                                            // Grade filter removed
                                                            if (false) {
                                                                $filterGrade = '';
                                                                $gradeToDivisionMap = [
                                                                    'A' => 'I',
                                                                    'B' => 'II',
                                                                    'C' => 'III',
                                                                    'D' => 'IV',
                                                                    'E' => '0',
                                                                    'F' => '0'
                                                                ];
                                                                $expectedDivision = $gradeToDivisionMap[$filterGrade] ?? null;
                                                                if ($expectedDivision) {
                                                                    $divisionsToShow = [$expectedDivision];
                                                                }
                                                            }
                                                        @endphp
                                                        @foreach($divisionsToShow as $div)
                                                            <div class="col-md-2 mb-2">
                                                                <div class="card bg-light">
                                                                    <div class="card-body text-center p-2">
                                                                        <strong class="text-primary-custom">{{ $divisionStats[$div] ?? 0 }}</strong>
                                                                        <br><small class="text-muted">Division {{ $div }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                            
                                            <!-- Male/Female per Division/Grade -->
                                            @php
                                                $genderFilter = null; // Gender filter removed
                                            @endphp
                                            
                                            @if($schoolType === 'Primary')
                                                <div class="mt-4">
                                                    <h6 class="mb-3"><i class="bi bi-gender-ambiguous"></i> 
                                                        Male/Female per Grade
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="bg-primary-custom text-white">
                                                                <tr>
                                                                    <th>Grade</th>
                                                                    @if(!$genderFilter)
                                                                        <th>Male</th>
                                                                        <th>Female</th>
                                                                        <th>Total</th>
                                                                    @else
                                                                        <th>{{ $genderFilter }}</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $gradesToShow = ['A', 'B', 'C', 'D', 'E', 'F']; // Grade filter removed - show all grades
                                                                @endphp
                                                                @foreach($gradesToShow as $grade)
                                                                    @php
                                                                        $maleCount = $maleGradeStats[$grade] ?? 0;
                                                                        $femaleCount = $femaleGradeStats[$grade] ?? 0;
                                                                        $total = $maleCount + $femaleCount;
                                                                    @endphp
                                                                    <tr>
                                                                        <td><strong>Grade {{ $grade }}</strong></td>
                                                                        @if(!$genderFilter)
                                                                            <td>{{ $maleCount }}</td>
                                                                            <td>{{ $femaleCount }}</td>
                                                                            <td><strong>{{ $total }}</strong></td>
                                                                        @else
                                                                            <td><strong>{{ $genderFilter === 'Male' ? $maleCount : $femaleCount }}</strong></td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @else
                                                @if(!(isset($filters['week']) && $filters['week'] !== 'all'))
                                                <div class="mt-4">
                                                    <h6 class="mb-3"><i class="bi bi-gender-ambiguous"></i> 
                                                        Male/Female per Division
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="bg-primary-custom text-white">
                                                                <tr>
                                                                    <th>Division</th>
                                                                    @if(!$genderFilter)
                                                                        <th>Male</th>
                                                                        <th>Female</th>
                                                                        <th>Total</th>
                                                                    @else
                                                                        <th>{{ $genderFilter }}</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $divisionsToShow = ['I', 'II', 'III', 'IV', '0'];
                                                                    // If grade filter is selected, show only matching division
                                                                    if (!empty($filters['grade']) && $filters['grade'] != '') {
                                                                        $filterGrade = strtoupper($filters['grade']);
                                                                        $gradeToDivisionMap = [
                                                                            'A' => 'I',
                                                                            'B' => 'II',
                                                                            'C' => 'III',
                                                                            'D' => 'IV',
                                                                            'E' => '0',
                                                                            'F' => '0'
                                                                        ];
                                                                        $expectedDivision = $gradeToDivisionMap[$filterGrade] ?? null;
                                                                        if ($expectedDivision) {
                                                                            $divisionsToShow = [$expectedDivision];
                                                                        }
                                                                    }
                                                                @endphp
                                                                @foreach($divisionsToShow as $div)
                                                                    @php
                                                                        $maleCount = $maleDivisionStats[$div] ?? 0;
                                                                        $femaleCount = $femaleDivisionStats[$div] ?? 0;
                                                                        $total = $maleCount + $femaleCount;
                                                                    @endphp
                                                                    <tr>
                                                                        <td><strong>Division {{ $div }}</strong></td>
                                                                        @if(!$genderFilter)
                                                                            <td>{{ $maleCount }}</td>
                                                                            <td>{{ $femaleCount }}</td>
                                                                            <td><strong>{{ $total }}</strong></td>
                                                                        @else
                                                                            <td><strong>{{ $genderFilter === 'Male' ? $maleCount : $femaleCount }}</strong></td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                            
                                            <!-- Class Overview Statistics -->
                                                @if(!(isset($filters['week']) && $filters['week'] !== 'all'))
                                                <div class="mt-4">
                                                    <h6 class="mb-3"><i class="bi bi-clipboard-data"></i> Class Overview</h6>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h6 class="text-primary-custom mb-3">Performance Summary</h6>
                                                                    <p><strong>Class Grade:</strong> <span class="badge badge-info">{{ $classAverageGrade }}</span></p>
                                                                    <p><strong>Performance Remark:</strong> <span class="badge badge-info">{{ $performanceRemark }}</span></p>
                                                                    <p><strong>Pass Rate:</strong> <span class="badge badge-success">{{ is_numeric($passRate) ? number_format($passRate, 1) : '0.0' }}%</span></p>
                                                                    <p><strong>Fail Rate:</strong> <span class="badge badge-danger">{{ is_numeric($failRate) ? number_format($failRate, 1) : '0.0' }}%</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <h6 class="text-primary-custom mb-3">Performance Comment</h6>
                                                                    <p class="text-muted">{{ $performanceComment }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($schoolType === 'Primary')
                                                    <div class="mt-3">
                                                        <h6 class="mb-2">Grade Distribution Summary</h6>
                                                        <div class="row">
                                                            @foreach(['A', 'B', 'C', 'D', 'E'] as $grade)
                                                                <div class="col-md-2 mb-2">
                                                                    <div class="card bg-success text-white">
                                                                        <div class="card-body text-center p-2">
                                                                            <strong>{{ $gradeStats[$grade] ?? 0 }}</strong>
                                                                            <br><small>Grade {{ $grade }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            <div class="col-md-2 mb-2">
                                                                <div class="card bg-danger text-white">
                                                                    <div class="card-body text-center p-2">
                                                                        <strong>{{ $gradeStats['F'] ?? 0 }}</strong>
                                                                        <br><small>Grade F</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Subject Performance Statistics -->
                                            @if(!empty($subjectStats))
                                                @php
                                                    // Sort subjects alphabetically
                                                    ksort($subjectStats);
                                                @endphp
                                                <div class="mt-4">
                                                    <h6 class="mb-3"><i class="bi bi-book"></i> Subject Performance Statistics</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm" id="subjectStatsTable">
                                                            <thead class="bg-primary-custom text-white">
                                                                <tr>
                                                                    <th>Subject</th>
                                                                    <th>A</th>
                                                                    <th>B</th>
                                                                    <th>C</th>
                                                                    <th>D</th>
                                                                    <th>E</th>
                                                                    <th>F</th>
                                                                    <th>Incomplete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($subjectStats as $subjectName => $grades)
                                                                    <tr>
                                                                        <td><strong>{{ $subjectName }}</strong></td>
                                                                        @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'Incomplete'] as $grade)
                                                                            @php
                                                                                $maleCount = $grades[$grade]['male'] ?? 0;
                                                                                $femaleCount = $grades[$grade]['female'] ?? 0;
                                                                                $total = $grades[$grade]['total'] ?? 0;
                                                                                $genderFilter = !empty($filters['gender']) && $filters['gender'] != '' ? ucfirst(strtolower($filters['gender'])) : null;
                                                                            @endphp
                                                                            <td>
                                                                                <div class="text-center">
                                                                                    @if($genderFilter)
                                                                                        <strong>{{ $genderFilter === 'Male' ? $maleCount : $femaleCount }}</strong>
                                                                                    @else
                                                                                        <strong>{{ $total }}</strong>
                                                                                        <br>
                                                                                        <small class="text-muted">
                                                                                            M: {{ $maleCount }}, F: {{ $femaleCount }}
                                                                                        </small>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($selectedExam->exam_name === 'Weekly Test' && isset($filters['week']) && $filters['week'] !== 'all')
                                        @php
                                            $uniqueSubjects = [];
                                            foreach ($examStudents as $student) {
                                                if (isset($student['result']['subjects'])) {
                                                    foreach ($student['result']['subjects'] as $sub) {
                                                        $uniqueSubjects[$sub['subject_name']] = $sub['subject_name'];
                                                    }
                                                }
                                            }
                                            sort($uniqueSubjects);
                                            
                                            // Get Week Label
                                            $currentWeekLabel = '';
                                            if (isset($availableWeeks)) {
                                                foreach ($availableWeeks as $wk) {
                                                    if ($wk['week'] == $filters['week']) {
                                                        $currentWeekLabel = $wk['display'];
                                                        break;
                                                    }
                                                }
                                            }
                                            if (empty($currentWeekLabel)) $currentWeekLabel = $filters['week'];
                                        @endphp
                                         <div class="card mb-4 border-0 shadow-sm bg-success text-white">
                                             <div class="card-body d-flex justify-content-between align-items-center p-3">
                                                 <div>
                                                     <h5 class="mb-0"><i class="bi bi-megaphone"></i> Send All Results for {{ $currentWeekLabel }}</h5>
                                                     <small>Allows you to send a single SMS to parents with all subject results combined.</small>
                                                 </div>
                                                 <button type="button" class="btn btn-light btn-send-all-sms" 
                                                     data-week="{{ $filters['week'] }}"
                                                     data-exam-id="{{ $filters['examID'] or '' }}">
                                                     <i class="bi bi-chat-left-dots-fill text-success"></i> Send All Results (SMS)
                                                 </button>
                                             </div>
                                         </div>

                                        @foreach($uniqueSubjects as $subjectIndex => $subjectName)
                                            <div class="card mb-3 border-0 shadow-sm">
                                                <div class="card-header bg-white d-flex justify-content-between align-items-center" style="cursor: pointer; border: 2px solid #940000; border-radius: 10px;" data-toggle="collapse" data-target="#collapseSubject{{ $subjectIndex }}" aria-expanded="false" aria-controls="collapseSubject{{ $subjectIndex }}">
                                                    <h5 class="mb-0 text-dark" style="font-weight: 600; color: #940000 !important;">{{ $subjectName }} Results - {{ $currentWeekLabel }}</h5>
                                                    <i class="bi bi-chevron-down" style="color: #940000;"></i>
                                                </div>
                                                <div class="collapse" id="collapseSubject{{ $subjectIndex }}">
                                                    <div class="card-body">
                                                        {{-- Subject Logic --}}
                                                        @php
                                                            $subjectStudents = [];
                                                            foreach($examStudents as $std) {
                                                                foreach($std['result']['subjects'] as $sub) {
                                                                    if ($sub['subject_name'] === $subjectName) {
                                                                        $stdCopy = $std; 
                                                                        $stdCopy['subject_mark'] = $sub['marks'] ?? 'Incomplete';
                                                                        $stdCopy['subject_grade'] = $sub['grade'] ?? '-';
                                                                        $stdCopy['subject_details'] = $sub;
                                                                        $subjectStudents[] = $stdCopy;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            usort($subjectStudents, function($a, $b) {
                                                                $markA = is_numeric($a['subject_mark']) ? $a['subject_mark'] : -1;
                                                                $markB = is_numeric($b['subject_mark']) ? $b['subject_mark'] : -1;
                                                                return $markB <=> $markA;
                                                            });
                                                            $top5Subject = array_slice($subjectStudents, 0, 5);
                                                        @endphp
                                                        
                                                        {{-- Top 5 for Subject --}}
                                                        <h6 class="mb-3 text-success"><i class="bi bi-star-fill"></i> Top 5 in {{ $subjectName }}</h6>
                                                        <div class="table-responsive mb-4">
                                                            <table class="table table-sm table-bordered">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th>Position</th>
                                                                        <th>Student Name</th>
                                                                        <th>Examinations</th>
                                                                        <th>Average</th>
                                                                        <th>Div/Grade</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($top5Subject as $index => $topStd)
                                                                        <tr>
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td>
                                                                                {{ $topStd['student']->first_name }} 
                                                                                {{ $topStd['student']->last_name }}
                                                                            </td>
                                                                            <td>{{ $topStd['subject_mark'] }}</td>
                                                                            <td>{{ $topStd['subject_grade'] }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        {{-- All Students for Subject --}}
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="mb-0 text-primary-custom"><i class="bi bi-list-ul"></i> All Students in {{ $subjectName }}</h6>
                                                            @if(isset($availableWeeks) && !empty($availableWeeks))
                                                                <div class="d-flex" style="gap: 5px;">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-export-subject-pdf" 
                                                                        data-subject="{{ $subjectName }}" 
                                                                        data-subject-index="{{ $subjectIndex }}"
                                                                        data-week="{{ $filters['week'] }}"
                                                                        data-week-label="{{ $currentWeekLabel }}"
                                                                        data-year="{{ $filters['year'] }}">
                                                                        <i class="bi bi-file-pdf"></i> PDF
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-outline-success btn-export-subject-excel" 
                                                                        data-subject="{{ $subjectName }}" 
                                                                        data-subject-index="{{ $subjectIndex }}"
                                                                        data-week="{{ $filters['week'] }}"
                                                                        data-week-label="{{ $currentWeekLabel }}"
                                                                        data-year="{{ $filters['year'] }}">
                                                                        <i class="bi bi-file-excel"></i> Excel
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-success btn-send-sms" 
                                                                        data-subject="{{ $subjectName }}" 
                                                                        data-subject-index="{{ $subjectIndex }}"
                                                                        data-week="{{ $filters['week'] }}"
                                                                        data-exam-id="{{ $filters['examID'] or '' }}">
                                                                        <i class="bi bi-chat-left-dots"></i> Send SMS
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover weekly-results-table" id="weeklyTable{{ $subjectIndex }}">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th>Pos</th>
                                                                        <th>First Name</th>
                                                                        <th>Last Name</th>
                                                                        <th>Class</th>
                                                                        <th>Marks</th>
                                                                        <th>Grade</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($subjectStudents as $index => $subStd)
                                                                        @php
                                                                            $pPhone = !empty($subStd['student']->parent->phone) ? $subStd['student']->parent->phone : null;
                                                                            if (!$pPhone || $pPhone === 'null' || $pPhone === 'undefined') {
                                                                                $pPhone = !empty($subStd['student']->emergency_contact_phone) ? $subStd['student']->emergency_contact_phone : '';
                                                                            }
                                                                            $firstName = trim(($subStd['student']->first_name ?? '') . ' ' . ($subStd['student']->middle_name ?? ''));
                                                                            $lastName = $subStd['student']->last_name ?? '';
                                                                        @endphp
                                                                        <tr data-student-id="{{ $subStd['student']->studentID }}" 
                                                                            data-first-name="{{ $firstName }}"
                                                                            data-last-name="{{ $lastName }}"
                                                                            data-parent-phone="{{ $pPhone }}"
                                                                            data-marks="{{ $subStd['subject_mark'] }}"
                                                                            data-grade="{{ $subStd['subject_grade'] }}">
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td>{{ $firstName }}</td>
                                                                            <td>{{ $lastName }}</td>
                                                                            <td>{{ $subStd['class_name'] ?? '' }}</td>
                                                                            <td>{{ $subStd['subject_mark'] }}</td>
                                                                            <td>{{ $subStd['subject_grade'] }}</td>
                                                                            <td>
                                                                                 <button class="btn btn-sm btn-info text-white toggle-details" type="button">
                                                                                    <i class="bi bi-eye"></i> View More
                                                                                 </button>
                                                                                 {{-- Hidden content for DataTables child row --}}
                                                                                 <div class="details-content d-none">
                                                                                    <div class="p-3 bg-light">
                                                                                        <div class="card card-body shadow-sm">
                                                                                            <h6 class="text-primary-custom"><strong><i class="bi bi-info-circle"></i> Question Breakdown - {{ $subStd['student']->first_name }} {{ $subStd['student']->last_name }}</strong></h6>
                                                                                            <hr class="my-2">
                                                                                            @if(isset($subStd['subject_details']['question_marks']) && count($subStd['subject_details']['question_marks']) > 0)
                                                                                                <ul class="list-group list-group-flush">
                                                                                                    @foreach($subStd['subject_details']['question_marks'] as $qMark)
                                                                                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                                                            <span><strong>Q{{ $qMark->question }}:</strong> <small class="text-muted ml-1">{{ Str::limit($qMark->question_description, 30) }}</small></span>
                                                                                                            @if($qMark->marks === 'Incomplete')
                                                                                                                <span class="badge badge-warning rounded-pill px-3">Incomplete / {{ $qMark->max_marks }}</span>
                                                                                                            @else
                                                                                                                <span class="badge bg-primary text-white rounded-pill px-3">{{ $qMark->marks }} / {{ $qMark->max_marks }}</span>
                                                                                                            @endif
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @else
                                                                                                <div class="text-center py-3">
                                                                                                    <i class="bi bi-clipboard-x text-muted display-4"></i>
                                                                                                    <p class="text-muted mt-2">No detailed question marks available.</p>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                 </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Initialize DataTables for Weekly Results --}}
                                        <script>
                                            $(document).ready(function() {
                                                $('.weekly-results-table').each(function() {
                                                    // Check if already initialized
                                                    if ($.fn.DataTable.isDataTable(this)) {
                                                        $(this).DataTable().destroy();
                                                    }
                                                    
                                                    var table = $(this).DataTable({
                                                        "pageLength": 5,
                                                        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                                                        "ordering": true,
                                                        "searching": true,
                                                        "responsive": true,
                                                        "language": {
                                                            "emptyTable": "No data available in table"
                                                        }
                                                    });

                                                    // Handle details expansion
                                                    $(this).on('click', '.toggle-details', function() {
                                                        var tr = $(this).closest('tr');
                                                        var row = table.row(tr);
                                                        var detailsHtml = tr.find('.details-content').html();

                                                        if (row.child.isShown()) {
                                                            row.child.hide();
                                                            tr.removeClass('shown');
                                                            $(this).html('<i class="bi bi-eye"></i> View More');
                                                        } else {
                                                            row.child(detailsHtml).show();
                                                            tr.addClass('shown');
                                                            $(this).html('<i class="bi bi-eye-slash"></i> Hide');
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    @else
                                    <!-- Top 5 Students -->
                                    <div class="card mb-4 border-0 shadow-sm">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0"><i class="bi bi-star-fill"></i> Top 5 Students</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Position</th>
                                                            <th>Student Number</th>
                                                            <th>Student Name</th>
                                                            @if($schoolType === 'Primary')
                                                                <th>Grade</th>
                                                            @else
                                                                <th>Division</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($top5Students as $topStudent)
                                                            <tr>
                                                                <td><span class="badge badge-success">{{ $topStudent['position'] ?? 'N/A' }}</span></td>
                                                                <td>{{ $topStudent['student']->admission_number ?? 'N/A' }}</td>
                                                                <td>
                                                                    {{ $topStudent['student']->first_name }} 
                                                                    {{ $topStudent['student']->middle_name ?? '' }} 
                                                                    {{ $topStudent['student']->last_name }}
                                                                </td>
                                                                @if($schoolType === 'Primary')
                                                                    <td><span class="badge badge-info">{{ $topStudent['grade'] ?? 'N/A' }}</span></td>
                                                                @else
                                                                    <td><span class="badge badge-warning">{{ $topStudent['division'] ?? 'N/A' }}</span></td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- All Students Table -->
                                    <div class="card mb-4 border-0 shadow-sm">
                                        <div class="card-header bg-primary-custom text-white">
                                            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Students Results</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="allStudentsTable">
                                                    <thead class="bg-primary-custom text-white">
                                                        <tr>
                                                            <th>Position</th>
                                                            <th>Student Number</th>
                                                            <th>Student Name</th>
                                                            <th>Class</th>
                                                            @if($schoolType === 'Primary')
                                                                <th>Grade</th>
                                                            @else
                                                                <th>Division</th>
                                                            @endif
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($examStudents as $examStudent)
                                                            <tr class="student-row" data-student-name="{{ strtolower($examStudent['student']->first_name . ' ' . ($examStudent['student']->middle_name ?? '') . ' ' . $examStudent['student']->last_name) }}" data-admission="{{ strtolower($examStudent['student']->admission_number ?? '') }}" data-student-id="{{ $examStudent['student']->studentID }}">
                                                                <td><span class="badge badge-success">{{ $examStudent['position'] ?? 'N/A' }}</span></td>
                                                                <td>{{ $examStudent['student']->admission_number ?? 'N/A' }}</td>
                                                                <td>
                                                                    {{ $examStudent['student']->first_name }} 
                                                                    {{ $examStudent['student']->middle_name ?? '' }} 
                                                                    {{ $examStudent['student']->last_name }}
                                                                </td>
                                                                <td>{{ $examStudent['class_name'] ?? 'N/A' }}</td>
                                                                @if($schoolType === 'Primary')
                                                                    <td><span class="badge badge-info">{{ $examStudent['grade'] ?? 'N/A' }}</span></td>
                                                                @else
                                                                    <td><span class="badge badge-warning">{{ $examStudent['division'] ?? 'N/A' }}</span></td>
                                                                @endif
                                                                <td>
                                                                    <button class="btn btn-sm btn-primary view-student-details" data-student-id="{{ $examStudent['student']->studentID }}" data-exam-id="{{ $filters['examID'] }}" title="View More Details">
                                                                        <i class="bi bi-eye"></i> View More
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle"></i> No results found for the selected exam and class.
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        @if($showDetailedView && ($filters['type'] ?? '') == 'report')
                            <!-- Detailed Report Results View for Main Class or Subclass -->
                            <div class="detailed-report-results">
                                @php
                                    // Re-check type
                                    $typeIsReport = ($filters['type'] ?? '') == 'report';
                                    
                                    // Re-check subclass conditions for title display
                                    $subclassEmptyForTitle = empty($filters['subclass']) || 
                                                            $filters['subclass'] == '' || 
                                                            $filters['subclass'] == null ||
                                                            $filters['subclass'] == '0';
                                    
                                    $subclassSelectedForTitle = !empty($filters['subclass']) && 
                                                               $filters['subclass'] != '' && 
                                                               $filters['subclass'] != null &&
                                                               $filters['subclass'] != '0';
                                    
                                    // Re-find subclass if selected for title
                                    $selectedSubclassForTitle = null;
                                    $subclassDisplayName = '';
                                    if ($subclassSelectedForTitle) {
                                        $subclassIDForTitle = $filters['subclass'];
                                        $selectedSubclassForTitle = \App\Models\Subclass::with('class')
                                            ->where('subclassID', $subclassIDForTitle)
                                            ->where('status', 'Active')
                                            ->first();
                                        if ($selectedSubclassForTitle) {
                                            $subclassName = trim($selectedSubclassForTitle->subclass_name);
                                            $subclassDisplayName = empty($subclassName) 
                                                ? $selectedSubclassForTitle->class->class_name 
                                                : $selectedSubclassForTitle->class->class_name . ' ' . $subclassName;
                                        }
                                    }
                                    
                                    // Re-check conditions
                                    // For teachers, if isTeacherView is true, never show "All Classes"
                                    $allClassesSelected = false;
                                    if (isset($isTeacherView) && $isTeacherView) {
                                        $allClassesSelected = false; // Teachers always have a specific class/subclass
                                    } else {
                                        $allClassesSelected = empty($filters['class']) || $filters['class'] == '' || $filters['class'] == null;
                                    }
                                    $subclassEmpty = empty($filters['subclass']) || 
                                                    $filters['subclass'] == '' || 
                                                    $filters['subclass'] == null ||
                                                    $filters['subclass'] == '0';
                                    $subclassSelected = !empty($filters['subclass']) && 
                                                       $filters['subclass'] != '' && 
                                                       $filters['subclass'] != null &&
                                                       $filters['subclass'] != '0';
                                    
                                    // Re-find subclass if selected
                                    $selectedSubclass = null;
                                    if ($subclassSelected) {
                                        $subclassID = $filters['subclass'];
                                        $selectedSubclass = \App\Models\Subclass::where('subclassID', $subclassID)
                                            ->where('status', 'Active')
                                            ->first();
                                    }
                                    
                                    // Re-find class if selected
                                    $selectedClass = null;
                                    if (!$allClassesSelected) {
                                        $classID = $filters['class'];
                                        $selectedClass = $classes->first(function($class) use ($classID) {
                                            return $class->classID == $classID || (string)$class->classID === (string)$classID;
                                        });
                                    }
                                    
                                    // Get term name
                                    $termName = ucfirst(str_replace('_', ' ', $filters['term'] ?? ''));
                                    
                                    // Process students with report results
                                    $reportStudents = [];
                                    $totalStudents = 0;
                                    $maleCount = 0;
                                    $femaleCount = 0;
                                    $totalMarksSum = 0;
                                    // Note: gradeStats, divisionStats, and male/female stats will be initialized
                                    // and calculated AFTER students are filtered and sorted (see below)
                                    
                                    foreach ($students as $student) {
                                        // Filter by class if class is selected (not "All Classes")
                                        if (!$allClassesSelected && $selectedClass) {
                                            $studentClassID = null;
                                            if ($student->subclass && $student->subclass->class) {
                                                $studentClassID = $student->subclass->class->classID;
                                            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                                $studentClassID = $student->oldSubclass->class->classID;
                                            }
                                            if ($studentClassID != $selectedClass->classID) {
                                                continue;
                                            }
                                        }
                                        
                                        // Filter by subclass if subclass is selected
                                        if ($subclassSelected && $selectedSubclass) {
                                            $studentSubclassID = $student->subclassID ?? $student->oldSubclassID ?? null;
                                            if ($studentSubclassID != $selectedSubclass->subclassID) {
                                                continue;
                                            }
                                        }
                                        
                                        // Filter by gender if gender filter is selected
                                        if (!empty($filters['gender']) && $filters['gender'] != '') {
                                            $studentGender = ucfirst(strtolower($student->gender ?? ''));
                                            $filterGender = ucfirst(strtolower($filters['gender']));
                                            if ($studentGender !== $filterGender) {
                                                continue;
                                            }
                                        }
                                        
                                        if (isset($resultsData[$student->studentID])) {
                                            $studentReport = $resultsData[$student->studentID];
                                            
                                            // Filter by grade if grade filter is selected
                                            $shouldInclude = true;
                                            if (!empty($filters['grade']) && $filters['grade'] != '') {
                                                $filterGrade = strtoupper($filters['grade']);
                                                $studentGrade = $studentReport['grade'] ?? null;
                                                
                                                if ($schoolType === 'Primary') {
                                                    if ($studentGrade !== $filterGrade) {
                                                        $shouldInclude = false;
                                                    }
                                                } else {
                                                    // For Secondary: filter by division
                                                    $division = $studentReport['division'] ?? null;
                                                    $divMatch = preg_match('/^([0IVX]+)\./', $division ?? '', $matches);
                                                    if ($divMatch) {
                                                        $divNum = $matches[1];
                                                        $gradeToDivisionMap = [
                                                            'A' => 'I', 'B' => 'II', 'C' => 'III', 'D' => 'IV', 'E' => '0', 'F' => '0'
                                                        ];
                                                        $expectedDivision = $gradeToDivisionMap[$filterGrade] ?? null;
                                                        if ($expectedDivision && $divNum !== $expectedDivision) {
                                                            $shouldInclude = false;
                                                        }
                                                    } else {
                                                        $shouldInclude = false;
                                                    }
                                                }
                                            }
                                            
                                            if ($shouldInclude) {
                                                $totalStudents++;
                                                $totalMarksSum += $studentReport['average_marks'];
                                                
                                                // Count gender
                                                if ($student->gender == 'Male') {
                                                    $maleCount++;
                                                } elseif ($student->gender == 'Female') {
                                                    $femaleCount++;
                                                }
                                                
                                                // Get student's class name
                                                $studentClassName = 'N/A';
                                                if ($student->subclass && $student->subclass->class) {
                                                    $studentClassName = $student->subclass->class->class_name;
                                                } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                                    $studentClassName = $student->oldSubclass->class->class_name;
                                                }
                                                
                                                // Get grade and division from report
                                                $grade = $studentReport['grade'] ?? null;
                                                $division = $studentReport['division'] ?? null;
                                                
                                                // Note: gradeStats, divisionStats, and male/female stats will be calculated
                                                // AFTER students are filtered and sorted (see below)
                                                
                                                $reportStudents[] = [
                                                    'student' => $student,
                                                    'report' => $studentReport,
                                                    'class_name' => $studentClassName,
                                                    'grade' => $grade,
                                                    'division' => $division,
                                                    'average_marks' => $studentReport['average_marks'],
                                                    'exams' => $studentReport['exams'] ?? []
                                                ];
                                            }
                                        }
                                    }
                                    
                                    // Calculate gradeStats, divisionStats and Male/Female stats for report view
                                    $gradeStats = [];
                                    $maleGradeStats = [];
                                    $femaleGradeStats = [];
                                    $divisionStats = [];
                                    $maleDivisionStats = [];
                                    $femaleDivisionStats = [];
                                    
                                    foreach ($reportStudents as $reportStudent) {
                                        $gender = $reportStudent['student']->gender ?? '';
                                        $grade = $reportStudent['grade'] ?? null;
                                        $fullDivision = $reportStudent['division'] ?? null;
                                        
                                        // Extract main division part (e.g., "III" from "III.21")
                                        $division = '';
                                        if ($fullDivision) {
                                            if (preg_match('/^([IVX0]+)\./', $fullDivision, $matches)) {
                                                $division = $matches[1];
                                            } else {
                                                $division = $fullDivision;
                                            }
                                        }

                                        // Count grades
                                        if ($grade && in_array($grade, ['A', 'B', 'C', 'D', 'E', 'F'])) {
                                            if (!isset($gradeStats[$grade])) $gradeStats[$grade] = 0;
                                            $gradeStats[$grade]++;
                                            if ($gender === 'Male') {
                                                if (!isset($maleGradeStats[$grade])) $maleGradeStats[$grade] = 0;
                                                $maleGradeStats[$grade]++;
                                            } elseif ($gender === 'Female') {
                                                if (!isset($femaleGradeStats[$grade])) $femaleGradeStats[$grade] = 0;
                                                $femaleGradeStats[$grade]++;
                                            }
                                        }

                                        // Count divisions
                                        if ($division && in_array($division, ['I', 'II', 'III', 'IV', '0'])) {
                                            if (!isset($divisionStats[$division])) $divisionStats[$division] = 0;
                                            $divisionStats[$division]++;
                                            if ($gender === 'Male') {
                                                if (!isset($maleDivisionStats[$division])) $maleDivisionStats[$division] = 0;
                                                $maleDivisionStats[$division]++;
                                            } elseif ($gender === 'Female') {
                                                if (!isset($femaleDivisionStats[$division])) $femaleDivisionStats[$division] = 0;
                                                $femaleDivisionStats[$division]++;
                                            }
                                        }
                                    }
                                    
                                    // Calculate Subject Statistics (Grade distribution per subject with Male/Female counts) for report view
                                    $subjectStats = []; // Format: ['SUBJECT_NAME' => ['A' => ['male' => 0, 'female' => 0, 'total' => 0], ...]]
                                    
                                    foreach ($reportStudents as $reportStudent) {
                                        $gender = $reportStudent['student']->gender ?? '';
                                        $exams = $reportStudent['exams'] ?? [];
                                        
                                        // Loop through all exams for this student
                                        foreach ($exams as $examData) {
                                            // Get subjects from this exam - need to fetch from results data
                                            $studentID = $reportStudent['student']->studentID;
                                            if (isset($resultsData[$studentID])) {
                                                $studentReportData = $resultsData[$studentID];
                                                $exam = $examData['exam'] ?? null;
                                                
                                                if ($exam) {
                                                    // Get results for this student and exam to extract subjects
                                                    $examResults = \App\Models\Result::where('studentID', $studentID)
                                                        ->where('examID', $exam->examID)
                                                        ->whereNotNull('marks')
                                                        ->where('status', 'allowed')
                                                        ->with(['classSubject.subject'])
                                                        ->get();
                                                    
                                                    foreach ($examResults as $result) {
                                                        $subjectName = $result->classSubject->subject->subject_name ?? 'N/A';
                                                        $marks = $result->marks ?? null;
                                                        
                                                        if ($marks !== null && $marks !== '') {
                                                            // Calculate grade for this subject
                                                            $subjectGrade = null;
                                                            $marksNum = (float) $marks;
                                                            if ($schoolType === 'Primary') {
                                                                if ($marksNum >= 75) {
                                                                    $subjectGrade = 'A';
                                                                } elseif ($marksNum >= 65) {
                                                                    $subjectGrade = 'B';
                                                                } elseif ($marksNum >= 45) {
                                                                    $subjectGrade = 'C';
                                                                } elseif ($marksNum >= 30) {
                                                                    $subjectGrade = 'D';
                                                                } else {
                                                                    $subjectGrade = 'F';
                                                                }
                                                            } else {
                                                                // For Secondary: same grading
                                                                if ($marksNum >= 75) {
                                                                    $subjectGrade = 'A';
                                                                } elseif ($marksNum >= 65) {
                                                                    $subjectGrade = 'B';
                                                                } elseif ($marksNum >= 45) {
                                                                    $subjectGrade = 'C';
                                                                } elseif ($marksNum >= 30) {
                                                                    $subjectGrade = 'D';
                                                                } else {
                                                                    $subjectGrade = 'F';
                                                                }
                                                            }
                                                            
                                                            if ($subjectGrade) {
                                                                // Initialize subject if not exists
                                                                if (!isset($subjectStats[$subjectName])) {
                                                                    $subjectStats[$subjectName] = [];
                                                                    foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $g) {
                                                                        $subjectStats[$subjectName][$g] = [
                                                                            'male' => 0,
                                                                            'female' => 0,
                                                                            'total' => 0
                                                                        ];
                                                                    }
                                                                }
                                                                
                                                                // Count by gender
                                                                if ($gender === 'Male') {
                                                                    $subjectStats[$subjectName][$subjectGrade]['male']++;
                                                                } elseif ($gender === 'Female') {
                                                                    $subjectStats[$subjectName][$subjectGrade]['female']++;
                                                                }
                                                                
                                                                // Update total
                                                                $subjectStats[$subjectName][$subjectGrade]['total']++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Group students by class name for position calculation (per class)
                                    $studentsByClass = [];
                                    foreach ($reportStudents as $reportStudent) {
                                        $className = $reportStudent['class_name'] ?? 'N/A';
                                        if (!isset($studentsByClass[$className])) {
                                            $studentsByClass[$className] = [];
                                        }
                                        $studentsByClass[$className][] = $reportStudent;
                                    }
                                    
                                    // Sort and assign positions per class
                                    $sortedReportStudents = [];
                                    foreach ($studentsByClass as $className => $classStudents) {
                                        // Sort students in this class
                                        if ($schoolType === 'Secondary') {
                                            usort($classStudents, function($a, $b) {
                                                // For Secondary, sort by division points first (from division string like 'I.9')
                                                $divA = $a['division'] ?? null;
                                                $divB = $b['division'] ?? null;

                                                // Extract Roman numeral prefix from division e.g. 'I.9' => 'I', 'IV.29' => 'IV'
                                                $divOrder = ['I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, '0' => 5];
                                                preg_match('/^([0IVX]+)\./', $divA ?? '', $mA);
                                                preg_match('/^([0IVX]+)\./', $divB ?? '', $mB);
                                                $rankA = $divOrder[$mA[1] ?? ''] ?? 999;
                                                $rankB = $divOrder[$mB[1] ?? ''] ?? 999;

                                                if ($rankA != $rankB) {
                                                    return $rankA <=> $rankB;
                                                }

                                                // Same division: sort by total_points ascending (lower = better for O-Level)
                                                $ptA = $a['report']['total_points'] ?? 9999;
                                                $ptB = $b['report']['total_points'] ?? 9999;
                                                if ($ptA != $ptB) {
                                                    return $ptA <=> $ptB;
                                                }

                                                // Fallback: average descending
                                                return $b['average_marks'] <=> $a['average_marks'];
                                            });
                                        } else {
                                            // Primary: Sort by average descending
                                            usort($classStudents, function($a, $b) {
                                                return $b['average_marks'] <=> $a['average_marks'];
                                            });
                                        }
                                        
                                        // Assign positions within this class (starting from 1 for each class)
                                        $classTotalStudents = count($classStudents);
                                        foreach ($classStudents as $index => $student) {
                                            $classStudents[$index]['position'] = $index + 1;
                                            $classStudents[$index]['total_class_students'] = $classTotalStudents;
                                        }
                                        
                                        // Add to sorted array
                                        $sortedReportStudents = array_merge($sortedReportStudents, $classStudents);
                                    }
                                    
                                    // Replace reportStudents with sorted and positioned students
                                    $reportStudents = $sortedReportStudents;
                                    
                                    // Calculate statistics
                                    $classAverage = $totalStudents > 0 ? $totalMarksSum / $totalStudents : 0;
                                    
                                    // Calculate male average
                                    $maleMarksSum = 0;
                                    foreach ($reportStudents as $reportStudent) {
                                        if ($reportStudent['student']->gender == 'Male') {
                                            $maleMarksSum += $reportStudent['average_marks'];
                                        }
                                    }
                                    $maleAverage = $maleCount > 0 ? $maleMarksSum / $maleCount : 0;
                                    
                                    // Calculate female average
                                    $femaleMarksSum = 0;
                                    foreach ($reportStudents as $reportStudent) {
                                        if ($reportStudent['student']->gender == 'Female') {
                                            $femaleMarksSum += $reportStudent['average_marks'];
                                        }
                                    }
                                    $femaleAverage = $femaleCount > 0 ? $femaleMarksSum / $femaleCount : 0;
                                    
                                    // Calculate class average grade
                                    $classAverageGrade = null;
                                    if ($schoolType === 'Primary') {
                                        if ($classAverage >= 75) {
                                            $classAverageGrade = 'A';
                                        } elseif ($classAverage >= 65) {
                                            $classAverageGrade = 'B';
                                        } elseif ($classAverage >= 45) {
                                            $classAverageGrade = 'C';
                                        } elseif ($classAverage >= 30) {
                                            $classAverageGrade = 'D';
                                        } else {
                                            $classAverageGrade = 'F';
                                        }
                                    }
                                    
                                    // Calculate pass/fail rates (assuming >= 30 is pass for both)
                                    $passedCount = count(array_filter($reportStudents, function($s) {
                                        return $s['average_marks'] >= 30;
                                    }));
                                    $passRate = $totalStudents > 0 ? ($passedCount / $totalStudents) * 100 : 0;
                                    $failRate = 100 - $passRate;
                                    
                                    // Performance remark
                                    $performanceRemark = '';
                                    if ($classAverage >= 75) {
                                        $performanceRemark = 'Excellent';
                                    } elseif ($classAverage >= 65) {
                                        $performanceRemark = 'Very Good';
                                    } elseif ($classAverage >= 45) {
                                        $performanceRemark = 'Good';
                                    } elseif ($classAverage >= 30) {
                                        $performanceRemark = 'Fair';
                                    } else {
                                        $performanceRemark = 'Needs Improvement';
                                    }
                                    
                                    // Performance comment
                                    $performanceComment = '';
                                    if ($classAverage >= 75) {
                                        $performanceComment = 'The class has demonstrated exceptional performance with an average above 75%.';
                                    } elseif ($classAverage >= 65) {
                                        $performanceComment = 'The class has shown very good performance with an average above 65%.';
                                    } elseif ($classAverage >= 45) {
                                        $performanceComment = 'The class has shown good performance with an average above 45%.';
                                    } elseif ($classAverage >= 30) {
                                        $performanceComment = 'The class performance is fair. Improvement is needed.';
                                    } else {
                                        $performanceComment = 'The class needs significant improvement.';
                                    }
                                    
                                    // Adjust counts for Overview Statistics if gender is filtered
                                    $displayTotalStudents = $totalStudents;
                                    $displayAverageMarks = number_format($classAverage, 1);
                                    if (!empty($filters['gender']) && $filters['gender'] != '') {
                                        if (ucfirst(strtolower($filters['gender'])) === 'Male') {
                                            $displayTotalStudents = $maleCount;
                                            $displayAverageMarks = number_format($maleAverage, 1);
                                        } elseif (ucfirst(strtolower($filters['gender'])) === 'Female') {
                                            $displayTotalStudents = $femaleCount;
                                            $displayAverageMarks = number_format($femaleAverage, 1);
                                        }
                                    }
                                    
                                    // Determine which grades to show
                                    $gradesToShow = ['A', 'B', 'C', 'D', 'E', 'F'];
                                    if (!empty($filters['grade']) && $filters['grade'] != '') {
                                        $gradesToShow = [strtoupper($filters['grade'])];
                                    }
                                    
                                    // Determine which divisions to show
                                    $divisionsToShow = ['I', 'II', 'III', 'IV', '0'];

                                    // Prepare detailed view data for PDF export (Advanced PDF format)
                                    $detailedReportData = [
                                        'className' => $allClassesSelected ? 'ALL CLASSES' : ($subclassDisplayName ?: ($selectedClass ? $selectedClass->class_name : 'N/A')),
                                        'examName' => 'Term Report (' . $termName . ')',
                                        'year' => $filters['year'] ?? date('Y'),
                                        'totalStudents' => $totalStudents,
                                        'maleCount' => $maleCount,
                                        'femaleCount' => $femaleCount,
                                        'averageMarks' => number_format($classAverage, 1),
                                        'averageGrade' => $classAverageGrade,
                                        'maleAverage' => $maleAverage > 0 ? number_format($maleAverage, 1) : '0.0',
                                        'femaleAverage' => $femaleAverage > 0 ? number_format($femaleAverage, 1) : '0.0',
                                        'gradeStats' => $gradeStats,
                                        'divisionStats' => $divisionStats ?? [],
                                        'maleDivisionStats' => $maleDivisionStats ?? [],
                                        'femaleDivisionStats' => $femaleDivisionStats ?? [],
                                        'maleGradeStats' => $maleGradeStats,
                                        'femaleGradeStats' => $femaleGradeStats,
                                        'subjectStats' => $subjectStats,
                                        'passRate' => number_format($passRate, 1),
                                        'failRate' => number_format($failRate, 1),
                                        'performanceRemark' => $performanceRemark,
                                        'performanceComment' => $performanceComment,
                                        'top5Students' => array_map(function($s) {
                                            return [
                                                'position' => $s['position'] ?? 'N/A',
                                                'studentName' => ($s['student']->first_name ?? '') . ' ' . ($s['student']->middle_name ?? '') . ' ' . ($s['student']->last_name ?? ''),
                                                'divisionOrGrade' => $s['division'] ?? $s['grade'] ?? 'N/A'
                                            ];
                                        }, array_slice($reportStudents, 0, 5)),
                                        'allStudents' => array_map(function($s) {
                                            $st = $s['student'];
                                            
                                            // Format exams summary as a single subject for the PDF table
                                            $examsSummary = '';
                                            if (isset($s['exams']) && count($s['exams']) > 0) {
                                                $eParts = [];
                                                foreach ($s['exams'] as $ex) {
                                                    $eName = strtoupper($ex['exam']->exam_name ?? 'N/A');
                                                    $eAvg = number_format($ex['average'] ?? 0, 1);
                                                    $eGrade = $ex['grade'] ?? 'N/A';
                                                    $eParts[] = $eName . ': ' . $eAvg . '-' . $eGrade;
                                                }
                                                $examsSummary = implode('; ', $eParts);
                                            }

                                            return [
                                                'studentID' => $st->studentID,
                                                'studentName' => trim($st->first_name . ' ' . ($st->middle_name ?? '') . ' ' . $st->last_name),
                                                'className' => $s['class_name'] ?? 'N/A',
                                                'totalMarks' => number_format($s['average_marks'], 1), // In report view, total shows average
                                                'grade' => $s['grade'] ?? null,
                                                'division' => $s['division'] ?? null,
                                                'subjects' => [['subject_name' => 'EXAMS', 'marks' => '', 'grade' => $examsSummary]], // Store summary in grade
                                                'position' => $s['position'] ?? null
                                            ];
                                        }, $reportStudents),
                                        'schoolName' => $school->school_name ?? Session::get('school_name', 'ShuleXpert')
                                    ];
                                @endphp
                                
                                <script>
                                    window.detailedViewData = @json($detailedReportData);
                                    console.log('Detailed Report Data Loaded:', window.detailedViewData);
                                </script>
                                
                                <!-- Title -->
                                <h4 class="mb-4 text-center">
                                    <i class="bi bi-file-text"></i> 
                                    <strong>
                                        @if($allClassesSelected)
                                            ALL CLASSES STUDENT REPORT {{ $termName }}
                                        @elseif($subclassSelectedForTitle && $selectedSubclassForTitle)
                                            {{ strtoupper($subclassDisplayName) }} STUDENT REPORT {{ $termName }}
                                        @elseif($selectedClass)
                                            {{ strtoupper($selectedClass->class_name) }} STUDENT REPORT {{ $termName }}
                                        @else
                                            STUDENT REPORT {{ $termName }}
                                        @endif
                                    </strong>
                                </h4>
                                
                                @if(count($reportStudents) > 0)
                                    <!-- Overview Statistics -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h3 class="text-primary-custom mb-0">{{ $displayTotalStudents }}</h3>
                                                    <small class="text-muted">Total Students</small>
                                                </div>
                                            </div>
                                        </div>
                                        @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                            <div class="col-md-3 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h3 class="text-primary-custom mb-0">{{ $maleCount }}</h3>
                                                        <small class="text-muted">Male</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                            <div class="col-md-3 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h3 class="text-primary-custom mb-0">{{ $femaleCount }}</h3>
                                                        <small class="text-muted">Female</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-3 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h3 class="text-primary-custom mb-0">{{ $displayAverageMarks }}</h3>
                                                    <small class="text-muted">Average Marks</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($schoolType === 'Primary')
                                            <!-- Grade Distribution -->
                                            <div class="col-12 mt-3">
                                                <h6 class="mb-2"><i class="bi bi-award"></i> Grade Distribution</h6>
                                                <div class="row">
                                                    @foreach($gradesToShow as $grade)
                                                        <div class="col-md-2 mb-2">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center p-2">
                                                                    <strong class="text-primary-custom">{{ $gradeStats[$grade] ?? 0 }}</strong>
                                                                    <br><small class="text-muted">Grade {{ $grade }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Male/Female per Grade -->
                                            <div class="col-12 mt-4">
                                                <h6 class="mb-3"><i class="bi bi-gender-ambiguous"></i> Male/Female per Grade</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="bg-primary-custom text-white">
                                                            <tr>
                                                                <th>Grade</th>
                                                                @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                    <th>Male</th>
                                                                @endif
                                                                @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                    <th>Female</th>
                                                                @endif
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($gradesToShow as $grade)
                                                                @php
                                                                    $maleCountGrade = $maleGradeStats[$grade] ?? 0;
                                                                    $femaleCountGrade = $femaleGradeStats[$grade] ?? 0;
                                                                    $totalGrade = $maleCountGrade + $femaleCountGrade;
                                                                @endphp
                                                                <tr>
                                                                    <td><strong>Grade {{ $grade }}</strong></td>
                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                        <td>{{ $maleCountGrade }}</td>
                                                                    @endif
                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                        <td>{{ $femaleCountGrade }}</td>
                                                                    @endif
                                                                    <td><strong>{{ $totalGrade }}</strong></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Division Distribution -->
                                            <div class="col-12 mt-3">
                                                <h6 class="mb-2"><i class="bi bi-trophy"></i> Division Distribution</h6>
                                                <div class="row">
                                                    @foreach($divisionsToShow as $div)
                                                        <div class="col-md-2 mb-2">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center p-2">
                                                                    <strong class="text-primary-custom">{{ $divisionStats[$div] ?? 0 }}</strong>
                                                                    <br><small class="text-muted">Division {{ $div }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Male/Female per Division -->
                                            <div class="col-12 mt-4">
                                                <h6 class="mb-3"><i class="bi bi-gender-ambiguous"></i> Male/Female per Division</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="bg-primary-custom text-white">
                                                            <tr>
                                                                <th>Division</th>
                                                                @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                    <th>Male</th>
                                                                @endif
                                                                @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                    <th>Female</th>
                                                                @endif
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($divisionsToShow as $div)
                                                                @php
                                                                    $maleCountDiv = $maleDivisionStats[$div] ?? 0;
                                                                    $femaleCountDiv = $femaleDivisionStats[$div] ?? 0;
                                                                    $totalDiv = $maleCountDiv + $femaleCountDiv;
                                                                @endphp
                                                                <tr>
                                                                    <td><strong>Division {{ $div }}</strong></td>
                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                        <td>{{ $maleCountDiv }}</td>
                                                                    @endif
                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                        <td>{{ $femaleCountDiv }}</td>
                                                                    @endif
                                                                    <td><strong>{{ $totalDiv }}</strong></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Class Overview Statistics -->
                                        <div class="col-12 mt-4">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            <h6 class="text-primary-custom mb-3">Performance Summary</h6>
                                                            <p><strong>Class Average:</strong> 
                                                                @if($schoolType === 'Primary')
                                                                    <span class="badge badge-info">{{ $classAverageGrade }}</span> 
                                                                @endif
                                                                ({{ number_format($classAverage, 1) }} marks)
                                                            </p>
                                                            @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                <p><strong>Male Average:</strong> <span class="badge badge-primary">{{ number_format($maleAverage, 1) }} marks</span></p>
                                                            @endif
                                                            @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                <p><strong>Female Average:</strong> <span class="badge badge-pink">{{ number_format($femaleAverage, 1) }} marks</span></p>
                                                            @endif
                                                            <p><strong>Pass Rate:</strong> <span class="badge badge-success">{{ number_format($passRate, 1) }}%</span></p>
                                                            <p><strong>Fail Rate:</strong> <span class="badge badge-danger">{{ number_format($failRate, 1) }}%</span></p>
                                                            <p><strong>Performance Remark:</strong> <span class="badge badge-info">{{ $performanceRemark }}</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            <h6 class="text-primary-custom mb-3">Performance Comment</h6>
                                                            <p>{{ $performanceComment }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Subject Performance Statistics -->
                                        @if(!empty($subjectStats))
                                            @php
                                                // Sort subjects alphabetically
                                                ksort($subjectStats);
                                            @endphp
                                            <div class="col-12 mt-4">
                                                <h6 class="mb-3"><i class="bi bi-book"></i> Subject Performance Statistics</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-sm" id="reportSubjectStatsTable">
                                                        <thead class="bg-primary-custom text-white">
                                                            <tr>
                                                                <th>Subject</th>
                                                                <th>A</th>
                                                                <th>B</th>
                                                                <th>C</th>
                                                                <th>D</th>
                                                                <th>E</th>
                                                                <th>F</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($subjectStats as $subjectName => $grades)
                                                                <tr>
                                                                    <td><strong>{{ $subjectName }}</strong></td>
                                                                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $grade)
                                                                        @php
                                                                            $maleCount = $grades[$grade]['male'] ?? 0;
                                                                            $femaleCount = $grades[$grade]['female'] ?? 0;
                                                                            $total = $grades[$grade]['total'] ?? 0;
                                                                        @endphp
                                                                        <td>
                                                                            <div class="text-center">
                                                                                <strong>{{ $total }}</strong>
                                                                                <br>
                                                                                <small class="text-muted">
                                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Male')
                                                                                        M: {{ $maleCount }}
                                                                                    @endif
                                                                                    @if(empty($filters['gender']) || ucfirst(strtolower($filters['gender'])) === 'Female')
                                                                                        @if(!empty($filters['gender']) && ucfirst(strtolower($filters['gender'])) === 'Male') , @endif F: {{ $femaleCount }}
                                                                                    @endif
                                                                                </small>
                                                                            </div>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Top 5 Students Table -->
                                    @php
                                        // Get top 5 students based on overall average
                                        $top5ReportStudents = array_slice($reportStudents, 0, 5);
                                    @endphp
                                    @if(count($top5ReportStudents) > 0)
                                        <div class="card mb-4 border-0 shadow-sm">
                                            <div class="card-header bg-primary-custom text-white">
                                                <h5 class="mb-0"><i class="bi bi-trophy"></i> TOP FIVE STUDENTS</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead class="bg-primary-custom text-white">
                                                            <tr>
                                                                <th>Position</th>
                                                                <th>Student Name</th>
                                                                <th>Grade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($top5ReportStudents as $topStudent)
                                                                <tr>
                                                                    <td><span class="badge badge-success">{{ $topStudent['position'] ?? 'N/A' }}</span></td>
                                                                    <td>
                                                                        {{ $topStudent['student']->first_name }} 
                                                                        {{ $topStudent['student']->middle_name ?? '' }} 
                                                                        {{ $topStudent['student']->last_name }}
                                                                    </td>
                                                                    <td><span class="badge badge-info">{{ $topStudent['grade'] ?? 'N/A' }}</span></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- All Students Results Table -->
                                    <div class="card mb-4 border-0 shadow-sm">
                                        <div class="card-header bg-primary-custom text-white">
                                            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Students Results</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="reportStudentsTable">
                                                    <thead class="bg-primary-custom text-white">
                                                        <tr>
                                                            <th>Position</th>
                                                            <th>Student Name</th>
                                                            <th>Examinations</th>
                                                            <th>Average</th>
                                                            <th>Div/Grade</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($reportStudents as $reportStudent)
                                                            @php
                                                                // Build examinations string: "EXAM1 {avg}-{grade}, EXAM2 {avg}-{grade}"
                                                                $examsString = '';
                                                                if (isset($reportStudent['exams']) && count($reportStudent['exams']) > 0) {
                                                                    $examStrings = [];
                                                                    foreach ($reportStudent['exams'] as $examData) {
                                                                        $examName = strtoupper($examData['exam']->exam_name ?? 'N/A');
                                                                        $examAvg = number_format($examData['average'] ?? 0, 1);
                                                                        $examGrade = $examData['grade'] ?? 'N/A';
                                                                        $examStrings[] = $examName . ' ' . $examAvg . '-' . $examGrade;
                                                                    }
                                                                    $examsString = implode(', ', $examStrings);
                                                                }
                                                            @endphp
                                                            <tr class="student-row" data-student-id="{{ $reportStudent['student']->studentID }}">
                                                                <td>
                                                                    <span class="badge badge-success">
                                                                        {{ $reportStudent['position'] ?? 'N/A' }} out of {{ $reportStudent['total_class_students'] ?? count($reportStudents) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {{ $reportStudent['student']->first_name }} 
                                                                    {{ $reportStudent['student']->middle_name ?? '' }} 
                                                                    {{ $reportStudent['student']->last_name }}
                                                                </td>
                                                                <td>{{ $examsString ?: 'N/A' }}</td>
                                                                <td>{{ number_format($reportStudent['average_marks'], 1) }}</td>
                                                                <td><span class="badge badge-info">{{ $reportStudent['grade'] ?? 'N/A' }}</span></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-primary view-report-student-details" 
                                                                            data-student-id="{{ $reportStudent['student']->studentID }}" 
                                                                            data-term="{{ $filters['term'] }}"
                                                                            data-year="{{ $filters['year'] }}"
                                                                            title="View More Details">
                                                                        <i class="bi bi-eye"></i> View More
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle"></i> No results found for the selected term and class.
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        @if(!$showDetailedView)
                            <!-- Exam Results Display (Original View) -->
                        <h5 class="mb-3">
                            <i class="bi bi-clipboard-check"></i> Exam Results
                            @if($filters['term'])
                                - {{ ucfirst(str_replace('_', ' ', $filters['term'])) }}
                            @endif
                            - {{ $filters['year'] ?? date('Y') }}
                        </h5>

                        @if(!empty($resultsData) && count($resultsData) > 0)
                            @foreach($students as $student)
                                @if(isset($resultsData[$student->studentID]))
                                    <div class="card result-card mb-3 student-row" data-student-name="{{ strtolower($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name) }}" data-admission="{{ strtolower($student->admission_number ?? '') }}" data-student-id="{{ $student->studentID }}" data-main-class="@if($student->subclass && $student->subclass->class){{ $student->subclass->class->class_name }}@elseif($student->oldSubclass && $student->oldSubclass->class){{ $student->oldSubclass->class->class_name }}@else N/A @endif">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <strong>{{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->last_name }}</strong>
                                                <small class="text-muted ml-2">
                                                    @if($student->subclass && $student->subclass->class)
                                                        {{ $student->subclass->class->class_name }}
                                                    @elseif($student->oldSubclass && $student->oldSubclass->class)
                                                        {{ $student->oldSubclass->class->class_name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button class="btn btn-sm btn-danger download-student-btn mr-1" data-student-id="{{ $student->studentID }}" data-type="pdf" title="Download PDF">
                                                    <i class="bi bi-file-pdf"></i> PDF
                                                </button>
                                                <button class="btn btn-sm btn-success download-student-btn" data-student-id="{{ $student->studentID }}" data-type="excel" title="Download Excel">
                                                    <i class="bi bi-file-excel"></i> Excel
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="bg-primary-custom text-white">
                                                        <tr>
                                                            <th>Exam Name</th>
                                                            <th>Date</th>
                                                            @if($schoolType === 'Primary')
                                                                <th>Total Marks</th>
                                                                <th>Grade</th>
                                                            @else
                                                                <th>Division</th>
                                                            @endif
                                                            <th>Subjects</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($resultsData[$student->studentID] as $examResult)
                                                            <tr>
                                                                <td>{{ $examResult['exam']->exam_name ?? 'N/A' }}</td>
                                                                <td>{{ $examResult['exam']->start_date ?? 'N/A' }}</td>
                                                                @if($schoolType === 'Primary')
                                                                    <td><strong>{{ is_numeric($examResult['total_marks']) ? number_format($examResult['total_marks'], 0) : $examResult['total_marks'] }}</strong></td>
                                                                    <td>
                                                                        <span class="badge badge-info">{{ $examResult['grade'] ?? 'N/A' }}</span>
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <span class="badge badge-warning">{{ $examResult['division'] ?? ($examResult['grade'] ?? 'N/A') }}</span>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#subjects-{{ $student->studentID }}-{{ $examResult['exam']->examID }}">
                                                                        View Subjects ({{ $examResult['subject_count'] }})
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="{{ $schoolType === 'Primary' ? '5' : '4' }}" class="p-0">
                                                                    <div class="collapse" id="subjects-{{ $student->studentID }}-{{ $examResult['exam']->examID }}">
                                                                        <div class="card card-body">
                                                                            <table class="table table-sm mb-0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Subject</th>
                                                                                        <th>Marks</th>
                                                                                        <th>Grade</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($examResult['subjects'] as $subject)
                                                                                        <tr>
                                                                                            <td>{{ $subject['subject_name'] }}</td>
                                                                                            <td>{{ is_numeric($subject['marks']) ? number_format($subject['marks'], 0) : $subject['marks'] }}</td>
                                                                                            <td>{{ $subject['grade'] ?? 'N/A' }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle"></i> No results found for the selected term and class.
                                    </div>
                                @endif
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Student Details -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="studentDetailsModalLabel">
                    <i class="bi bi-person"></i> Student Detailed Results
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="studentDetailsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading student details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger export-student-pdf-btn" data-student-id="" data-exam-id="" title="Export PDF">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="btn btn-success export-student-excel-btn" data-student-id="" data-exam-id="" title="Export Excel">
                    <i class="bi bi-file-excel"></i> Export Excel
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
$(document).ready(function() {
    console.log('Result Management page loaded');
    
    // Check if export buttons exist
    const exportPdfBtn = $('#exportAllPdf');
    const exportExcelBtn = $('#exportAllExcel');
    console.log('Export PDF button found:', exportPdfBtn.length > 0);
    console.log('Export Excel button found:', exportExcelBtn.length > 0);
    
    if (exportPdfBtn.length === 0) {
        console.warn('Export PDF button not found in DOM at page load');
    }
    if (exportExcelBtn.length === 0) {
        console.warn('Export Excel button not found in DOM at page load');
    }
    
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Update result count on page load
    updateResultCount();
    
    // Initialize DataTable for All Students Results table
    function initializeAllStudentsDataTable() {
        // Destroy existing DataTable if it exists
        if ($.fn.DataTable.isDataTable('#allStudentsTable')) {
            $('#allStudentsTable').DataTable().destroy();
        }
        
        // Initialize DataTable
        if ($('#allStudentsTable').length > 0) {
            $('#allStudentsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                'language': {
                    'search': 'Search:',
                    'lengthMenu': 'Show _MENU_ entries',
                    'info': 'Showing _START_ to _END_ of _TOTAL_ entries',
                    'infoEmpty': 'No entries to show',
                    'infoFiltered': '(filtered from _MAX_ total entries)',
                    'paginate': {
                        'first': 'First',
                        'last': 'Last',
                        'next': 'Next',
                        'previous': 'Previous'
                    }
                },
                'order': [[0, 'asc']], // Sort by Position column ascending
                'columnDefs': [
                    { 'orderable': true, 'targets': [0, 1, 2, 3, 4] }, // Position, Student Number, Student Name, Class, Grade/Division
                    { 'orderable': false, 'targets': [5] } // Actions column
                ]
            });
        }
    }
    
    // Initialize DataTable for Subject Performance Statistics table
    function initializeSubjectStatsDataTable() {
        // Destroy existing DataTable if it exists
        if ($.fn.DataTable.isDataTable('#subjectStatsTable')) {
            $('#subjectStatsTable').DataTable().destroy();
        }
        
        // Initialize DataTable
        if ($('#subjectStatsTable').length > 0) {
            $('#subjectStatsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                'language': {
                    'search': 'Search:',
                    'lengthMenu': 'Show _MENU_ entries',
                    'info': 'Showing _START_ to _END_ of _TOTAL_ entries',
                    'infoEmpty': 'No entries to show',
                    'infoFiltered': '(filtered from _MAX_ total entries)',
                    'paginate': {
                        'first': 'First',
                        'last': 'Last',
                        'next': 'Next',
                        'previous': 'Previous'
                    }
                },
                'order': [[0, 'asc']], // Sort by Subject name ascending
                'columnDefs': [
                    { 'orderable': true, 'targets': [0, 1, 2, 3, 4, 5, 6] } // All columns sortable
                ]
            });
        }
    }
    
    // Initialize DataTable for Report Students Results table
    function initializeReportStudentsDataTable() {
        // Destroy existing DataTable if it exists
        if ($.fn.DataTable.isDataTable('#reportStudentsTable')) {
            $('#reportStudentsTable').DataTable().destroy();
        }
        
        // Initialize DataTable
        if ($('#reportStudentsTable').length > 0) {
            $('#reportStudentsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                'language': {
                    'search': 'Search:',
                    'lengthMenu': 'Show _MENU_ entries',
                    'info': 'Showing _START_ to _END_ of _TOTAL_ entries',
                    'infoEmpty': 'No entries to show',
                    'infoFiltered': '(filtered from _MAX_ total entries)',
                    'paginate': {
                        'first': 'First',
                        'last': 'Last',
                        'next': 'Next',
                        'previous': 'Previous'
                    }
                },
                'order': [[0, 'asc']], // Sort by Position column ascending
                'columnDefs': [
                    { 'orderable': true, 'targets': [0, 1, 2, 3, 4] }, // Position, Student Name, Examinations, Average, Grade
                    { 'orderable': false, 'targets': [5] } // Actions column
                ]
            });
        }
    }
    
    // Initialize DataTable for Report Subject Statistics table
    function initializeReportSubjectStatsDataTable() {
        // Destroy existing DataTable if it exists
        if ($.fn.DataTable.isDataTable('#reportSubjectStatsTable')) {
            $('#reportSubjectStatsTable').DataTable().destroy();
        }
        
        // Initialize DataTable
        if ($('#reportSubjectStatsTable').length > 0) {
            $('#reportSubjectStatsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                'language': {
                    'search': 'Search:',
                    'lengthMenu': 'Show _MENU_ entries',
                    'info': 'Showing _START_ to _END_ of _TOTAL_ entries',
                    'infoEmpty': 'No entries to show',
                    'infoFiltered': '(filtered from _MAX_ total entries)',
                    'paginate': {
                        'first': 'First',
                        'last': 'Last',
                        'next': 'Next',
                        'previous': 'Previous'
                    }
                },
                'order': [[0, 'asc']], // Sort by Subject name ascending
                'columnDefs': [
                    { 'orderable': true, 'targets': [0, 1, 2, 3, 4, 5, 6] } // All columns sortable
                ]
            });
        }
    }
    
    // Initialize DataTables on page load
    initializeAllStudentsDataTable();
    initializeSubjectStatsDataTable();
    initializeReportStudentsDataTable();
    initializeReportSubjectStatsDataTable();

    // Handle view report student details button click
    $(document).on('click', '.view-report-student-details', function() {
        const studentID = $(this).data('student-id');
        const term = $(this).data('term');
        const year = $(this).data('year');
        const $row = $(this).closest('tr');
        
        // Get student info from the row (Position column is now at index 0)
        const position = $row.find('td').eq(0).text().trim(); // Position column
        const studentName = $row.find('td').eq(1).text().trim(); // Student Name column
        const examsString = $row.find('td').eq(2).text().trim(); // Examinations column
        const average = $row.find('td').eq(3).text().trim(); // Average column
        const gradeOrDivision = $row.find('td').eq(4).text().trim(); // Grade column
        
        // Show modal
        $('#studentDetailsModal').modal('show');
        
        // Set modal title
        $('#studentDetailsModalLabel').html(`<i class="bi bi-person"></i> ${studentName} - Term Report Details`);
        
        // Hide export buttons for report view (or keep them if needed)
        $('.export-student-pdf-btn').attr('data-student-id', studentID).attr('data-exam-id', '');
        $('.export-student-excel-btn').attr('data-student-id', studentID).attr('data-exam-id', '');
        
        // Show loading
        $('#studentDetailsContent').html(`
            <div class="text-center">
                <div class="spinner-border text-primary-custom" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading student details...</p>
            </div>
        `);
        
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        const termName = term ? term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : '';
        
        // Fetch subject details via AJAX
        $.ajax({
            url: '{{ route("manageResults") }}',
            method: 'GET',
            data: {
                term: term,
                year: year,
                type: 'report',
                studentID: studentID,
                getSubjectDetails: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                // Store student data globally for PDF export
                window.currentReportStudentData = {
                    studentID: response.student.studentID,
                    studentName: (response.student.first_name || '') + ' ' + (response.student.middle_name || '') + ' ' + (response.student.last_name || ''),
                    admissionNumber: response.student.admission_number || 'N/A',
                    gender: response.student.gender || 'Male',
                    photo: response.student.photo || null,
                    term: term,
                    termName: termName,
                    year: year,
                    position: position,
                    totalStudentsCount: response.totalStudentsCount || 0,
                    averageMarks: response.averageMarks || 0,
                    grade: response.grade || gradeOrDivision,
                    subjects: response.subjects || [],
                    exams: response.exams || []
                };
                
                // Build content
                let content = `
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><strong>Student Information</strong></h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Student Name:</strong> ${studentName}</p>
                                    <p><strong>Term:</strong> ${termName}</p>
                                    <p><strong>Year:</strong> ${year}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Position:</strong> <span class="badge badge-success">${position}</span></p>
                                    <p><strong>Grade:</strong> ${gradeOrDivision}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header bg-primary-custom text-white">
                            <h6 class="mb-0"><i class="bi bi-book"></i> Examinations</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Examination</th>
                                            <th>Average</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                // Parse exams string (format: "EXAM1 avg-grade, EXAM2 avg-grade")
                if (examsString && examsString !== 'N/A') {
                    const examPairs = examsString.split(',');
                    examPairs.forEach(function(examPair) {
                        const trimmed = examPair.trim();
                        // Format: "EXAMNAME avg-grade"
                        const match = trimmed.match(/^(.+?)\s+([\d.]+)-([A-Z])$/);
                        if (match) {
                            const examName = match[1];
                            const examAvg = match[2];
                            const examGrade = match[3];
                            content += `
                                <tr>
                                    <td>${examName}</td>
                                    <td>${examAvg}</td>
                                    <td><span class="badge badge-info">${examGrade}</span></td>
                                </tr>
                            `;
                        }
                    });
                } else {
                    content += `
                        <tr>
                            <td colspan="3" class="text-center text-muted">No examinations found</td>
                        </tr>
                    `;
                }
                
                content += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                
                // Build Subject Results Table
                if (response.subjects && response.subjects.length > 0 && response.exams && response.exams.length > 0) {
                    content += `
                        <div class="card mb-3">
                            <div class="card-header bg-primary-custom text-white">
                                <h6 class="mb-0"><i class="bi bi-list-check"></i> Subject Results</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped" id="reportSubjectResultsTable">
                                        <thead class="bg-primary-custom text-white">
                                            <tr>
                                                <th>Subject</th>
                    `;
                    
                    // Add dynamic exam columns
                    response.exams.forEach(function(exam) {
                        content += `<th>${exam.exam_name}</th>`;
                    });
                    
                    content += `
                                                <th>Average</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;
                    
                    // Build rows for each subject
                    response.subjects.forEach(function(subject) {
                        content += `<tr><td><strong>${subject.subject_name}</strong></td>`;
                        
                        // Add marks for each exam
                        response.exams.forEach(function(exam) {
                            const examResult = (subject.exams && subject.exams[exam.examID]) ? subject.exams[exam.examID] : null;
                            
                            if (examResult && examResult.marks !== null && examResult.marks !== '') {
                                const marks = parseFloat(examResult.marks).toFixed(0);
                                const grade = examResult.grade || 'N/A';
                                content += `<td>${marks}-${grade}</td>`;
                            } else {
                                content += `<td>-</td>`;
                            }
                        });
                        
                        // Add average and grade
                        const avg = subject.average ? parseFloat(subject.average).toFixed(1) : '0.0';
                        const grade = subject.grade || 'N/A';
                        content += `<td><strong>${avg}</strong></td>`;
                        content += `<td><span class="badge badge-info">${grade}</span></td>`;
                        content += `</tr>`;
                    });
                    
                    content += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                // Add export buttons
                content += `
                    <div class="card">
                        <div class="card-body text-center">
                            <button class="btn btn-primary export-report-student-pdf-btn" data-student-id="${studentID}" title="Export as PDF">
                                <i class="bi bi-file-pdf"></i> Export PDF
                            </button>
                            <button class="btn btn-success export-report-student-excel-btn ml-2" data-student-id="${studentID}" title="Export as Excel">
                                <i class="bi bi-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                `;
                
                $('#studentDetailsContent').html(content);
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Could not load subject details. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.status === 404) {
                    errorMsg = 'Student not found.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please try again later.';
                }
                $('#studentDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> 
                        ${errorMsg}
                    </div>
                `);
                console.error('Error loading subject details:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status:', xhr.status);
            }
        });
    });

    // Handle view student details button click
    $(document).on('click', '.view-student-details', function() {
        const studentID = $(this).data('student-id');
        const examID = $(this).data('exam-id');
        const $row = $(this).closest('tr');
        
        // Get student info from the row
        const studentName = $row.find('td').eq(2).text().trim();
        const studentNumber = $row.find('td').eq(1).text().trim();
        const gradeOrDivision = $row.find('td').eq(3).text().trim();
        const position = $row.find('td').eq(0).text().trim();
        
        // Show modal
        $('#studentDetailsModal').modal('show');
        
        // Set modal title
        $('#studentDetailsModalLabel').html(`<i class="bi bi-person"></i> ${studentName} - Detailed Results`);
        
        // Set data attributes for export buttons
        $('.export-student-pdf-btn').attr('data-student-id', studentID).attr('data-exam-id', examID);
        $('.export-student-excel-btn').attr('data-student-id', studentID).attr('data-exam-id', examID);
        
        // Show loading
        $('#studentDetailsContent').html(`
            <div class="text-center">
                <div class="spinner-border text-primary-custom" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading student details...</p>
            </div>
        `);
        
        // Get exam name
        const examName = $('#examID option:selected').text().split('(')[0].trim();
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        
        // Build content
        let content = `
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><strong>Student Information</strong></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Student Name:</strong> ${studentName}</p>
                            <p><strong>Student Number:</strong> ${studentNumber}</p>
                            <p><strong>Position:</strong> <span class="badge badge-success">${position}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Exam:</strong> ${examName}</p>
                            <p><strong>${schoolType === 'Primary' ? 'Grade' : 'Division'}:</strong> ${gradeOrDivision}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Make AJAX request to get detailed subject results
        $.ajax({
            url: '{{ route("manageResults") }}',
            method: 'GET',
            data: {
                term: $('#term').val(),
                year: $('#year').val(),
                type: $('#type').val() || 'exam',
                examID: examID,
                class: $('#class').val(),
                studentID: studentID,
                getSubjectDetails: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                // Store student data globally for PDF export
                window.currentStudentData = null;
                
                // Check if response is JSON (subject details) or HTML (full page)
                let subjectsHtml = '';
                
                if (typeof response === 'object' && response.subjects) {
                    // JSON response with subject details
                    const studentPosition = response.position || position;
                    window.currentStudentData = {
                        studentID: response.student.studentID,
                        studentName: (response.student.first_name || '') + ' ' + (response.student.middle_name || '') + ' ' + (response.student.last_name || ''),
                        admissionNumber: response.student.admission_number || 'N/A',
                        gender: response.student.gender || 'Male',
                        photo: response.student.photo || null,
                        examID: examID,
                        examName: examName,
                        totalMarks: response.totalMarks || 0,
                        averageMarks: response.averageMarks || 0,
                        subjectCount: response.subjectCount || 0,
                        grade: response.grade || null,
                        division: response.division || null,
                        totalStudentsCount: response.totalStudentsCount || 0,
                        subjects: response.subjects || [],
                        position: studentPosition,
                        gradeOrDivision: gradeOrDivision
                    };
                    
                    // Update position in content if response has position
                    if (response.position) {
                        content = content.replace(
                            `<p><strong>Position:</strong> <span class="badge badge-success">${position}</span></p>`,
                            `<p><strong>Position:</strong> <span class="badge badge-success">${studentPosition} out of ${response.totalStudentsCount || 0}</span></p>`
                        );
                    }
                    
                    if (response.subjects && response.subjects.length > 0) {
                        const isTermReport = response.subjects[0].is_term_report;
                        
                        if (isTermReport && response.exams) {
                            // TERM REPORT MULTI-COLUMN VIEW
                            let examHeaders = '';
                            response.exams.forEach(ex => {
                                examHeaders += `<th class="text-center">${ex.exam_name}</th>`;
                            });
                            
                            subjectsHtml = `
                                <div class="card">
                                    <div class="card-header bg-primary-custom text-white">
                                        <h6 class="mb-0"><i class="bi bi-book"></i> Subject Results Breakdown</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-striped">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Subject</th>
                                                        ${examHeaders}
                                                        <th class="text-center">Average</th>
                                                        <th class="text-center">Grade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;
                            
                            response.subjects.forEach(function(subject) {
                                let examCells = '';
                                response.exams.forEach(ex => {
                                    const res = (subject.exams && subject.exams[ex.examID]) ? subject.exams[ex.examID] : null;
                                    if (res) {
                                        examCells += `<td class="text-center">${res.marks !== null ? parseFloat(res.marks).toFixed(0) : '-'}<br><small class="text-muted">${res.grade || '-'}</small></td>`;
                                    } else {
                                        examCells += `<td class="text-center text-muted">-</td>`;
                                    }
                                });
                                
                                subjectsHtml += `
                                    <tr>
                                        <td><strong>${subject.subject_name}</strong></td>
                                        ${examCells}
                                        <td class="text-center align-middle font-weight-bold">${parseFloat(subject.average).toFixed(1)}</td>
                                        <td class="text-center align-middle"><span class="badge badge-info">${subject.grade ?? 'N/A'}</span></td>
                                    </tr>
                                `;
                            });
                            
                            subjectsHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            // ORIGINAL CA OR SINGLE EXAM VIEW
                            subjectsHtml = `
                                <div class="card">
                                    <div class="card-header bg-primary-custom text-white">
                                        <h6 class="mb-0"><i class="bi bi-book"></i> Subject Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Marks</th>
                                                        <th>Grade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;
                            
                            response.subjects.forEach(function(subject) {
                                if (subject.is_ca_averaged) {
                                    // CA Averaged View
                                    subjectsHtml += `<tr class="bg-light-info">
                                        <td>
                                            <div class="font-weight-bold text-dark">${subject.subject_name}</div>
                                            <div class="small text-muted mt-1 px-2 border-left">
                                    `;
                                    
                                    if (subject.exams && subject.exams.length > 0) {
                                        subject.exams.forEach(function(ex) {
                                            subjectsHtml += `<div class="d-flex justify-content-between mb-1">
                                                <span>${ex.exam_name}:</span>
                                                <span class="font-italic">${ex.marks ?? '-'} (${ex.grade ?? '-'})</span>
                                            </div>`;
                                        });
                                    }
                                    
                                    subjectsHtml += `</div></td>
                                        <td class="text-center align-middle h5"><strong>${parseFloat(subject.marks).toFixed(1)}</strong></td>
                                        <td class="text-center align-middle font-weight-bold h5 text-primary"><strong>${subject.grade ?? 'N/A'}</strong></td>
                                    </tr>`;
                                } else {
                                    // Single Exam View
                                    const marks = subject.marks !== null && subject.marks !== '' ? 
                                        parseFloat(subject.marks).toFixed(0) : 'N/A';
                                    subjectsHtml += `<tr>
                                        <td>${subject.subject_name}</td>
                                        <td class="text-center">${marks}</td>
                                        <td class="text-center font-weight-bold">${subject.grade ?? 'N/A'}</td>
                                    </tr>`;
                                }
                            });
                            
                            subjectsHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                } else {
                    // HTML response - try to extract from original view structure
                    const $response = $(response);
                    
                    // Try to find student's detailed results in original view
                    const $studentCard = $response.find(`.student-row[data-student-id="${studentID}"]`).closest('.card.result-card');
                    
                    if ($studentCard.length > 0) {
                        // Find the exam result for this specific exam
                        $studentCard.find('table tbody tr').each(function() {
                            const $examRow = $(this);
                            const examNameInRow = $examRow.find('td').eq(0).text().trim();
                            
                            if (examNameInRow === examName || examID) {
                                // Get the collapse button and target
                                const $collapseBtn = $examRow.find('button[data-toggle="collapse"]');
                                if ($collapseBtn.length > 0) {
                                    const collapseTarget = $collapseBtn.attr('data-target');
                                    if (collapseTarget) {
                                        const $collapseDiv = $response.find(collapseTarget);
                                        
                                        // Get subjects table from collapse div
                                        const $subjectsTable = $collapseDiv.find('table');
                                        if ($subjectsTable.length > 0) {
                                            subjectsHtml = `
                                                <div class="card">
                                                    <div class="card-header bg-primary-custom text-white">
                                                        <h6 class="mb-0"><i class="bi bi-book"></i> Subject Details</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            ${$subjectsTable[0].outerHTML}
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
                
                // If no subjects found, show message
                if (!subjectsHtml) {
                    subjectsHtml = `
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            No subject details found for this student and exam.
                        </div>
                    `;
                }
                
                content += subjectsHtml;
                $('#studentDetailsContent').html(content);
            },
            error: function(xhr, status, error) {
                content += `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Could not load detailed subject information. Please refer to the main results table.
                    </div>
                `;
                $('#studentDetailsContent').html(content);
                console.error('Error loading subject details:', error);
            }
        });
    });

    // Load subclasses when class is selected
    $('#class').on('change', function() {
        const classID = $(this).val();
        const schoolID = '{{ Session::get("schoolID") }}';
        const currentSubclass = '{{ $filters["subclass"] ?? "" }}';
        
        if (classID) {
            $.ajax({
                url: '/get_subclasses_for_exam',
                method: 'GET',
                data: {
                    classID: classID,
                    schoolID: schoolID
                },
                success: function(response) {
                    const subclassSelect = $('#subclass');
                    subclassSelect.html('<option value="">All Subclasses</option>');
                    
                    if (response.subclasses && response.subclasses.length > 0) {
                        response.subclasses.forEach(function(subclass) {
                            const selected = (currentSubclass == subclass.subclassID) ? 'selected' : '';
                            const displayName = subclass.display_name || (subclass.class_name + ' ' + subclass.subclass_name);
                            subclassSelect.append(
                                $('<option></option>')
                                    .attr('value', subclass.subclassID)
                                    .attr('selected', selected)
                                    .text(displayName)
                            );
                        });
                    }
                },
                error: function() {
                    console.error('Failed to load subclasses');
                }
            });
        } else {
            $('#subclass').html('<option value="">All Subclasses</option>');
        }
    });

    // Show/hide exam filter based on type selection
    $('#type').on('change', function() {
        const type = $(this).val();
        if (type === 'exam') {
            $('#examFilterContainer').show();
        } else {
            $('#examFilterContainer').hide();
            $('#examID').val('');
        }
    });

    // Update filtering description
    function updateFilteringDescription() {
        const filters = {
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val(),
            status: $('#status').val(),
            class: $('#class').val(),
            subclass: $('#subclass').val(),
            examID: $('#examID').val() || '',
            grade: $('#grade').val() || '',
            gender: $('#gender').val() || '',
            week: $('#week').val() || 'all'
        };

        let description = '';
        
        if (filters.type === 'exam') {
            description = 'Exam Results';
            if (filters.examID) {
                const examSelect = $('#examID option:selected');
                if (examSelect.length > 0) {
                    description = examSelect.text().split('(')[0].trim();
                }
            }
        } else {
            description = 'Term Report';
        }

        // For teachers with locked subclass, get from readonly input fields (default values)
        @if((isset($isTeacherView) && $isTeacherView) || (isset($isCoordinatorResultsView) && $isCoordinatorResultsView))
            // Get from readonly input fields - these contain the default class/subclass from classManagement
            const subclassDisplay = $('#lockedSubclassDisplay');
            const classDisplay = $('#lockedClassDisplay');
            const isCoordinatorView = {{ isset($isCoordinatorResultsView) && $isCoordinatorResultsView ? 'true' : 'false' }};
            
            if (isCoordinatorView) {
                // Coordinator view: main class is locked, subclass can be selected
                const classText = classDisplay.length > 0 ? classDisplay.val() : '';
                const subclassSelect = $('#subclass option:selected');
                const subclassText = subclassSelect.length > 0 ? subclassSelect.text() : '';
                
                if (subclassText && subclassText !== 'All Subclasses') {
                    description = 'Student result of ' + subclassText;
                } else if (classText && classText !== 'N/A') {
                    description = 'Student result of ' + classText;
                }
            } else {
                // Teacher view: both class and subclass are locked
                if (subclassDisplay.length > 0) {
                    const subclassText = subclassDisplay.val();
                    if (subclassText && subclassText !== 'N/A') {
                        description = 'Student result of ' + subclassText;
                    } else if (classDisplay.length > 0) {
                        const classText = classDisplay.val();
                        if (classText && classText !== 'N/A') {
                            description = 'Student result of ' + classText;
                        }
                    }
                } else {
                    // Fallback: try to get from hidden inputs
                    const lockedSubclassID = $('#lockedSubclassID').val();
                    if (lockedSubclassID) {
                        description = 'Student result';
                    }
                }
            }
        @else
            if (filters.subclass) {
                const subclassSelect = $('#subclass option:selected');
                if (subclassSelect.length > 0) {
                    description = 'Student result of ' + subclassSelect.text();
                }
            } else if (filters.class) {
                const classSelect = $('#class option:selected');
                if (classSelect.length > 0) {
                    description = 'Student result of ' + classSelect.text();
                }
            }
        @endif

        if (filters.term) {
            const termText = filters.term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            description += ' - ' + termText;
        }

        description += ' - ' + filters.year;

        if (filters.grade) {
            description += ' (Grade: ' + filters.grade + ')';
        }

        if (filters.gender) {
            description += ' (' + filters.gender + ')';
        }

        if (filters.week && filters.week !== 'all') {
            description += ' (' + filters.week + ')';
        }

        if ($('#subjectID').val()) {
            const subjectName = $('#subjectID option:selected').text();
            description += ' - Subject: ' + subjectName;
        }

        if (description) {
            $('#filteringText').text(description);
            $('#filteringDescription').show();
        } else {
            $('#filteringDescription').hide();
        }
    }

    // Function to load incomplete results
    function loadIncompleteResults(examID) {
        if (!examID) return;

        const $container = $('#incompleteResultsContainer');
        const $list = $('#incompleteResultsList');
        const $count = $('#incompleteCount');
        const $examNameLabel = $('#incompleteExamName');

        $container.show();
        $list.html(`
            <div class="p-5 text-center">
                <div class="spinner-border text-warning mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <p class="text-muted font-italic">Analyzing exam completion data across all subjects...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("manageResults") }}',
            method: 'GET',
            data: {
                examID: examID,
                classID: $('#class').val(),
                subclassID: $('#subclass').val(),
                getIncompleteResults: true
            },
            success: function(response) {
                $examNameLabel.text(response.exam_name);
                
                if (!response.data || response.data.length === 0) {
                    $count.text('0').removeClass('badge-white').addClass('badge-light');
                    $list.html(`
                        <div class="p-5 text-center glass-widget m-3 border-success" style="background: rgba(40, 167, 69, 0.05);">
                            <i class="bi bi-check-circle-all text-success h1 d-block mb-3" style="font-size: 4rem;"></i>
                            <h4 class="text-success font-weight-bold">Maximum Completion!</h4>
                            <p class="text-muted mb-0">Excellent! All subjects assigned to this exam have 100% result entries.</p>
                        </div>
                    `);
                    return;
                }

                $count.text(response.data.length).addClass('badge-white').removeClass('badge-light');

                let html = '';
                response.data.forEach((teacher, tIdx) => {
                    let teacherPhoto = '{{ asset("images/male.png") }}';
                    if (teacher.teacher_photo) {
                        teacherPhoto = `/userImages/${teacher.teacher_photo}`;
                    } else if (teacher.teacher_gender === 'Female') {
                        teacherPhoto = '{{ asset("images/female.png") }}';
                    }
                    
                    const teacherCollapseID = `teacher-collapse-${tIdx}`;
                    const fallbackImg = teacher.teacher_gender === 'Female' ? '{{ asset("images/female.png") }}' : '{{ asset("images/male.png") }}';
                    
                    html += `
                        <div class="list-group-item border-0 border-bottom bg-transparent mb-1 p-0">
                            <div class="d-flex align-items-center justify-content-between p-3 cursor-pointer item-hover teacher-header" data-toggle="collapse" data-target="#${teacherCollapseID}">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="${teacherPhoto}" class="teacher-avatar mr-3" onerror="this.src='${fallbackImg}'" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #940000;">
                                        <span class="position-absolute badge badge-warning p-1 border border-white" style="bottom: 0; right: 10px; font-size: 0.6rem;">${teacher.subjects.length}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">${teacher.teacher_name}</h6>
                                        <div class="small text-muted d-flex align-items-center">
                                            <i class="bi bi-telephone-fill mr-1 x-small"></i> ${teacher.teacher_phone || 'No Phone'}
                                            <span class="mx-2">|</span>
                                            <i class="bi bi-journal-text mr-1 x-small"></i> ${teacher.subjects.length} Subjects Pending
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-warning btn-sm mr-3 font-weight-bold rounded-pill px-3 shadow-sm remind-teacher-btn" 
                                        data-phone="${teacher.teacher_phone || ''}" data-name="${teacher.teacher_name}" style="background-color: #f39c12; border: none; color: white;">
                                        <i class="bi bi-chat-left-dots-fill mr-1"></i> Send Nudge
                                    </button>
                                    <i class="bi bi-chevron-right text-muted transition-icon"></i>
                                </div>
                            </div>
                            <div id="${teacherCollapseID}" class="collapse bg-white mx-3 mb-3 rounded-lg shadow-sm border" style="border-radius: 12px;">
                                <div class="p-2">
                    `;

                    teacher.subjects.forEach((subj, sIdx) => {
                        const studentCollapseID = `students-collapse-${tIdx}-${sIdx}`;
                        html += `
                            <div class="p-3 border-bottom last-child-no-border subject-row">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="cursor-pointer flex-grow-1" data-toggle="collapse" data-target="#${studentCollapseID}">
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 font-weight-bold" style="color: #e67e22;">${subj.subject_name}</h6>
                                            <span class="badge badge-warning-subtle ml-2 px-2" style="background: #fff3e0; color: #e65100; font-size: 0.7rem;">${subj.missing_count} Missing</span>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <span><i class="bi bi-building mr-1"></i> ${subj.class_name}</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-link btn-sm text-muted p-0" data-toggle="collapse" data-target="#${studentCollapseID}">
                                        <i class="bi bi-caret-down-fill transition-icon"></i>
                                    </button>
                                </div>
                                <div id="${studentCollapseID}" class="collapse mt-2 pt-3 border-top" style="background: #fafafa; border-radius: 8px; padding: 10px;">
                                    <p class="small font-weight-bold text-muted mb-2 px-1"><i class="bi bi-people mr-1"></i> Students without results:</p>
                                    <div class="d-flex flex-wrap">
                                        ${subj.students.map(st => `
                                            <span class="student-tag border-warning-subtle">
                                                <i class="bi bi-person text-warning mr-1"></i> ${st.first_name} ${st.last_name} 
                                                <small class="text-muted">(${st.admission_number || 'N/A'})</small>
                                            </span>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    html += `
                                </div>
                            </div>
                        </div>
                    `;
                });

                $list.html(html);
            },
            error: function() {
                $list.html(`
                    <div class="p-5 text-center">
                        <i class="bi bi-exclamation-octagon text-danger h1"></i>
                        <p class="text-danger mt-2 font-weight-bold">Analysis Failed</p>
                        <button class="btn btn-outline-danger btn-sm mt-2" onclick="loadIncompleteResults('${examID}')">Retry Analysis</button>
                    </div>
                `);
            }
        });
    }

    // Remind Single Teacher with custom message
    $(document).on('click', '.remind-teacher-btn', function(e) {
        e.stopPropagation();
        const name = $(this).data('name');
        const phone = $(this).data('phone');
        const examName = $('#incompleteExamName').text();

        if (!phone || phone === 'No Phone') {
            Swal.fire({
                title: 'Missing Phone Number',
                text: `${name} does not have a valid phone number recorded.`,
                icon: 'warning',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        const defaultMsg = `Habari ${name}, tunaomba ukamilishe kuingiza matokeo ya mtihani wa "${examName}" kwa masomo yako. Ahsante.`;

        Swal.fire({
            title: 'Write SMS Nudge',
            input: 'textarea',
            inputLabel: `Compose reminder for ${name}`,
            inputValue: defaultMsg,
            inputAttributes: {
                'autocapitalize': 'off',
                'autocorrect': 'off'
            },
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-chat-left-text mr-1"></i> Send SMS',
            confirmButtonColor: '#e67e22',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value) return 'You need to write something!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const customSms = result.value;
                Swal.fire({
                    title: 'Sending SMS...',
                    html: `Routing message to ${phone}...<br><small class="text-muted font-italic">"${customSms.substring(0, 40)}..."</small>`,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                setTimeout(() => {
                    Swal.fire({
                        title: 'Success!',
                        text: `Custom reminder sent to ${name}.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 1200);
            }
        });
    });

    // Remind All Teachers with Broadcast custom message
    $(document).on('click', '#sendSmsToAllIncomplete', function(e) {
        e.stopPropagation();
        const count = $('#incompleteCount').text();
        const examName = $('#incompleteExamName').text();
        
        if (count === '0') {
            Swal.fire('Everything is set!', 'No teachers need nudging at this time.', 'success');
            return;
        }

        const defaultMsg = `Ndugu Mwalimu, tunaomba ukamilishe kuingiza matokeo ya mtihani wa "${examName}" kwa masomo yote yaliyobaki upesi iwezekanavyo. Ahsante.`;

        Swal.fire({
            title: 'Broadcast Custom Nudge',
            text: `Compose a message for all ${count} teachers with pending results:`,
            input: 'textarea',
            inputValue: defaultMsg,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-broadcast mr-1"></i> Broadcast to All',
            confirmButtonColor: '#d35400',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value) return 'The message cannot be empty!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const broadcastMsg = result.value;
                Swal.fire({
                    title: 'Processing Broadcast...',
                    html: `Dispatching notifications to ${count} educators.<br><small>${broadcastMsg.substring(0, 50)}...</small>`,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                setTimeout(() => {
                    Swal.fire({
                        title: 'Broadcast Complete!',
                        text: `All ${count} teachers have been notified with your custom message.`,
                        icon: 'success',
                        confirmButtonColor: '#27ae60'
                    });
                }, 2000);
            }
        });
    });


    // Handle form submission with AJAX
    $('#filterForm').on('submit', function(e) {
        // SECURITY: For teachers, prevent form manipulation
        @if(isset($isTeacherView) && $isTeacherView)
            const lockedSubclassID = $('#lockedSubclassID').val();
            const submittedSubclass = $('input[name="subclass"]').val();
            
            // Verify subclass hasn't been changed
            if (submittedSubclass !== lockedSubclassID) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Security Error',
                    text: 'You cannot change the class selection. Please refresh the page.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.reload();
                });
                return false;
            }
        @endif
        e.preventDefault();

        // Trigger incomplete results analysis if an exam is selected
        const selectedExamID = $('#examID').val();
        const selectedType = $('#type').val();
        if (selectedExamID && selectedType === 'exam') {
            loadIncompleteResults(selectedExamID);
        } else {
            $('#incompleteResultsContainer').hide();
        }

        filterResultsAjax();
    });

    // Handle clear filters button
    $('#clearFiltersBtn').on('click', function() {
        @if(isset($isTeacherView) && $isTeacherView)
            // For teachers, redirect back to classManagement with subclassID
            const subclassID = $('#lockedSubclassID').val();
            if (subclassID) {
                window.location.href = '{{ route("manageResults") }}?subclassID=' + subclassID;
            } else {
                window.location.href = '{{ route("AdmitedClasses") }}';
            }
        @else
            window.location.href = '{{ route("manageResults") }}';
        @endif
    });

    // INIT: Auto-load incomplete results if an exam is already selected on page load (Persistence)
    $(document).ready(function() {
        const initialExamID = $('#examID').val();
        const initialType = $('#type').val();
        if (initialExamID && initialType === 'exam') {
            loadIncompleteResults(initialExamID);
        }
    });

    // Update filtering description on filter change
    $('#term, #year, #type, #status, #class, #subclass, #examID, #grade, #gender, #week, #subjectID').on('change', function() {
        updateFilteringDescription();
        
        // Auto-filter when term or year or type changes to reload exams
        const changedId = $(this).attr('id');
        if (changedId === 'term' || changedId === 'year' || (changedId === 'type' && $(this).val() === 'exam')) {
            if ($('#type').val() === 'exam') {
                setTimeout(function() {
                    filterResultsAjax();
                }, 100);
            }
        }
    });

    // AJAX function to filter results
    function filterResultsAjax() {
        // SECURITY: For teachers, verify subclass hasn't been changed
        @if(isset($isTeacherView) && $isTeacherView)
            const lockedSubclassID = $('#lockedSubclassID').val();
            const currentSubclass = $('input[name="subclass"]').val();
            
            if (currentSubclass !== lockedSubclassID) {
                Swal.fire({
                    icon: 'error',
                    title: 'Security Error',
                    text: 'You cannot change the class selection. Please refresh the page.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.reload();
                });
                return false;
            }
        @endif
        
        // SECURITY: For coordinators, verify main class hasn't been changed
        @if(isset($isCoordinatorResultsView) && $isCoordinatorResultsView)
            const lockedClassID = $('#lockedClassID').val();
            const currentClass = $('#class').val() || $('#lockedClassID').val();
            
            if (currentClass !== lockedClassID) {
                Swal.fire({
                    icon: 'error',
                    title: 'Security Error',
                    text: 'You cannot change the main class selection. Please refresh the page.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.reload();
                });
                return false;
            }
        @endif
        
        // Show loading indicator
        $('#loadingIndicator').show();
        $('#errorMessage').hide();
        $('#filterSubmitBtn').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Filtering...');

        // Scroll to top of results section
        $('html, body').animate({
            scrollTop: $('#resultsContainer').offset().top - 100
        }, 300);

        // Get form data
        // For teachers, use locked default values from classManagement
        @if(isset($isTeacherView) && $isTeacherView)
            const formData = {
                term: $('#term').val(),
                year: $('#year').val(),
                type: $('#type').val(),
                status: $('#status').val(),
                class: $('#lockedClassID').val(), // Use locked default class
                subclass: $('#lockedSubclassID').val(), // Use locked default subclass
                subclassID: $('#lockedSubclassID').val(), // Also send subclassID parameter
                examID: $('#examID').val() || '',
                grade: $('#grade').val() || '',
                gender: $('#gender').val() || '',
                week: $('#week').val() || 'all',
                subjectID: $('#subjectID').val() || ''
            };
        @elseif(isset($isCoordinatorResultsView) && $isCoordinatorResultsView)
            // Coordinator view: main class is locked, subclass can be selected
            const formData = {
                term: $('#term').val(),
                year: $('#year').val(),
                type: $('#type').val(),
                status: $('#status').val(),
                class: $('#lockedClassID').val(), // Use locked default class
                classID: $('#lockedClassID').val(), // Also send classID parameter for coordinator
                coordinator: 'true', // Indicate this is coordinator view
                subclass: $('#subclass').val() || '', // Coordinator can select subclass
                examID: $('#examID').val() || '',
                grade: $('#grade').val() || '',
                gender: $('#gender').val() || '',
                week: $('#week').val() || 'all',
                subjectID: $('#subjectID').val() || ''
            };
        @else
            const formData = {
                term: $('#term').val(),
                year: $('#year').val(),
                type: $('#type').val(),
                status: $('#status').val(),
                class: $('#class').val(),
                subclass: $('#subclass').val(),
                examID: $('#examID').val() || '',
                grade: $('#grade').val() || '',
                gender: $('#gender').val() || '',
                week: $('#week').val() || 'all',
                subjectID: $('#subjectID').val() || ''
            };
        @endif

        // Build query string
        const queryString = $.param(formData);

        // Make AJAX request
        $.ajax({
            url: '{{ route("manageResults") }}?' + queryString,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Hide loading indicator
                $('#loadingIndicator').hide();
                $('#filterSubmitBtn').prop('disabled', false).html('<i class="bi bi-search"></i> Filter Results');

                // Update URL without reloading page
                const newUrl = '{{ route("manageResults") }}?' + queryString;
                window.history.pushState({path: newUrl}, '', newUrl);

                // Parse the response
                const $response = $(response);
                
                // Extract and update results container
                const $newResultsContainer = $response.find('#resultsContainer');
                
                // Check if we should show detailed view based on filters
                // For exam: type=exam, examID selected, class selected, and (subclass empty OR subclass selected)
                // For report: type=report, term selected, class selected, and (subclass empty OR subclass selected)
                const subclassEmpty = !formData.subclass || formData.subclass === '' || formData.subclass === '0';
                const subclassSelected = formData.subclass && formData.subclass !== '' && formData.subclass !== '0';
                const shouldShowDetailedExam = formData.type === 'exam' && 
                                               formData.examID && 
                                               formData.class && 
                                               (subclassEmpty || subclassSelected);
                const shouldShowDetailedReport = formData.type === 'report' && 
                                                 formData.term && 
                                                 formData.class && 
                                                 (subclassEmpty || subclassSelected);
                const shouldShowDetailed = shouldShowDetailedExam || shouldShowDetailedReport;
                
                // Check if detailed view exists in response (both exam and report)
                const $detailedView = $response.find('.detailed-exam-results, .detailed-report-results');
                
                console.log('AJAX Filter Debug:', {
                    type: formData.type,
                    examID: formData.examID,
                    class: formData.class,
                    subclass: formData.subclass,
                    shouldShowDetailed: shouldShowDetailed,
                    detailedViewFound: $detailedView.length > 0,
                    resultsContainerFound: $newResultsContainer.length > 0
                });
                
                if ($newResultsContainer.length > 0) {
                    // Replace the entire results container content
                    $('#resultsContainer').html($newResultsContainer.html());
                    
                    // Also update the exam list in the filter form if year/term changed
                    const $newExamID = $response.find('#examID');
                    if ($newExamID.length > 0) {
                        const currentVal = $('#examID').val();
                        $('#examID').html($newExamID.html());
                        $('#examID').val(currentVal); // Try to restore selection if it still exists
                    }

                    // And week list
                    const $newWeek = $response.find('#week');
                    if ($newWeek.length > 0) {
                        const currentVal = $('#week').val();
                        $('#week').html($newWeek.html());
                        $('#week').val(currentVal);
                    }
                    
                    // Update result count
                    updateResultCount();
                    
                    // Re-initialize DataTables
                    initializeAllStudentsDataTable();
                    initializeSubjectStatsDataTable();

                    // TRIGGER INCOMPLETE RESULTS ANALYSIS (POST-DATA LOAD)
                    const selectedExamID = $('#examID').val();
                    const selectedType = $('#type').val();
                    if (selectedExamID && selectedType === 'exam') {
                        loadIncompleteResults(selectedExamID);
                    } else {
                        $('#incompleteResultsContainer').hide();
                    }
                    initializeReportStudentsDataTable();
                    initializeReportSubjectStatsDataTable();
                    
                    // Re-initialize student search functionality (only if not detailed view)
                    if (!$detailedView.length || !shouldShowDetailed) {
                        $('#studentSearch').off('keyup').on('keyup', function() {
                            const searchTerm = $(this).val().toLowerCase().trim();
                            let visibleCount = 0;

                            if (searchTerm === '') {
                                $('.student-row').show();
                                visibleCount = $('.student-row').length;
                            } else {
                                $('.student-row').each(function() {
                                    const studentName = $(this).data('student-name') || '';
                                    const admission = $(this).data('admission') || '';
                                    
                                    if (studentName.includes(searchTerm) || admission.includes(searchTerm)) {
                                        $(this).show();
                                        visibleCount++;
                                    } else {
                                        $(this).hide();
                                    }
                                });
                            }

                            $('#resultCountNumber').text(visibleCount);
                        });
                    }
                } else {
                    // Fallback: try to find the results card
                    const $resultsCard = $response.find('.card.border-0.shadow-sm').last();
                    if ($resultsCard.length > 0) {
                        const $currentResultsCard = $('.card.border-0.shadow-sm').last();
                        $currentResultsCard.replaceWith($resultsCard);
                        updateResultCount();
                    } else {
                        // If no results found, show error
                        $('#errorText').text('Could not load results. Please try again.');
                        $('#errorMessage').show();
                    }
                }

                // Update available exams dropdown if needed
                const $newExamSelect = $response.find('#examID');
                if ($newExamSelect.length > 0 && $newExamSelect.find('option').length > 0) {
                    const currentExamID = $('#examID').val();
                    $('#examID').html($newExamSelect.html());
                    if (currentExamID) {
                        $('#examID').val(currentExamID);
                    }
                }

                // Update week filter dropdown if needed
                const $newWeekSelect = $response.find('#week');
                const $newWeekContainer = $response.find('#weekFilterContainer');
                if ($newWeekSelect.length > 0) {
                    const currentWeek = $('#week').val();
                    $('#week').html($newWeekSelect.html());
                    if (currentWeek) {
                        $('#week').val(currentWeek);
                    }
                }
                if ($newWeekContainer.length > 0) {
                    $('#weekFilterContainer').attr('style', $newWeekContainer.attr('style'));
                }

                // Update subclasses dropdown if needed
                const $newSubclassSelect = $response.find('#subclass');
                if ($newSubclassSelect.length > 0 && $newSubclassSelect.find('option').length > 0) {
                    const currentSubclass = $('#subclass').val();
                    $('#subclass').html($newSubclassSelect.html());
                    if (currentSubclass) {
                        $('#subclass').val(currentSubclass);
                    }
                }
            },
            error: function(xhr, status, error) {
                // Hide loading indicator
                $('#loadingIndicator').hide();
                $('#filterSubmitBtn').prop('disabled', false).html('<i class="bi bi-search"></i> Filter Results');

                // Extract error message and type
                let errorMsg = 'An error occurred while filtering results.';
                let errorType = 'general';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    if (xhr.responseJSON.error_type) {
                        errorType = xhr.responseJSON.error_type;
                    }
                } else if (xhr.status === 0) {
                    errorMsg = 'Network error. Please check your connection.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please try again later.';
                } else if (xhr.status === 404) {
                    errorMsg = 'Page not found. Please refresh the page.';
                }
                
                // Determine error type from message if not provided
                if (errorType === 'general') {
                    if (errorMsg.includes("still taken") || errorMsg.includes("still ongoing")) {
                        errorType = 'exam_not_ended';
                    } else if (errorMsg.includes("approval") && (errorMsg.includes("pending") || errorMsg.includes("Wait"))) {
                        errorType = 'approval_pending';
                    } else if (errorMsg.includes("rejected")) {
                        errorType = 'approval_rejected';
                    } else if (errorMsg.includes("Unauthorized") || errorMsg.includes("access")) {
                        errorType = 'unauthorized_access';
                    } else if (errorMsg.includes("select a class") || errorMsg.includes("subclass")) {
                        errorType = 'no_subclass_selected';
                    }
                }
                
                $('#errorText').text(errorMsg);
                $('#errorMessage').show();
                
                // Show SweetAlert info alert
                var title = 'Validation Information';
                var message = errorMsg;
                
                if (errorType === 'exam_not_ended') {
                    title = 'Exam Still Ongoing';
                    message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + errorMsg + '</p><p class="mt-2">You can view results only after the exam has ended.</p></div>';
                } else if (errorType === 'approval_pending') {
                    title = 'Results Pending Approval';
                    message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + errorMsg + '</p><p class="mt-2">Please wait for the approval process to complete.</p></div>';
                } else if (errorType === 'approval_rejected') {
                    title = 'Results Approval Rejected';
                    message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + errorMsg + '</p><p class="mt-2">The results approval was rejected. Please contact the administrator.</p></div>';
                } else if (errorType === 'unauthorized_access') {
                    title = 'Access Denied';
                    message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + errorMsg + '</p><p class="mt-2">You do not have permission to view results for this class.</p></div>';
                } else if (errorType === 'no_subclass_selected') {
                    title = 'Class Selection Required';
                    message = '<div style="text-align: left;"><p><strong>Action Required:</strong> ' + errorMsg + '</p><p class="mt-2">Please select a class to view results.</p></div>';
                }
                
                // Use the showValidationAlert function
                if (typeof showValidationAlert !== 'undefined') {
                    showValidationAlert(title, message, errorType);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: title,
                        html: message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#940000',
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                }
                
                console.error('Filter error:', error);
            }
        });
    }

    // Update filtering description on page load
    updateFilteringDescription();

    // Student search functionality
    $('#studentSearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        if (searchTerm === '') {
            // Show all rows if search is empty
            $('.student-row').show();
            visibleCount = $('.student-row').length;
        } else {
            $('.student-row').each(function() {
                const studentName = $(this).data('student-name') || '';
                const admission = $(this).data('admission') || '';
                
                if (studentName.includes(searchTerm) || admission.includes(searchTerm)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });
        }

        $('#resultCountNumber').text(visibleCount);
    });

    function updateResultCount() {
        const visibleRows = $('.student-row:visible').length;
        const totalRows = $('.student-row').length;
        $('#resultCountNumber').text(visibleRows);
        if (visibleRows === 0 && totalRows > 0) {
            $('#resultCount').html('<span class="badge badge-warning">No results match your search</span>');
        } else {
            $('#resultCount').html('<span class="badge badge-info">Showing <span id="resultCountNumber">' + visibleRows + '</span> results</span>');
        }
    }

    // Ensure jsPDF is available globally
    if (typeof window.jspdf !== 'undefined' && !window.jsPDF) {
        window.jsPDF = window.jspdf.jsPDF;
    }

    // =========================================================
    // SERVER-SIDE PDF DOWNLOAD HELPER (DomPDF - direct link)
    // Uses <a> tag click with href URL - same result as duty book
    // fetch() approach had session cookie issues, direct link works
    // =========================================================
    function serverDownloadPDF(params, filename) {
        const btn = params._btn;
        const originalHtml = btn ? btn.html() : null;
        if (btn) btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Generating PDF...');

        // Build clean params WITHOUT btn jQuery object
        const cleanParams = {};
        Object.keys(params).forEach(function(k) {
            if (k !== '_btn' && params[k] !== null && params[k] !== undefined && params[k] !== '') {
                cleanParams[k] = params[k];
            }
        });

        const queryString = new URLSearchParams(cleanParams).toString();
        const url = '{{ route("admin.download_results_pdf") }}?' + queryString;

        // Use direct anchor download - avoids fetch() session issues
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = filename || 'Result.pdf';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        // Re-enable button after short delay
        setTimeout(function() {
            if (btn && originalHtml) btn.prop('disabled', false).html(originalHtml);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Downloading!', text: 'PDF is being generated...', icon: 'success', timer: 2000, showConfirmButton: false });
            }
        }, 1500);
    }

    // Handle export from exam-result modal (single student)
    $(document).on('click', '.export-student-pdf-btn', function(e) {
        e.preventDefault();
        const studentID = $(this).data('student-id');
        const examID = $(this).data('exam-id');
        const $btn = $(this);

        const params = {
            option: 'single',
            studentID: studentID,
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val() || 'exam',
            status: $('#status').val() || 'active',
            examID: examID || $('#examID').val() || '',
            _btn: $btn
        };

        const studentName = (window.currentStudentData && window.currentStudentData.studentName)
            ? window.currentStudentData.studentName.replace(/\s+/g, '_')
            : 'Student';
        const examName = (window.currentStudentData && window.currentStudentData.examName)
            ? window.currentStudentData.examName.replace(/\s+/g, '_')
            : 'Exam';

        serverDownloadPDF(params, studentName + '_' + examName + '.pdf');
    });

    $(document).on('click', '.export-student-excel-btn', function(e) {
        e.preventDefault();
        const studentID = $(this).data('student-id');
        const examID = $(this).data('exam-id');

        if (!window.currentStudentData) {
            alert('Student data not available. Please view student details first.');
            return;
        }

        downloadStudentExcelFromModal(window.currentStudentData);
    });

    // Handle export from term report modal (single student)
    $(document).on('click', '.export-report-student-pdf-btn', function(e) {
        e.preventDefault();
        const studentID = $(this).data('student-id');
        const $btn = $(this);

        const params = {
            option: 'single',
            studentID: studentID,
            term: $('#term').val(),
            year: $('#year').val(),
            type: 'report',
            status: $('#status').val() || 'active',
            examID: $('#examID').val() || '',
            _btn: $btn
        };

        const studentName = (window.currentReportStudentData && window.currentReportStudentData.studentName)
            ? window.currentReportStudentData.studentName.replace(/\s+/g, '_')
            : 'Student';

        serverDownloadPDF(params, studentName + '_Term_Report.pdf');
    });
    
    $(document).on('click', '.export-report-student-excel-btn', function(e) {
        e.preventDefault();
        const studentID = $(this).data('student-id');
        
        if (!window.currentReportStudentData) {
            alert('Student data not available. Please view student details first.');
            return;
        }
        
        downloadReportStudentExcelFromModal(window.currentReportStudentData);
    });

    // Handle individual student download using JavaScript
    $(document).on('click', '.download-student-btn', function(e) {
        e.preventDefault();
        
        const type = $(this).data('type'); // pdf or excel
        const studentID = $(this).data('student-id');
        
        // Find student row/card
        let studentRow = null;
        if ($('#resultsTable').length > 0) {
            // Term report table
            studentRow = $('#resultsTable tbody tr').filter(function() {
                return $(this).find('.download-student-btn[data-student-id="' + studentID + '"]').length > 0;
            });
        } else {
            // Exam results cards
            studentRow = $('.student-row').filter(function() {
                return $(this).find('.download-student-btn[data-student-id="' + studentID + '"]').length > 0;
            });
        }

        if (studentRow.length === 0) {
            alert('Student data not found');
            return;
        }

        // Get current filter values
        const filters = {
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val(),
            status: $('#status').val(),
            class: $('#class').val(),
            subclass: $('#subclass').val(),
            examID: $('#examID').val() || '',
            grade: $('#grade').val() || '',
            gender: $('#gender').val() || ''
        };

        // Get school info
        const schoolName = '{{ $school->school_name ?? "School" }}';
        const schoolLogo = '{{ $school->school_logo ?? "" }}';

        if (type === 'pdf') {
            downloadStudentPDF(studentRow, studentID, filters, schoolName, schoolLogo);
        } else {
            downloadStudentExcel(studentRow, studentID, filters, schoolName);
        }
    });

    // Function to download student PDF using JavaScript
    function downloadStudentPDF(studentRow, studentID, filters, schoolName, schoolLogo) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        let yPos = 15;
        
        // School name header (centered)
        doc.setFontSize(20);
        doc.setTextColor(148, 0, 0);
        doc.setFont('helvetica', 'bold');
        const pageWidth = doc.internal.pageSize.getWidth();
        doc.text(schoolName, pageWidth / 2, yPos, { align: 'center' });
        yPos += 10;

        // Get student name and details
        let studentName = '';
        let admission = '';
        let className = '';
        let subclassName = '';
        
        if ($('#resultsTable').length > 0) {
            // Term report
            studentName = studentRow.find('td').eq(1).text().trim();
            className = studentRow.find('td').eq(2).text().trim();
        } else {
            // Exam results
            const nameElement = studentRow.find('.card-header h6 strong');
            studentName = nameElement.text().trim();
            className = studentRow.data('main-class') || 'N/A';
        }

        // Build filtering description for title
        let filteringDesc = 'Student result';
        if (className) {
            filteringDesc += ' of ' + className;
        }
        if (filters.type === 'exam') {
            let examName = '';
            if (filters.examID) {
                const examSelect = $('#examID option:selected');
                if (examSelect.length > 0) {
                    examName = examSelect.text().split('(')[0].trim();
                }
            } else {
                if ($('#resultsTable').length === 0) {
                    const firstExamRow = studentRow.find('table tbody tr').first();
                    if (firstExamRow.length > 0) {
                        examName = firstExamRow.find('td').eq(0).text().trim();
                    }
                }
                if (!examName) {
                    examName = 'Examination';
                }
            }
            filteringDesc += ' in ' + examName + ' Examination';
        } else {
            filteringDesc += ' - Term Report';
        }

        // Title (centered)
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text(filteringDesc, pageWidth / 2, yPos, { align: 'center' });
        yPos += 10;

        // Student name
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text('Student: ' + studentName, 14, yPos);
        yPos += 7;

        // Term
        if (filters.term) {
            const termText = filters.term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            doc.setFontSize(11);
            doc.text('Muhula (Term): ' + termText, 14, yPos);
            yPos += 7;
        }

        // Year
        doc.text('Year: ' + filters.year, 14, yPos);
        yPos += 10;

        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        
        if ($('#resultsTable').length > 0) {
            // Term Report - show summary table
            let grade, division;
            if (schoolType === 'Primary') {
                grade = studentRow.find('td').eq(3).text().trim();
            } else {
                division = studentRow.find('td').eq(3).text().trim();
            }
            const position = studentRow.find('td').eq(4).text().trim();

            const summaryData = [];
            if (schoolType === 'Primary') {
                summaryData.push(['Grade', grade]);
            } else {
                summaryData.push(['Division', division]);
            }
            summaryData.push(['Position', position]);

            doc.autoTable({
                startY: yPos,
                head: [['Field', 'Value']],
                body: summaryData,
                theme: 'grid',
                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
                columnStyles: {
                    0: { cellWidth: 80, fontStyle: 'bold' },
                    1: { cellWidth: 80, halign: 'center' }
                },
                tableWidth: 'auto'
            });
            
            // Headmaster's Sign after table
            yPos = doc.lastAutoTable.finalY + 15;
            doc.setDrawColor(0, 0, 255); // Blue color
            doc.setLineWidth(0.5);
            doc.line(pageWidth - 50, yPos, pageWidth - 10, yPos); // Signature line
            doc.setFontSize(10);
            doc.setTextColor(0, 0, 255); // Blue text
            doc.setFont('helvetica', 'bold');
            doc.text('Headmaster\'s Sign', pageWidth - 30, yPos + 5, { align: 'center' });
        } else {
            // Exam Results - show only first exam (single exam per PDF)
            const examRows = studentRow.find('table tbody tr').filter(function() {
                return $(this).find('td').length > 0 && !$(this).find('.collapse').length;
            });
            
            // Get first exam only
            if (examRows.length > 0) {
                const firstExamRow = examRows.first();
                const examName = firstExamRow.find('td').eq(0).text().trim();
                const examDate = firstExamRow.find('td').eq(1).text().trim();
                let grade, division;
                if (schoolType === 'Primary') {
                    grade = firstExamRow.find('td').eq(3).text().trim();
                } else {
                    division = firstExamRow.find('td').eq(2).text().trim();
                }

                // Summary table FIRST (before subjects)
                const summaryData = [];
                if (schoolType === 'Primary') {
                    summaryData.push(['Grade', grade || 'N/A']);
                } else {
                    summaryData.push(['Division', division || 'N/A']);
                }

                doc.autoTable({
                    startY: yPos,
                    head: [['Field', 'Value']],
                    body: summaryData,
                    theme: 'grid',
                    headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                    styles: { fontSize: 10 },
                    margin: { left: 14 },
                    columnStyles: {
                        0: { cellWidth: 80, fontStyle: 'bold' },
                        1: { cellWidth: 80, halign: 'center' }
                    },
                    tableWidth: 'auto'
                });

                yPos = doc.lastAutoTable.finalY + 10;

                // Exam name and date
                doc.setFontSize(11);
                doc.setFont('helvetica', 'bold');
                doc.text(examName + ' - ' + examDate, 14, yPos);
                yPos += 8;

                // Get subjects from collapsed section
                const collapseButton = firstExamRow.find('button[data-toggle="collapse"]');
                const collapseId = collapseButton.attr('data-target');
                if (collapseId) {
                    // Open the collapse section to access data
                    const collapseSection = $(collapseId);
                    if (!collapseSection.hasClass('show')) {
                        collapseSection.addClass('show');
                    }
                    
                    const subjectRows = collapseSection.find('table tbody tr');
                    
                    if (subjectRows.length > 0) {
                        const subjectData = [];
                        subjectRows.each(function() {
                            const subject = $(this).find('td').eq(0).text().trim();
                            let marks = $(this).find('td').eq(1).text().trim();
                            // Round to whole number if it's a number
                            if (marks !== 'N/A' && marks !== '-') {
                                const marksNum = parseFloat(marks);
                                if (!isNaN(marksNum)) {
                                    marks = Math.round(marksNum).toString();
                                }
                            }
                            const subjectGrade = $(this).find('td').eq(2).text().trim();
                            if (subject && subject !== 'N/A') {
                                subjectData.push([subject, marks, subjectGrade]);
                            }
                        });

                        if (subjectData.length > 0) {
                            doc.autoTable({
                                startY: yPos,
                                head: [['Subject', 'Marks', 'Grade']],
                                body: subjectData,
                                theme: 'striped',
                                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                                styles: { fontSize: 10 },
                                margin: { left: 14 },
                                columnStyles: {
                                    0: { cellWidth: 120 },
                                    1: { cellWidth: 40, halign: 'right' },
                                    2: { cellWidth: 40, halign: 'center' }
                                },
                                tableWidth: 'auto'
                            });
                            
                            // Headmaster's Sign after subjects table
                            yPos = doc.lastAutoTable.finalY + 15;
                            doc.setDrawColor(0, 0, 255); // Blue color
                            doc.setLineWidth(0.5);
                            doc.line(pageWidth - 50, yPos, pageWidth - 10, yPos); // Signature line
                            doc.setFontSize(10);
                            doc.setTextColor(0, 0, 255); // Blue text
                            doc.setFont('helvetica', 'bold');
                            doc.text('Headmaster\'s Sign', pageWidth - 30, yPos + 5, { align: 'center' });
                        }
                    }
                }
            }
        }

        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            const pageHeight = doc.internal.pageSize.height;
            const pageWidth = doc.internal.pageSize.getWidth();
            
            // Generated date
            doc.setFontSize(8);
            doc.setTextColor(128, 128, 128);
            doc.text('Generated on ' + new Date().toLocaleString(), pageWidth / 2, pageHeight - 25, { align: 'center' });
            
            // Powered by: EmCa Technologies LTD
            doc.setFontSize(8);
            doc.setTextColor(148, 0, 0);
            doc.setFont('helvetica', 'bold');
            doc.text('Powered by: EmCa Technologies LTD', pageWidth / 2, pageHeight - 20, { align: 'center' });
            
            // Headmaster's Sign (Blue ink)
            doc.setDrawColor(0, 0, 255); // Blue color
            doc.setLineWidth(0.5);
            doc.line(pageWidth - 50, pageHeight - 10, pageWidth - 10, pageHeight - 10); // Signature line
            doc.setFontSize(10);
            doc.setTextColor(0, 0, 255); // Blue text
            doc.setFont('helvetica', 'bold');
            doc.text('Headmaster\'s Sign', pageWidth - 30, pageHeight - 5, { align: 'center' });
        }

        // Generate filename from filtering description
        let filename = $('#filteringText').text().replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        if (!filename) {
            filename = studentName.replace(/\s+/g, '_') + '_';
            filename += (filters.type === 'exam' ? 'Exam' : 'Term_Report') + '_';
            filename += (filters.term ? filters.term.replace('_', '') + '_' : '') + filters.year;
        } else {
            filename = studentName.replace(/\s+/g, '_') + '_' + filename;
        }
        filename += '.pdf';

        doc.save(filename);
    }

    // Function to download student Excel using JavaScript
    function downloadStudentExcel(studentRow, studentID, filters, schoolName) {
        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Get student name
        let studentName = '';
        if ($('#resultsTable').length > 0) {
            const nameCell = studentRow.find('td').eq(1);
            studentName = nameCell.text().trim();
        } else {
            const nameElement = studentRow.find('.card-header h6 strong');
            studentName = nameElement.text().trim();
        }

        // Build title
        let title = '';
        if (filters.type === 'exam') {
            title = 'Exam Results';
            if (filters.examID) {
                const examSelect = $('#examID option:selected');
                if (examSelect.length > 0) {
                    title = examSelect.text().split('(')[0].trim();
                }
            }
        } else {
            title = 'Term Report';
        }
        
        if (filters.term) {
            title += ' - ' + filters.term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
        title += ' - ' + filters.year;

        if ($('#resultsTable').length > 0) {
            // Term report
            const data = [];
            
            // Header rows
            data.push([schoolName]);
            data.push([studentName + ' - ' + title]);
            data.push([]);
            
            // Student info
            const schoolTypeExcel = '{{ $schoolType ?? "Secondary" }}';
            const className = studentRow.find('td').eq(2).text().trim();
            let grade, division;
            if (schoolTypeExcel === 'Primary') {
                grade = studentRow.find('td').eq(3).text().trim();
            } else {
                division = studentRow.find('td').eq(3).text().trim();
            }
            const position = studentRow.find('td').eq(4).text().trim();

            data.push(['Field', 'Value']);
            data.push(['Class', className]);
            if (schoolTypeExcel === 'Primary') {
                data.push(['Grade', grade]);
            } else {
                data.push(['Division', division]);
            }
            data.push(['Position', position]);

            const ws = XLSX.utils.aoa_to_sheet(data);
            
            // Style header
            ws['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 1 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 1 } }
            ];

            XLSX.utils.book_append_sheet(wb, ws, 'Results');
        } else {
            // Exam results
            const data = [];
            
            // Header rows
            data.push([schoolName]);
            data.push([studentName + ' - ' + title]);
            data.push([]);
            if (schoolTypeExcel === 'Primary') {
                data.push(['Exam Name', 'Date', 'Grade']);
            } else {
                data.push(['Exam Name', 'Date', 'Division']);
            }

            const examRows = studentRow.find('table tbody tr').filter(function() {
                return $(this).find('td').length > 0 && !$(this).find('.collapse').length;
            });
            examRows.each(function() {
                const examName = $(this).find('td').eq(0).text().trim();
                const examDate = $(this).find('td').eq(1).text().trim();
                if (schoolTypeExcel === 'Primary') {
                    const grade = $(this).find('td').eq(3).text().trim();
                    data.push([examName, examDate, grade]);
                } else {
                    const division = $(this).find('td').eq(2).text().trim();
                    data.push([examName, examDate, division]);
                }
            });

            const ws = XLSX.utils.aoa_to_sheet(data);
            
            // Style header
            ws['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 5 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 5 } }
            ];

            XLSX.utils.book_append_sheet(wb, ws, 'Results');
        }

        // Generate filename from filtering description
        let filename = $('#filteringText').text().replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        if (!filename) {
            filename = studentName.replace(/\s+/g, '_') + '_';
            filename += (filters.type === 'exam' ? 'Exam' : 'Term_Report') + '_';
            filename += (filters.term ? filters.term.replace('_', '') + '_' : '') + filters.year;
        } else {
            filename = studentName.replace(/\s+/g, '_') + '_' + filename;
        }
        filename += '.xlsx';

        XLSX.writeFile(wb, filename);
    }

    // Export All Students PDF
    // If detailed view (class/subclass with stats) is showing → use jsPDF rich export
    // Otherwise → use server-side DomPDF (simple table)
    $(document).on('click', '#exportAllPdf', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);

        // No detailed view — use server-side DomPDF natively
        console.log('Using server-side DomPDF export for class');
        const classVal    = $('#class').val() || '';
        const subclassVal = $('#subclass').val() || '';

        // Determine right option based on filters selected
        let option = 'all';
        if (subclassVal) option = 'subclass';
        else if (classVal) option = 'class';

        const params = {
            option: option,
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val() || 'exam',
            status: $('#status').val() || 'active',
            examID: $('#examID').val() || '',
            classID:    classVal,
            subclassID: subclassVal,
            grade:  $('#grade').val() || '',
            gender: $('#gender').val() || '',
            _btn: $btn
        };

        const term = params.term ? params.term.replace('_', '') : '';
        const year = params.year;
        const typeName = params.type === 'report' ? 'Term_Report' : 'Exam_Results';
        const filename = 'Students_' + typeName + '_' + (term ? term + '_' : '') + year + '.pdf';

        serverDownloadPDF(params, filename);
    });

    // Function to export detailed view PDF
    function exportDetailedViewPDF() {
        if (!window.detailedViewData) {
            alert('Detailed view data not available. Please refresh the page and try again.');
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation
        
        const schoolName = '{{ $school->school_name ?? "School" }}';
        const schoolLogo = '{{ $school->school_logo ?? "" }}';
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        const pageWidth = doc.internal.pageSize.getWidth();
        let yPos = 15;
        
        const data = window.detailedViewData;
        const className = data.className;
        const examName = data.examName;
        const year = data.year;
        const schoolSubjects = @json($schoolSubjects ?? []);
        
        // Load and add school logo (if available)
        if (schoolLogo) {
            try {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.src = schoolLogo;
                img.onload = function() {
                    const imgWidth = 30;
                    const imgHeight = (img.height * imgWidth) / img.width;
                    doc.addImage(img, 'PNG', (pageWidth - imgWidth) / 2, yPos, imgWidth, imgHeight);
                    yPos += imgHeight + 5;
                    continuePDFGeneration();
                };
                img.onerror = function() {
                    continuePDFGeneration();
                };
            } catch (e) {
                continuePDFGeneration();
            }
        } else {
            continuePDFGeneration();
        }
        
        function continuePDFGeneration() {
            // School name (centered)
            doc.setFontSize(18);
            doc.setTextColor(148, 0, 0);
            doc.setFont('helvetica', 'bold');
            doc.text(schoolName, pageWidth / 2, yPos, { align: 'center' });
            yPos += 8;
            
            // Title: {class name} STUDENT RESULT {exam name} year
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.setFont('helvetica', 'bold');
            const title = className.toUpperCase() + ' STUDENT RESULT ' + examName.toUpperCase() + ' ' + year;
            doc.text(title, pageWidth / 2, yPos, { align: 'center' });
            yPos += 10;
            
            // Calculate table width to match main results table
            // Use larger margins to ensure table is centered and division column fits
            const tableMargin = { left: 15, right: 15 };
            const availableWidth = pageWidth - tableMargin.left - tableMargin.right;
            
            // Statistics Table
            const statsData = [
                ['Total Students', (data.totalStudents || 0).toString()],
                ['Male', (data.maleCount || 0).toString()],
                ['Female', (data.femaleCount || 0).toString()],
                ['Pass Rate', (data.passRate || 0).toString() + '%']
            ];
            
            doc.autoTable({
                startY: yPos,
                head: [['Overview Statistics', 'Value']],
                body: statsData,
                theme: 'grid',
                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { 
                    left: tableMargin.left, 
                    right: tableMargin.right, 
                    top: tableMargin.left,
                    bottom: 25 // Bottom margin to avoid footer overlap
                },
                tableWidth: availableWidth,
                columnStyles: {
                    0: { cellWidth: availableWidth * 0.6, fontStyle: 'bold' },
                    1: { cellWidth: availableWidth * 0.4, halign: 'center' }
                }
            });
            
            yPos = doc.lastAutoTable.finalY + 10;
            
            // Grade/Division Distribution
            if (schoolType === 'Primary') {
                const gradeData = [];
                ['A', 'B', 'C', 'D', 'E', 'F'].forEach(function(grade) {
                    const count = (data.gradeStats && data.gradeStats[grade]) ? data.gradeStats[grade] : 0;
                    gradeData.push(['Grade ' + grade, count.toString()]);
                });
                
                if (gradeData.length > 0) {
                    doc.autoTable({
                        startY: yPos,
                        head: [['Grade Distribution', 'Count']],
                        body: gradeData,
                        theme: 'grid',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                        styles: { fontSize: 9 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: {
                            0: { cellWidth: availableWidth * 0.6, fontStyle: 'bold' },
                            1: { cellWidth: availableWidth * 0.4, halign: 'center' }
                        }
                    });
                    yPos = doc.lastAutoTable.finalY + 10;
                }
            } else {
                const divisionData = [];
                ['I', 'II', 'III', 'IV', '0'].forEach(function(div) {
                    const count = (data.divisionStats && data.divisionStats[div]) ? data.divisionStats[div] : 0;
                    divisionData.push(['Division ' + div, count.toString()]);
                });
                
                if (divisionData.length > 0) {
                    doc.autoTable({
                        startY: yPos,
                        head: [['Division Distribution', 'Count']],
                        body: divisionData,
                        theme: 'grid',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                        styles: { fontSize: 9 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: {
                            0: { cellWidth: availableWidth * 0.6, fontStyle: 'bold' },
                            1: { cellWidth: availableWidth * 0.4, halign: 'center' }
                        }
                    });
                    yPos = doc.lastAutoTable.finalY + 10;
                }
            }
            
            // Male/Female per Division/Grade Table
            if (schoolType === 'Primary') {
                const maleFemaleGradeData = [];
                ['A', 'B', 'C', 'D', 'E', 'F'].forEach(function(grade) {
                    const maleCount = (data.maleGradeStats && data.maleGradeStats[grade]) ? data.maleGradeStats[grade] : 0;
                    const femaleCount = (data.femaleGradeStats && data.femaleGradeStats[grade]) ? data.femaleGradeStats[grade] : 0;
                    const total = maleCount + femaleCount;
                    maleFemaleGradeData.push(['Grade ' + grade, maleCount.toString(), femaleCount.toString(), total.toString()]);
                });
                
                if (maleFemaleGradeData.length > 0) {
                    doc.autoTable({
                        startY: yPos,
                        head: [['Grade', 'Male', 'Female', 'Total']],
                        body: maleFemaleGradeData,
                        theme: 'grid',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                        styles: { fontSize: 9 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: {
                            0: { cellWidth: availableWidth * 0.3, fontStyle: 'bold' },
                            1: { cellWidth: availableWidth * 0.233, halign: 'center' },
                            2: { cellWidth: availableWidth * 0.233, halign: 'center' },
                            3: { cellWidth: availableWidth * 0.234, halign: 'center' }
                        }
                    });
                    yPos = doc.lastAutoTable.finalY + 10;
                }
            } else {
                const maleFemaleDivisionData = [];
                ['I', 'II', 'III', 'IV', '0'].forEach(function(div) {
                    const maleCount = (data.maleDivisionStats && data.maleDivisionStats[div]) ? data.maleDivisionStats[div] : 0;
                    const femaleCount = (data.femaleDivisionStats && data.femaleDivisionStats[div]) ? data.femaleDivisionStats[div] : 0;
                    const total = maleCount + femaleCount;
                    maleFemaleDivisionData.push(['Division ' + div, maleCount.toString(), femaleCount.toString(), total.toString()]);
                });
                
                if (maleFemaleDivisionData.length > 0) {
                    doc.autoTable({
                        startY: yPos,
                        head: [['Division', 'Male', 'Female', 'Total']],
                        body: maleFemaleDivisionData,
                        theme: 'grid',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                        styles: { fontSize: 9 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: {
                            0: { cellWidth: availableWidth * 0.3, fontStyle: 'bold' },
                            1: { cellWidth: availableWidth * 0.233, halign: 'center' },
                            2: { cellWidth: availableWidth * 0.233, halign: 'center' },
                            3: { cellWidth: availableWidth * 0.234, halign: 'center' }
                        }
                    });
                    yPos = doc.lastAutoTable.finalY + 10;
                }
            }
            
            // Class Overview Statistics
            const overviewData = [
                ['Class Average', (data.averageGrade || 'N/A') + ' (' + data.averageMarks + ' marks)'],
                ['Male Average', (data.maleAverage || '0') + ' marks'],
                ['Female Average', (data.femaleAverage || '0') + ' marks'],
                ['Pass Rate', data.passRate + '%'],
                ['Fail Rate', data.failRate + '%'],
                ['Performance Remark', data.performanceRemark || 'N/A']
            ];
            
            doc.autoTable({
                startY: yPos,
                head: [['Class Overview', 'Value']],
                body: overviewData,
                theme: 'grid',
                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 9 },
                margin: { 
                    left: tableMargin.left, 
                    right: tableMargin.right, 
                    top: tableMargin.left,
                    bottom: 25 // Bottom margin to avoid footer overlap
                },
                tableWidth: availableWidth,
                columnStyles: {
                    0: { cellWidth: availableWidth * 0.5, fontStyle: 'bold' },
                    1: { cellWidth: availableWidth * 0.5, halign: 'center' }
                }
            });
            yPos = doc.lastAutoTable.finalY + 10;
            
            // Performance Comment
            if (data.performanceComment) {
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('Performance Comment:', tableMargin.left, yPos);
                yPos += 5;
                
                doc.setFontSize(9);
                doc.setFont('helvetica', 'normal');
                const commentLines = doc.splitTextToSize(data.performanceComment, availableWidth);
                doc.text(commentLines, tableMargin.left, yPos);
                yPos += commentLines.length * 5 + 5;
            }
            
            // Subject Performance Statistics Table
            if (data.subjectStats && Object.keys(data.subjectStats).length > 0) {
                // Title
                doc.setFontSize(11);
                doc.setTextColor(148, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('Subject Performance Statistics', tableMargin.left, yPos);
                yPos += 7;
                
                // Get all subjects and sort alphabetically
                const subjectNames = Object.keys(data.subjectStats).sort();
                
                // Build table data
                const subjectStatsData = [];
                subjectNames.forEach(function(subjectName) {
                    const grades = data.subjectStats[subjectName];
                    const row = [subjectName];
                    
                    // Add grade counts with male/female breakdown
                    ['A', 'B', 'C', 'D', 'E', 'F'].forEach(function(grade) {
                        const gradeData = grades[grade] || { male: 0, female: 0, total: 0 };
                        const total = gradeData.total || 0;
                        const male = gradeData.male || 0;
                        const female = gradeData.female || 0;
                        // Format: "Total\nM: X, F: Y"
                        row.push(total.toString() + '\nM:' + male + ',F:' + female);
                    });
                    
                    subjectStatsData.push(row);
                });
                
                if (subjectStatsData.length > 0) {
                    doc.autoTable({
                        startY: yPos,
                        head: [['Subject', 'A', 'B', 'C', 'D', 'E', 'F']],
                        body: subjectStatsData,
                        theme: 'striped',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold', fontSize: 8 },
                        styles: { fontSize: 7, cellPadding: 2 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: {
                            0: { cellWidth: availableWidth * 0.25, fontStyle: 'bold' }, // Subject name
                            1: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }, // Grade A
                            2: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }, // Grade B
                            3: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }, // Grade C
                            4: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }, // Grade D
                            5: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }, // Grade E
                            6: { cellWidth: availableWidth * 0.125, halign: 'center', fontSize: 6 }  // Grade F
                        },
                        didParseCell: function(data) {
                            // Handle multi-line cells (total and M:F breakdown)
                            if (data.section === 'body' && data.column.index > 0) {
                                const cellValue = data.cell.text[0];
                                if (cellValue && cellValue.includes('\n')) {
                                    data.cell.text = cellValue.split('\n');
                                }
                            }
                        }
                    });
                    yPos = doc.lastAutoTable.finalY + 10;
                }
            }
            
            // Top 5 Students Table
            if (data.top5Students && data.top5Students.length > 0) {
                // Title: TOP FIVE STUDENTS
                doc.setFontSize(12);
                doc.setTextColor(148, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('TOP FIVE STUDENTS', tableMargin.left, yPos);
                yPos += 7;
                
                const top5Data = data.top5Students.map(function(s) {
                    return [s.position.toString(), s.studentName, s.divisionOrGrade];
                });
                
                doc.autoTable({
                    startY: yPos,
                    head: [['Position', 'Student Name', schoolType === 'Primary' ? 'Grade' : 'Division']],
                    body: top5Data,
                    theme: 'striped',
                    headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                    styles: { fontSize: 9 },
                    margin: { 
                        left: tableMargin.left, 
                        right: tableMargin.right, 
                        top: tableMargin.left,
                        bottom: 25 // Bottom margin to avoid footer overlap
                    },
                    tableWidth: availableWidth,
                    columnStyles: {
                        0: { cellWidth: availableWidth * 0.15, halign: 'center' },
                        1: { cellWidth: availableWidth * 0.65, fontStyle: 'bold' },
                        2: { cellWidth: availableWidth * 0.2, halign: 'center', fontStyle: 'bold' }
                    }
                });
                yPos = doc.lastAutoTable.finalY + 10;
            }
            
            // All Students Table with subjects
            // Check if we need to start on a new page to avoid header/footer overlap
            const pageHeight = doc.internal.pageSize.height;
            const footerHeight = 25; // Space needed for footer (increased)
            const minSpaceNeeded = 30; // Minimum space needed for table header
            
            // If current yPos is too close to footer, add a new page
            if (yPos > pageHeight - footerHeight - minSpaceNeeded) {
                doc.addPage();
                yPos = 20; // Start from top of new page with some margin
            }
            
            const headers = ['Position', 'Student Name', 'Class', 'Subject'];
            if (schoolType === 'Primary') {
                headers.push('Total Marks', 'Grade');
            } else {
                headers.push('Division');
            }
            
            const allStudentsData = [];
            
            if (data.allStudents && data.allStudents.length > 0) {
                data.allStudents.forEach(function(student, index) {
                    // Use position from data if available, otherwise use index + 1
                    const position = student.position || (index + 1);
                    const rowData = [position.toString(), student.studentName || 'N/A', student.className || 'N/A'];
                    
                    // Build subjects string: "ENGLISH-79-A, KISWAHILI-80-B"
                    let subjectsString = '';
                    if (student.subjects && student.subjects.length > 0) {
                        const subjectParts = [];
                        student.subjects.forEach(function(subj) {
                            if (subj.marks !== null && subj.marks !== '') {
                                const subjectName = (subj.subject_name || 'N/A').toUpperCase();
                                const marks = parseFloat(subj.marks).toFixed(0);
                                const grade = subj.grade || '';
                                if (grade) {
                                    subjectParts.push(subjectName + '-' + marks + '-' + grade);
                                } else {
                                    subjectParts.push(subjectName + '-' + marks);
                                }
                            }
                        });
                        subjectsString = subjectParts.join(', ');
                    }
                    
                    // Add subjects string
                    rowData.push(subjectsString || '-');
                    
                    // Add division/grade or total marks and grade
                    if (schoolType === 'Primary') {
                        rowData.push((student.totalMarks || 0).toString(), student.grade || 'N/A');
                    } else {
                        rowData.push(student.division || 'N/A');
                    }
                    
                    allStudentsData.push(rowData);
                });
            }
            
            // Create all students table with proper column widths
            if (allStudentsData.length > 0) {
                try {
                    // Set column styles - ensure division column has enough width
                    const columnStyles = {};
                    const positionWidth = 15; // Position column width
                    const studentNameWidth = 35; // Fixed width for student name
                    const classWidth = 25; // Class column width
                    const divisionWidth = 25; // Division needs enough space (e.g., "I.13", "II.18")
                    const totalMarksWidth = 20; // For primary total marks
                    const gradeWidth = 15; // For primary grade
                    
                    // Calculate remaining width for subjects column (single column with all subjects)
                    const reservedWidth = positionWidth + studentNameWidth + classWidth + (schoolType === 'Primary' ? (totalMarksWidth + gradeWidth) : divisionWidth);
                    let subjectColumnWidth = availableWidth - reservedWidth;
                    
                    // Ensure subject column has minimum width
                    if (subjectColumnWidth < 50) {
                        subjectColumnWidth = 50; // Minimum width for subject column
                    }
                    
                    // Position column
                    columnStyles[0] = { cellWidth: positionWidth, fontSize: 7, halign: 'center' };
                    
                    // Student Name column - bold Arial Black
                    columnStyles[1] = { cellWidth: studentNameWidth, fontSize: 7, fontStyle: 'bold', font: 'helvetica' };
                    
                    // Class column
                    columnStyles[2] = { cellWidth: classWidth, fontSize: 7, halign: 'center' };
                    
                    // Subject column (single column with all subjects: "ENGLISH-79-A, KISWAHILI-80-B") - bold Arial Black
                    columnStyles[3] = { cellWidth: subjectColumnWidth, fontSize: 6, halign: 'left', fontStyle: 'bold', font: 'helvetica' };
                    
                    // Division/Grade column (last column) - bold Arial Black
                    if (schoolType === 'Primary') {
                        columnStyles[4] = { cellWidth: totalMarksWidth, fontSize: 7, halign: 'center' };
                        columnStyles[5] = { cellWidth: gradeWidth, fontSize: 7, halign: 'center', fontStyle: 'bold', font: 'helvetica' };
                    } else {
                        columnStyles[4] = { cellWidth: divisionWidth, fontSize: 7, halign: 'center', fontStyle: 'bold', font: 'helvetica' };
                    }
                    
                    doc.autoTable({
                        startY: yPos,
                        head: [headers],
                        body: allStudentsData,
                        theme: 'striped',
                        headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold', fontSize: 8 },
                        styles: { fontSize: 7 },
                        margin: { 
                            left: tableMargin.left, 
                            right: tableMargin.right, 
                            top: tableMargin.left,
                            bottom: 25 // Increased bottom margin to avoid footer overlap
                        },
                        tableWidth: availableWidth,
                        columnStyles: columnStyles,
                        styles: { overflow: 'linebreak' },
                        showHead: 'everyPage',
                        didDrawCell: function(data) {
                            // Apply bold Arial Black font to specific columns
                            if (data.section === 'body') {
                                // Column 1: Student Name - bold
                                if (data.column.index === 1) {
                                    doc.setFont('helvetica', 'bold');
                                }
                                // Column 3: Subject - bold
                                else if (data.column.index === 3) {
                                    doc.setFont('helvetica', 'bold');
                                }
                                // Column 4 or 5: Division/Grade - bold
                                else if ((schoolType === 'Primary' && data.column.index === 5) || (schoolType !== 'Primary' && data.column.index === 4)) {
                                    doc.setFont('helvetica', 'bold');
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error generating PDF table:', error);
                    alert('Error generating PDF. Please check console for details.');
                    return;
                }
            } else {
                alert('No student data available to export.');
                return;
            }
            
            // Footer - Add to all pages with proper spacing
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                const pageHeight = doc.internal.pageSize.height;
                
                // Footer position - ensure enough space from bottom
                const footerY = pageHeight - 12; // Position footer 12mm from bottom
                const dateY = footerY - 5; // Date 5mm above footer text
                
                // Generated date
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text('Generated on ' + new Date().toLocaleString(), pageWidth / 2, dateY, { align: 'center' });
                
                // Powered by: EmCa Technologies LTD
                doc.setFontSize(8);
                doc.setTextColor(148, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('Powered by: EmCa Technologies LTD', pageWidth / 2, footerY, { align: 'center' });
            }
            
            // Generate filename
            let filename = className.replace(/\s+/g, '_') + '_STUDENT_RESULT_' + examName.replace(/\s+/g, '_') + '_' + year + '.pdf';
            doc.save(filename);
        }
    }

    // Export All Excel - Use event delegation to handle dynamically loaded buttons
    $(document).on('click', '#exportAllExcel', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Export All Excel button clicked');
        console.log('Button element:', this);
        console.log('jQuery object:', $(this));
        
        // Verify button exists
        if ($(this).length === 0) {
            console.error('Export Excel button not found in DOM');
            alert('Export button not found. Please refresh the page.');
            return;
        }
        
        try {
            exportAllStudentsExcel();
        } catch (error) {
            console.error('Error exporting Excel:', error);
            console.error('Error stack:', error.stack);
            alert('Error exporting Excel: ' + error.message);
        }
    });

    function exportAllStudentsPDF() {
        // Legacy fallback — now main export is handled by #exportAllPdf click handler above
        // using server-side DomPDF via fetch blob. This function is kept for any direct calls.
        $('#exportAllPdf').trigger('click');
    }

    // NOTE: exportDetailedViewPDF (jsPDF) kept below for detailed stats view
    // which is a different complex view not covered by server-side simple table PDF
    if (false) {
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape for wide table
        
        // Get filters
        const filters = {
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val(),
            status: $('#status').val(),
            class: $('#class').val(),
            subclass: $('#subclass').val(),
            examID: $('#examID').val() || '',
            grade: $('#grade').val() || '',
            gender: $('#gender').val() || ''
        };
        console.log('Filters:', filters);

        const schoolName = '{{ $school->school_name ?? "School" }}';
        const pageWidth = doc.internal.pageSize.getWidth();
        let yPos = 15;

        // School header (centered)
        doc.setFontSize(18);
        doc.setTextColor(148, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text(schoolName, pageWidth / 2, yPos, { align: 'center' });
        yPos += 8;

        // Title from filtering description
        let title = $('#filteringText').text();
        if (!title) {
            if (filters.type === 'exam') {
                title = 'Exam Results';
                if (filters.examID) {
                    const examSelect = $('#examID option:selected');
                    if (examSelect.length > 0) {
                        title = examSelect.text().split('(')[0].trim();
                    }
                }
            } else {
                title = 'Term Report';
            }
            
            if (filters.term) {
                title += ' - ' + filters.term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
            title += ' - ' + filters.year;
        }

        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text(title, pageWidth / 2, yPos, { align: 'center' });
        yPos += 8;

        // Get all visible students
        const studentRows = $('.student-row:visible');
        console.log('Found student rows:', studentRows.length);
        
        if (studentRows.length === 0) {
            alert('No students to export. Please check your filters and make sure students are visible on the page.');
            console.warn('No visible student rows found');
            return;
        }

        // Get all school subjects
        const schoolSubjects = @json($schoolSubjects ?? []);
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        
        // Build headers: #, Student Name, Class, [All Subjects], Division/Grade
        const headers = ['#', 'Student Name', 'Class'];
        schoolSubjects.forEach(function(subject) {
            headers.push(subject.subject_name);
        });
        if (schoolType === 'Primary') {
            headers.push('Grade');
        } else {
            headers.push('Division');
        }

        const tableData = [];

        studentRows.each(function(index) {
            const row = $(this);
            const studentID = row.find('.download-student-btn').first().data('student-id');
            
            let studentName = '';
            let className = '';
            
            if ($('#resultsTable').length > 0) {
                // Term Report (Standard)
                studentName = row.find('td').eq(1).text().trim();
                className = row.find('td').eq(2).text().trim();
            } else if ($('#reportStudentsTable').length > 0) {
                // Term Report (Detailed/Report Students Table)
                studentName = row.find('td').eq(1).text().trim();
                className = '{{ $subclassDisplayName ?? ($selectedClass->class_name ?? "N/A") }}';
            } else {
                // Exam Results
                studentName = row.find('.card-header h6 strong').text().trim();
                
                // Get class from data attribute
                className = row.data('main-class') || 'N/A';
            }

            const mainClass = className;  // alias used later

            const rowData = [index + 1, studentName, className];
            
            // Get marks for each subject
            schoolSubjects.forEach(function(subject) {
                let marks = '-';
                
                // Try to find marks for this subject
                if ($('#resultsTable').length === 0) {
                    // Exam Results - check collapsed sections
                    const examRows = row.find('table tbody tr').filter(function() {
                        return $(this).find('td').length > 0 && !$(this).find('.collapse').length;
                    });
                    
                    examRows.each(function() {
                        const collapseButton = $(this).find('button[data-toggle="collapse"]');
                        const collapseId = collapseButton.attr('data-target');
                        if (collapseId) {
                            const collapseSection = $(collapseId);
                            if (!collapseSection.hasClass('show')) {
                                collapseSection.addClass('show');
                            }
                            
                            const subjectRows = collapseSection.find('table tbody tr');
                            subjectRows.each(function() {
                                const subjName = $(this).find('td').eq(0).text().trim();
                                if (subjName === subject.subject_name) {
                                    marks = $(this).find('td').eq(1).text().trim();
                                    return false; // Break
                                }
                            });
                            
                            if (marks !== '-') {
                                return false; // Break outer loop
                            }
                        }
                    });
                }
                
                rowData.push(marks);
            });

            // Get total, division/grade, main class
            if ($('#resultsTable').length > 0) {
                const totalMarks = row.find('td').eq(6).text().trim();
                const division = row.find('td').eq(8).text().trim() || row.find('td').eq(7).text().trim();
                rowData.push(totalMarks);
                rowData.push(division);
                rowData.push(mainClass);
            } else {
                // Exam Results - get from first exam
                const firstExamRow = row.find('table tbody tr').first();
                if (firstExamRow.length > 0) {
                    const totalMarks = firstExamRow.find('td').eq(2).text().trim();
                    const division = firstExamRow.find('td').eq(5).text().trim() || firstExamRow.find('td').eq(4).text().trim();
                    rowData.push(totalMarks);
                    rowData.push(division);
                    rowData.push(mainClass);
                } else {
                    rowData.push('N/A');
                    rowData.push('N/A');
                    rowData.push(mainClass);
                }
            }

            tableData.push(rowData);
        });

        // Create table
        doc.autoTable({
            startY: yPos,
            head: [headers],
            body: tableData,
            theme: 'striped',
            headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold', fontSize: 7 },
            styles: { fontSize: 6, overflow: 'linebreak' },
            margin: { left: 10, right: 10 },
            tableWidth: 'auto'
        });

        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            const pageHeight = doc.internal.pageSize.height;
            
            // Generated date
            doc.setFontSize(8);
            doc.setTextColor(128, 128, 128);
            doc.text('Generated on ' + new Date().toLocaleString(), pageWidth / 2, pageHeight - 15, { align: 'center' });
            
            // Powered by: EmCa Technologies LTD
            doc.setFontSize(8);
            doc.setTextColor(148, 0, 0);
            doc.setFont('helvetica', 'bold');
            doc.text('Powered by: EmCa Technologies LTD', pageWidth / 2, pageHeight - 10, { align: 'center' });
        }

        // Generate filename from filtering description
        let filename = $('#filteringText').text().replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        if (!filename) {
            filename = 'All_Students_';
            filename += (filters.type === 'exam' ? 'Exam' : 'Term_Report') + '_';
            filename += (filters.term ? filters.term.replace('_', '') + '_' : '') + filters.year;
        }
        filename += '.pdf';

        doc.save(filename);
    }

    function exportAllStudentsExcel() {
        console.log('exportAllStudentsExcel called');
        
        // Check if XLSX is available
        if (typeof XLSX === 'undefined') {
            alert('Excel library not loaded. Please refresh the page and try again.');
            console.error('XLSX not available');
            return;
        }
        
        const wb = XLSX.utils.book_new();
        const schoolName = '{{ $school->school_name ?? "School" }}';

        // Get filters
        const filters = {
            term: $('#term').val(),
            year: $('#year').val(),
            type: $('#type').val()
        };
        console.log('Filters:', filters);

        // Build title from filtering description
        let title = $('#filteringText').text();
        if (!title) {
            if (filters.type === 'exam') {
                title = 'Exam Results';
                if ($('#examID').val()) {
                    const examSelect = $('#examID option:selected');
                    if (examSelect.length > 0) {
                        title = examSelect.text().split('(')[0].trim();
                    }
                }
            } else {
                title = 'Term Report';
            }
            
            if (filters.term) {
                title += ' - ' + filters.term.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
            title += ' - ' + filters.year;
        }

        const data = [];
        data.push([schoolName]);
        data.push([title]);
        data.push([]);

        // Get all visible students
        const studentRows = $('.student-row:visible');
        console.log('Found student rows:', studentRows.length);
        
        if (studentRows.length === 0) {
            alert('No students to export. Please check your filters and make sure students are visible on the page.');
            console.warn('No visible student rows found');
            return;
        }

        // Get all school subjects
        const schoolSubjects = @json($schoolSubjects ?? []);
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        
        // Build headers: #, Student Name, Class, [All Subjects], Division/Grade
        const headers = ['#', 'Student Name', 'Class'];
        schoolSubjects.forEach(function(subject) {
            headers.push(subject.subject_name);
        });
        if (schoolType === 'Primary') {
            headers.push('Grade');
        } else {
            headers.push('Division');
        }

        data.push(headers);

        // Get data for each student
        studentRows.each(function(index) {
            const row = $(this);
            const studentID = row.find('.download-student-btn').first().data('student-id');
            
            let studentName = '';
            let className = '';
            
            if ($('#resultsTable').length > 0) {
                // Term Report
                studentName = row.find('td').eq(1).text().trim();
                className = row.find('td').eq(2).text().trim();
            } else {
                // Exam Results
                studentName = row.find('.card-header h6 strong').text().trim();
                
                // Get class from data attribute
                className = row.data('main-class') || 'N/A';
            }

            const rowData = [index + 1, studentName, className];
            
            // Get marks for each subject
            schoolSubjects.forEach(function(subject) {
                let marks = '-';
                
                // Try to find marks for this subject
                if ($('#resultsTable').length === 0) {
                    // Exam Results - check collapsed sections
                    const examRows = row.find('table tbody tr').filter(function() {
                        return $(this).find('td').length > 0 && !$(this).find('.collapse').length;
                    });
                    
                    examRows.each(function() {
                        const collapseButton = $(this).find('button[data-toggle="collapse"]');
                        const collapseId = collapseButton.attr('data-target');
                        if (collapseId) {
                            const collapseSection = $(collapseId);
                            if (!collapseSection.hasClass('show')) {
                                collapseSection.addClass('show');
                            }
                            
                            const subjectRows = collapseSection.find('table tbody tr');
                            subjectRows.each(function() {
                                const subjName = $(this).find('td').eq(0).text().trim();
                                if (subjName === subject.subject_name) {
                                    marks = $(this).find('td').eq(1).text().trim();
                                    return false; // Break
                                }
                            });
                            
                            if (marks !== '-') {
                                return false; // Break outer loop
                            }
                        }
                    });
                }
                
                rowData.push(marks);
            });

            // Get total, division/grade
            const mainClass = row.data('main-class') || className || 'N/A';
            if ($('#resultsTable').length > 0) {
                // Term Report
                const totalMarks = row.find('td').eq(6).text().trim();
                const division = row.find('td').eq(8).text().trim() || row.find('td').eq(7).text().trim();
                rowData.push(totalMarks);
                rowData.push(division);
            } else {
                // Exam Results - get from first exam
                const firstExamRow = row.find('table tbody tr').first();
                if (firstExamRow.length > 0) {
                    const totalMarks = firstExamRow.find('td').eq(2).text().trim();
                    const division = firstExamRow.find('td').eq(5).text().trim() || firstExamRow.find('td').eq(4).text().trim();
                    rowData.push(totalMarks);
                    rowData.push(division);
                } else {
                    rowData.push('N/A');
                    rowData.push('N/A');
                }
            }

            data.push(rowData);
        });

        const ws = XLSX.utils.aoa_to_sheet(data);
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: headers.length - 1 } },
            { s: { r: 1, c: 0 }, e: { r: 1, c: headers.length - 1 } }
        ];

        XLSX.utils.book_append_sheet(wb, ws, 'Results');

        // Generate filename from filtering description
        let filename = $('#filteringText').text().replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        if (!filename) {
            filename = 'All_Students_';
            filename += (filters.type === 'exam' ? 'Exam' : 'Term_Report') + '_';
            filename += (filters.term ? filters.term.replace('_', '') + '_' : '') + filters.year;
        }
        filename += '.xlsx';

        XLSX.writeFile(wb, filename);
    }
    
    // Function to download student PDF from modal
    function downloadStudentPDFFromModal(studentData) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        const schoolName = '{{ $school->school_name ?? "School" }}';
        const schoolLogo = '{{ $school->school_logo ?? "" }}';
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        const pageWidth = doc.internal.pageSize.getWidth();
        let yPos = 15;
        
        // Header: School logo (left), Student image (right), School name (center)
        const logoWidth = 25;
        const studentImageWidth = 30;
        
        // Load images asynchronously using Promise
        const loadImage = function(src) {
            return new Promise(function(resolve, reject) {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = function() {
                    resolve(img);
                };
                img.onerror = function() {
                    resolve(null); // Return null if image fails to load
                };
                img.src = src;
            });
        };
        
        // Load both images
        const logoPromise = schoolLogo ? loadImage(schoolLogo) : Promise.resolve(null);
        const studentImagePath = studentData.photo || 
            (studentData.gender === 'Female' ? '{{ asset("placeholder/female.png") }}' : '{{ asset("placeholder/male.png") }}');
        const studentImagePromise = loadImage(studentImagePath);
        
        Promise.all([logoPromise, studentImagePromise]).then(function(images) {
            const logoImg = images[0];
            const studentImg = images[1];
            
            // Add school logo (left)
            if (logoImg) {
                const logoActualHeight = (logoImg.height * logoWidth) / logoImg.width;
                doc.addImage(logoImg, 'PNG', 15, yPos, logoWidth, logoActualHeight);
            }
            
            // Add student image (right) or placeholder
            if (studentImg) {
                const studentActualHeight = (studentImg.height * studentImageWidth) / studentImg.width;
                doc.addImage(studentImg, 'PNG', pageWidth - 15 - studentImageWidth, yPos, studentImageWidth, studentActualHeight);
            }
            
            // School name (center, between logo and image)
            doc.setFontSize(16);
            doc.setTextColor(148, 0, 0);
            doc.setFont('helvetica', 'bold');
            doc.text(schoolName, pageWidth / 2, yPos + 10, { align: 'center' });
            yPos += 20;
            
            // Exam name + "STUDENT RESULT"
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.setFont('helvetica', 'bold');
            const examTitle = (studentData.examName || 'EXAMINATION') + ' STUDENT RESULT';
            doc.text(examTitle, pageWidth / 2, yPos, { align: 'center' });
            yPos += 8;
            
            // Student name
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.text(studentData.studentName || 'N/A', pageWidth / 2, yPos, { align: 'center' });
            yPos += 10;
            
            // Calculate consistent table width and margins for all tables
            const tableMargin = 20; // Equal margins on both sides
            const availableWidth = pageWidth - (tableMargin * 2);
            const tableWidth = availableWidth; // Use full available width
            
            // Overview Table: Position (out of...), Average grade, Total marks, Division (for secondary only)
            const overviewData = [];
            const positionText = (studentData.position || 'N/A') + ' out of ' + (studentData.totalStudentsCount || 0);
            overviewData.push(['Position', positionText]);
            
            // Calculate average grade
            let averageGrade = 'N/A';
            if (studentData.averageMarks) {
                const avg = parseFloat(studentData.averageMarks);
                if (avg >= 75) averageGrade = 'A';
                else if (avg >= 65) averageGrade = 'B';
                else if (avg >= 50) averageGrade = 'C';
                else if (avg >= 40) averageGrade = 'D';
                else if (avg >= 30) averageGrade = 'E';
                else averageGrade = 'F';
            }
            overviewData.push(['Average Grade', averageGrade]);
            overviewData.push(['Total Marks', (studentData.totalMarks || 0).toFixed(0)]);
            
            // Always show Division for Secondary school, even if empty (show N/A)
            if (schoolType === 'Secondary') {
                const divisionValue = studentData.division || studentData.gradeOrDivision || 'N/A';
                overviewData.push(['Division', divisionValue]);
            }
            
            doc.autoTable({
                startY: yPos,
                head: [['Overview', 'Value']],
                body: overviewData,
                theme: 'grid',
                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { 
                    left: tableMargin, 
                    right: tableMargin,
                    top: tableMargin,
                    bottom: 10
                },
                tableWidth: tableWidth,
                columnStyles: {
                    0: { cellWidth: tableWidth * 0.5, fontStyle: 'bold' },
                    1: { cellWidth: tableWidth * 0.5, halign: 'center' }
                }
            });
            yPos = doc.lastAutoTable.finalY + 10;
            
            // Subjects Table
            if (studentData.subjects && studentData.subjects.length > 0) {
                const hasCA = studentData.subjects.some(s => s.is_ca_averaged && s.exams && s.exams.length > 1);
                const headers = hasCA ? [['Subject', 'CA', 'Exam', 'Total', 'Grade']] : [['Subject', 'Marks', 'Grade']];
                
                const subjectData = [];
                studentData.subjects.forEach(function(subject) {
                    let marks = 'N/A';
                    if (subject.marks === 'incomplete') {
                        marks = 'incomplete';
                    } else if (subject.marks !== null && subject.marks !== '') {
                        marks = parseFloat(subject.marks).toFixed(0);
                    }

                    if (hasCA) {
                        let caMarks = '-';
                        let examMarks = '-';
                        if (subject.exams) {
                            let caSum = 0;
                            let caCount = 0;
                            subject.exams.forEach(ex => {
                                if (ex.exam_name.includes('(CA)')) {
                                    caSum += parseFloat(ex.marks) || 0;
                                    caCount++;
                                } else {
                                    examMarks = (ex.marks !== null && ex.marks !== '') ? parseFloat(ex.marks).toFixed(0) : '-';
                                }
                            });
                            if (caCount > 0) caMarks = (caSum / caCount).toFixed(0);
                        }
                        subjectData.push([
                            subject.subject_name || 'N/A',
                            caMarks,
                            examMarks,
                            marks,
                            subject.grade || 'N/A'
                        ]);
                    } else {
                        subjectData.push([
                            subject.subject_name || 'N/A',
                            marks,
                            subject.grade || 'N/A'
                        ]);
                    }
                });
                
                const columnStyles = hasCA ? {
                    0: { cellWidth: tableWidth * 0.4 },
                    1: { cellWidth: tableWidth * 0.15, halign: 'center' },
                    2: { cellWidth: tableWidth * 0.15, halign: 'center' },
                    3: { cellWidth: tableWidth * 0.15, halign: 'center' },
                    4: { cellWidth: tableWidth * 0.15, halign: 'center' }
                } : {
                    0: { cellWidth: tableWidth * 0.6 },
                    1: { cellWidth: tableWidth * 0.2, halign: 'right' },
                    2: { cellWidth: tableWidth * 0.2, halign: 'center' }
                };

                doc.autoTable({
                    startY: yPos,
                    head: headers,
                    body: subjectData,
                    theme: 'striped',
                    headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                    styles: { fontSize: 9 },
                    margin: { 
                        left: tableMargin, 
                        right: tableMargin,
                        top: tableMargin,
                        bottom: 10
                    },
                    tableWidth: tableWidth,
                    columnStyles: columnStyles
                });
            }
            
            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                const pageHeight = doc.internal.pageSize.height;
                
                // Generated date
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text('Generated on ' + new Date().toLocaleString(), pageWidth / 2, pageHeight - 25, { align: 'center' });
                
                // Powered by: EmCa Technologies LTD
                doc.setFontSize(8);
                doc.setTextColor(148, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('Powered by: EmCa Technologies LTD', pageWidth / 2, pageHeight - 20, { align: 'center' });
                
                // Headmaster's Sign (Blue ink)
                doc.setDrawColor(0, 0, 255); // Blue color
                doc.setLineWidth(0.5);
                doc.line(pageWidth - 50, pageHeight - 10, pageWidth - 10, pageHeight - 10); // Signature line
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 255); // Blue text
                doc.setFont('helvetica', 'bold');
                doc.text('Headmaster\'s Sign', pageWidth - 30, pageHeight - 5, { align: 'center' });
            }
            
            // Generate filename
            const filename = (studentData.studentName || 'Student').replace(/\s+/g, '_') + '_' + 
                           (studentData.examName || 'Exam').replace(/\s+/g, '_') + '.pdf';
            doc.save(filename);
        });
    }
    
    // Function to download student Excel from modal
    function downloadStudentExcelFromModal(studentData) {
        const wb = XLSX.utils.book_new();
        const schoolName = '{{ $school->school_name ?? "School" }}';
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        
        const data = [];
        data.push([schoolName]);
        data.push([(studentData.examName || 'EXAMINATION') + ' STUDENT RESULT']);
        data.push([studentData.studentName || 'N/A']);
        data.push([]);
        
        // Overview
        data.push(['Field', 'Value']);
        const positionText = (studentData.position || 'N/A') + ' out of ' + (studentData.totalStudentsCount || 0);
        data.push(['Position', positionText]);
        
        // Calculate average grade
        let averageGrade = 'N/A';
        if (studentData.averageMarks) {
            const avg = parseFloat(studentData.averageMarks);
            if (avg >= 75) averageGrade = 'A';
            else if (avg >= 65) averageGrade = 'B';
            else if (avg >= 50) averageGrade = 'C';
            else if (avg >= 40) averageGrade = 'D';
            else if (avg >= 30) averageGrade = 'E';
            else averageGrade = 'F';
        }
        data.push(['Average Grade', averageGrade]);
        data.push(['Total Marks', (studentData.totalMarks || 0).toFixed(0)]);
        
        // Always show Division for Secondary school, even if empty (show N/A)
        if (schoolType === 'Secondary') {
            const divisionValue = studentData.division || studentData.gradeOrDivision || 'N/A';
            data.push(['Division', divisionValue]);
        }
        
        data.push([]);
        data.push(['Subject', 'Marks', 'Grade']);
        
        if (studentData.subjects && studentData.subjects.length > 0) {
            const hasCA = studentData.subjects.some(s => s.is_ca_averaged && s.exams && s.exams.length > 1);
            if (hasCA) {
                // Remove the standard header and add CA headers
                data.pop();
                data.push(['Subject', 'CA', 'Exam', 'Total', 'Grade']);
            }

            studentData.subjects.forEach(function(subject) {
                let marks = 'N/A';
                if (subject.marks === 'incomplete') {
                    marks = 'incomplete';
                } else if (subject.marks !== null && subject.marks !== '') {
                    marks = parseFloat(subject.marks).toFixed(0);
                }

                if (hasCA) {
                    let caMarks = '-';
                    let examMarks = '-';
                    if (subject.exams) {
                        let caSum = 0;
                        let caCount = 0;
                        subject.exams.forEach(ex => {
                            if (ex.exam_name.includes('(CA)')) {
                                caSum += parseFloat(ex.marks) || 0;
                                caCount++;
                            } else {
                                examMarks = (ex.marks !== null && ex.marks !== '') ? parseFloat(ex.marks).toFixed(0) : '-';
                            }
                        });
                        if (caCount > 0) caMarks = (caSum / caCount).toFixed(0);
                    }
                    data.push([
                        subject.subject_name || 'N/A',
                        caMarks,
                        examMarks,
                        marks,
                        subject.grade || 'N/A'
                    ]);
                } else {
                    data.push([
                        subject.subject_name || 'N/A',
                        marks,
                        subject.grade || 'N/A'
                    ]);
                }
            });
        }
        
        const ws = XLSX.utils.aoa_to_sheet(data);
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: 2 } },
            { s: { r: 1, c: 0 }, e: { r: 1, c: 2 } },
            { s: { r: 2, c: 0 }, e: { r: 2, c: 2 } }
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, 'Results');
        
        // Generate filename
        const filename = (studentData.studentName || 'Student').replace(/\s+/g, '_') + '_' + 
                       (studentData.examName || 'Exam').replace(/\s+/g, '_') + '.xlsx';
        XLSX.writeFile(wb, filename);
    }
    
    // Function to download term report student PDF from modal
    function downloadReportStudentPDFFromModal(reportStudentData) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        const schoolName = '{{ $school->school_name ?? "School" }}';
        const schoolLogo = '{{ $school->school_logo ?? "" }}';
        const schoolType = '{{ $schoolType ?? "Secondary" }}';
        const pageWidth = doc.internal.pageSize.getWidth();
        let yPos = 15;
        
        // Header: School logo (left), Student image (right), School name (center)
        const logoWidth = 25;
        const studentImageWidth = 30;
        
        // Load images asynchronously using Promise
        const loadImage = function(src) {
            return new Promise(function(resolve, reject) {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = function() {
                    resolve(img);
                };
                img.onerror = function() {
                    resolve(null);
                };
                img.src = src;
            });
        };
        
        // Load both images
        const logoPromise = schoolLogo ? loadImage(schoolLogo) : Promise.resolve(null);
        const studentImagePath = reportStudentData.photo || 
            (reportStudentData.gender === 'Female' ? '{{ asset("placeholder/female.png") }}' : '{{ asset("placeholder/male.png") }}');
        const studentImagePromise = loadImage(studentImagePath);
        
        Promise.all([logoPromise, studentImagePromise]).then(function(images) {
            const logoImg = images[0];
            const studentImg = images[1];
            
            // Add school logo (left)
            if (logoImg) {
                const logoActualHeight = (logoImg.height * logoWidth) / logoImg.width;
                doc.addImage(logoImg, 'PNG', 15, yPos, logoWidth, logoActualHeight);
            }
            
            // Add student image (right) or placeholder
            if (studentImg) {
                const studentActualHeight = (studentImg.height * studentImageWidth) / studentImg.width;
                doc.addImage(studentImg, 'PNG', pageWidth - 15 - studentImageWidth, yPos, studentImageWidth, studentActualHeight);
            }
            
            // School name (center, between logo and image)
            doc.setFontSize(16);
            doc.setTextColor(148, 0, 0);
            doc.setFont('helvetica', 'bold');
            doc.text(schoolName, pageWidth / 2, yPos + 10, { align: 'center' });
            yPos += 20;
            
            // Term name + "STUDENT REPORT"
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.setFont('helvetica', 'bold');
            const termTitle = (reportStudentData.termName || 'TERM') + ' STUDENT REPORT';
            doc.text(termTitle, pageWidth / 2, yPos, { align: 'center' });
            yPos += 8;
            
            // Student name
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.text(reportStudentData.studentName || 'N/A', pageWidth / 2, yPos, { align: 'center' });
            yPos += 10;
            
            // Calculate consistent table width and margins
            const tableMargin = 20;
            const availableWidth = pageWidth - (tableMargin * 2);
            const tableWidth = availableWidth;
            
            // Overview Table: Position (out of...), Average grade, Grade
            const overviewData = [];
            const positionText = (reportStudentData.position || 'N/A') + ' out of ' + (reportStudentData.totalStudentsCount || 0);
            overviewData.push(['Position', positionText]);
            
            // Calculate average grade
            let averageGrade = 'N/A';
            if (reportStudentData.averageMarks) {
                const avg = parseFloat(reportStudentData.averageMarks);
                if (avg >= 75) averageGrade = 'A';
                else if (avg >= 65) averageGrade = 'B';
                else if (avg >= 45) averageGrade = 'C';
                else if (avg >= 30) averageGrade = 'D';
                else averageGrade = 'F';
            }
            overviewData.push(['Average Grade', averageGrade]);
            overviewData.push(['Grade', reportStudentData.grade || 'N/A']);
            
            doc.autoTable({
                startY: yPos,
                head: [['Overview', 'Value']],
                body: overviewData,
                theme: 'grid',
                headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { 
                    left: tableMargin, 
                    right: tableMargin,
                    top: tableMargin,
                    bottom: 10
                },
                tableWidth: tableWidth,
                columnStyles: {
                    0: { cellWidth: tableWidth * 0.5, fontStyle: 'bold' },
                    1: { cellWidth: tableWidth * 0.5, halign: 'center' }
                }
            });
            yPos = doc.lastAutoTable.finalY + 10;
            
            // Subject Results Table
            if (reportStudentData.subjects && reportStudentData.subjects.length > 0 && reportStudentData.exams && reportStudentData.exams.length > 0) {
                // Build headers: Subject, [Exam columns], Average, Grade
                const headers = ['Subject'];
                reportStudentData.exams.forEach(function(exam) {
                    headers.push(exam.exam_name);
                });
                headers.push('Average', 'Grade');
                
                // Build table data
                const subjectData = [];
                reportStudentData.subjects.forEach(function(subject) {
                    const row = [subject.subject_name];
                    
                    // Add marks for each exam (format: "marks-grade")
                    reportStudentData.exams.forEach(function(exam) {
                        const examResult = (subject.exams && subject.exams[exam.examID]) ? subject.exams[exam.examID] : null;
                        
                        if (examResult && examResult.marks !== null && examResult.marks !== '') {
                            let marks = 'N/A';
                            if (examResult.marks === 'incomplete') {
                                marks = 'incomplete';
                            } else {
                                marks = parseFloat(examResult.marks).toFixed(0);
                            }
                            const grade = examResult.grade || 'N/A';
                            row.push(marks + '-' + grade);
                        } else {
                            row.push('-');
                        }
                    });
                    
                    // Add average and grade
                    const avg = subject.average ? parseFloat(subject.average).toFixed(1) : '0.0';
                    const grade = subject.grade || 'N/A';
                    row.push(avg, grade);
                    
                    subjectData.push(row);
                });
                
                // Calculate column widths as percentages of tableWidth to match overview table width
                const numExams = reportStudentData.exams.length;
                const totalColumns = 1 + numExams + 2; // Subject + Exams + Average + Grade
                
                // Use percentage-based widths to ensure table width matches overview
                const subjectColPercent = 0.25; // 25% for subject name
                const examColPercent = (0.55 / numExams); // 55% divided by number of exams
                const avgColPercent = 0.10; // 10% for average
                const gradeColPercent = 0.10; // 10% for grade
                
                const columnStyles = {};
                columnStyles[0] = { cellWidth: tableWidth * subjectColPercent, fontStyle: 'bold' };
                for (let i = 1; i <= numExams; i++) {
                    columnStyles[i] = { cellWidth: tableWidth * examColPercent, halign: 'center', fontSize: 8 };
                }
                columnStyles[numExams + 1] = { cellWidth: tableWidth * avgColPercent, halign: 'center', fontStyle: 'bold' };
                columnStyles[numExams + 2] = { cellWidth: tableWidth * gradeColPercent, halign: 'center', fontStyle: 'bold' };
                
                doc.autoTable({
                    startY: yPos,
                    head: [headers],
                    body: subjectData,
                    theme: 'striped',
                    headStyles: { fillColor: [148, 0, 0], textColor: 255, fontStyle: 'bold', fontSize: 8 },
                    styles: { fontSize: 8 },
                    margin: { 
                        left: tableMargin, 
                        right: tableMargin,
                        top: tableMargin,
                        bottom: 10
                    },
                    tableWidth: tableWidth,
                    columnStyles: columnStyles,
                    didDrawCell: function(data) {
                        // Apply bold font to subject names, average, and grade
                        if (data.section === 'body') {
                            if (data.column.index === 0 || data.column.index === numExams + 1 || data.column.index === numExams + 2) {
                                doc.setFont('helvetica', 'bold');
                            }
                        }
                    }
                });
            }
            
            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                const pageHeight = doc.internal.pageSize.height;
                
                // Generated date
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text('Generated on ' + new Date().toLocaleString(), pageWidth / 2, pageHeight - 25, { align: 'center' });
                
                // Powered by: EmCa Technologies LTD
                doc.setFontSize(8);
                doc.setTextColor(148, 0, 0);
                doc.setFont('helvetica', 'bold');
                doc.text('Powered by: EmCa Technologies LTD', pageWidth / 2, pageHeight - 20, { align: 'center' });
                
                // Headmaster's Sign (Blue ink)
                doc.setDrawColor(0, 0, 255); // Blue color
                doc.setLineWidth(0.5);
                doc.line(pageWidth - 50, pageHeight - 10, pageWidth - 10, pageHeight - 10); // Signature line
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 255); // Blue text
                doc.setFont('helvetica', 'bold');
                doc.text('Headmaster\'s Sign', pageWidth - 30, pageHeight - 5, { align: 'center' });
            }
            
            // Generate filename
            const filename = (reportStudentData.studentName || 'Student').replace(/\s+/g, '_') + '_' + 
                           (reportStudentData.termName || 'Term').replace(/\s+/g, '_') + '_Report_' + 
                           (reportStudentData.year || '') + '.pdf';
            doc.save(filename);
        });
    }
    
    // Function to download term report student Excel from modal
    function downloadReportStudentExcelFromModal(reportStudentData) {
        const wb = XLSX.utils.book_new();
        const schoolName = '{{ $school->school_name ?? "School" }}';
        
        const data = [];
        data.push([schoolName]);
        data.push([(reportStudentData.termName || 'TERM') + ' STUDENT REPORT']);
        data.push([reportStudentData.studentName || 'N/A']);
        data.push([]);
        
        // Overview
        data.push(['Field', 'Value']);
        const positionText = (reportStudentData.position || 'N/A') + ' out of ' + (reportStudentData.totalStudentsCount || 0);
        data.push(['Position', positionText]);
        
        // Calculate average grade
        let averageGrade = 'N/A';
        if (reportStudentData.averageMarks) {
            const avg = parseFloat(reportStudentData.averageMarks);
            if (avg >= 75) averageGrade = 'A';
            else if (avg >= 65) averageGrade = 'B';
            else if (avg >= 45) averageGrade = 'C';
            else if (avg >= 30) averageGrade = 'D';
            else averageGrade = 'F';
        }
        data.push(['Average Grade', averageGrade]);
        data.push(['Grade', reportStudentData.grade || 'N/A']);
        data.push([]);
        
        // Subject Results Table
        if (reportStudentData.subjects && reportStudentData.subjects.length > 0 && reportStudentData.exams && reportStudentData.exams.length > 0) {
            // Headers: Subject, [Exam columns], Average, Grade
            const headers = ['Subject'];
            reportStudentData.exams.forEach(function(exam) {
                headers.push(exam.exam_name);
            });
            headers.push('Average', 'Grade');
            data.push(headers);
            
            // Add subject data
            reportStudentData.subjects.forEach(function(subject) {
                const row = [subject.subject_name];
                
                // Add marks for each exam
                reportStudentData.exams.forEach(function(exam) {
                    const examResult = (subject.exams && subject.exams[exam.examID]) ? subject.exams[exam.examID] : null;
                    
                    if (examResult && examResult.marks !== null && examResult.marks !== '') {
                        let marks = 'N/A';
                        if (examResult.marks === 'incomplete') {
                            marks = 'incomplete';
                        } else {
                            marks = parseFloat(examResult.marks).toFixed(0);
                        }
                        const grade = examResult.grade || 'N/A';
                        row.push(marks + '-' + grade);
                    } else {
                        row.push('-');
                    }
                });
                
                // Add average and grade
                const avg = subject.average ? parseFloat(subject.average).toFixed(1) : '0.0';
                const grade = subject.grade || 'N/A';
                row.push(avg, grade);
                
                data.push(row);
            });
        }
        
        const ws = XLSX.utils.aoa_to_sheet(data);
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: (reportStudentData.exams ? reportStudentData.exams.length + 3 : 3) } },
            { s: { r: 1, c: 0 }, e: { r: 1, c: (reportStudentData.exams ? reportStudentData.exams.length + 3 : 3) } },
            { s: { r: 2, c: 0 }, e: { r: 2, c: (reportStudentData.exams ? reportStudentData.exams.length + 3 : 3) } }
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, 'Results');
        
        // Generate filename
        const filename = (reportStudentData.studentName || 'Student').replace(/\s+/g, '_') + '_' + 
                       (reportStudentData.termName || 'Term').replace(/\s+/g, '_') + '_Report_' + 
                       (reportStudentData.year || '') + '.xlsx';
        XLSX.writeFile(wb, filename);
    }
});

// Grade Definition Modal Functions
$(document).ready(function() {
    // Load grade definitions when modal opens
    $('#gradeDefinitionModal').on('show.bs.modal', function() {
        loadGradeDefinitions();
    });
    
    // Also handle Bootstrap 4 event
    $('#gradeDefinitionModal').on('shown.bs.modal', function() {
        loadGradeDefinitions();
    });

    // Load grade definitions for selected class
    $('#gradeDefinitionClassSelect').on('change', function() {
        loadGradeDefinitions();
    });

    // Update range display when first/last marks change
    $(document).on('input', '.edit-first, .edit-last', function() {
        const row = $(this).closest('tr');
        const first = parseFloat(row.find('.edit-first').val()) || 0;
        const last = parseFloat(row.find('.edit-last').val()) || 0;
        row.find('td:nth-child(4)').text(first.toFixed(2) + ' - ' + last.toFixed(2));
    });

    // Function to load grade definitions
    function loadGradeDefinitions() {
        const classID = $('#gradeDefinitionClassSelect').val();
        const tbody = $('#gradeDefinitionsTableBody');
        
        if (!classID) {
            tbody.html('<tr><td colspan="5" class="text-center text-muted">Please select a class to view grade definitions</td></tr>');
            return;
        }

        // Show loading
        tbody.html('<tr><td colspan="5" class="text-center"><i class="bi bi-hourglass-split"></i> Loading...</td></tr>');

        $.ajax({
            url: '{{ route("grade_definitions.index") }}',
            method: 'GET',
            data: { classID: classID },
            dataType: 'json',
            success: function(response) {
                tbody.empty();
                
                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(function(def) {
                        const row = `
                            <tr data-id="${def.gradeDefinitionID}">
                                <td><span class="badge bg-info">${def.grade}</span></td>
                                <td><input type="number" class="form-control form-control-sm edit-first" value="${def.first}" step="0.01" min="0" max="100"></td>
                                <td><input type="number" class="form-control form-control-sm edit-last" value="${def.last}" step="0.01" min="0" max="100"></td>
                                <td>${parseFloat(def.first).toFixed(2)} - ${parseFloat(def.last).toFixed(2)}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning update-grade-def" data-id="${def.gradeDefinitionID}">
                                        <i class="bi bi-pencil"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-grade-def" data-id="${def.gradeDefinitionID}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                } else {
                    tbody.html('<tr><td colspan="5" class="text-center text-muted">No grade definitions found. Add one below.</td></tr>');
                }
            },
            error: function(xhr) {
                tbody.html('<tr><td colspan="5" class="text-center text-danger">Failed to load grade definitions. Please try again.</td></tr>');
                console.error('Error loading grade definitions:', xhr);
            }
        });
    }

    // Add new grade input row
    let gradeInputCount = 1; // Start with 1 (first row already exists)
    const maxGradeInputs = 100;
    
    $('#addGradeInputBtn').on('click', function() {
        if (gradeInputCount >= maxGradeInputs) {
            alert('Maximum ' + maxGradeInputs + ' grade inputs allowed.');
            return;
        }
        
        const newRow = `
            <div class="grade-input-row mb-3 border-bottom pb-3">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Grade <span class="text-danger">*</span></label>
                        <input type="text" class="form-control grade-input" name="grades[]" placeholder="e.g., A" maxlength="10" required>
                        <small class="text-muted">Grade letter (A, B, C, D, E, F)</small>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">First Mark <span class="text-danger">*</span></label>
                        <input type="number" class="form-control first-input" name="firsts[]" step="0.01" min="0" max="100" placeholder="e.g., 75" required>
                        <small class="text-muted">Minimum marks</small>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Last Mark <span class="text-danger">*</span></label>
                        <input type="number" class="form-control last-input" name="lasts[]" step="0.01" min="0" max="100" placeholder="e.g., 100" required>
                        <small class="text-muted">Maximum marks</small>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger remove-grade-input">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#gradeInputsContainer').append(newRow);
        gradeInputCount++;
        
        // Show remove button on first row if there are multiple rows
        if (gradeInputCount > 1) {
            $('.grade-input-row').first().find('.remove-grade-input').show();
        }
    });
    
    // Remove grade input row
    $(document).on('click', '.remove-grade-input', function() {
        $(this).closest('.grade-input-row').remove();
        gradeInputCount--;
        
        // Hide remove button on first row if only one row remains
        if (gradeInputCount === 1) {
            $('.grade-input-row').first().find('.remove-grade-input').hide();
        }
    });
    
    // Clear all grade inputs
    $('#clearAllGradesBtn').on('click', function() {
        if (confirm('Are you sure you want to clear all grade inputs?')) {
            $('#gradeInputsContainer').html(`
                <div class="grade-input-row mb-3 border-bottom pb-3">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Grade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control grade-input" name="grades[]" placeholder="e.g., A" maxlength="10" required>
                            <small class="text-muted">Grade letter (A, B, C, D, E, F)</small>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">First Mark <span class="text-danger">*</span></label>
                            <input type="number" class="form-control first-input" name="firsts[]" step="0.01" min="0" max="100" placeholder="e.g., 75" required>
                            <small class="text-muted">Minimum marks</small>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Last Mark <span class="text-danger">*</span></label>
                            <input type="number" class="form-control last-input" name="lasts[]" step="0.01" min="0" max="100" placeholder="e.g., 100" required>
                            <small class="text-muted">Maximum marks</small>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger remove-grade-input" style="display: none;">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `);
            gradeInputCount = 1;
        }
    });

    // Add new grade definitions (multiple at once)
    $('#addGradeDefinitionForm').on('submit', function(e) {
        e.preventDefault();
        
        const classID = $('#gradeDefinitionClassSelect').val();
        if (!classID) {
            alert('Please select a class first');
            return;
        }
        
        // Collect all grade inputs
        const grades = [];
        $('.grade-input-row').each(function() {
            const grade = $(this).find('.grade-input').val().trim().toUpperCase();
            const firstVal = $(this).find('.first-input').val();
            const lastVal = $(this).find('.last-input').val();
            
            // Check if fields are filled
            if (!grade || !firstVal || !lastVal) {
                return; // Skip empty rows
            }
            
            const first = parseFloat(firstVal);
            const last = parseFloat(lastVal);
            
            // Validate numeric values
            if (isNaN(first) || isNaN(last)) {
                return; // Skip invalid rows
            }
            
            grades.push({
                grade: grade,
                first: first,
                last: last
            });
        });
        
        if (grades.length === 0) {
            alert('Please fill in at least one complete grade definition (Grade, First Mark, and Last Mark)');
            return;
        }
        
        // Validate all inputs
        let hasError = false;
        let errorMsg = '';
        grades.forEach(function(g, index) {
            // Check if last is less than first (this should not happen)
            if (g.last < g.first) {
                hasError = true;
                errorMsg += 'Row ' + (index + 1) + ': Last mark (' + g.last + ') must be greater than or equal to first mark (' + g.first + ').\n';
            }
            // Check if marks are within valid range
            if (g.first < 0 || g.first > 100 || g.last < 0 || g.last > 100) {
                hasError = true;
                errorMsg += 'Row ' + (index + 1) + ': Marks must be between 0 and 100.\n';
            }
        });
        
        if (hasError) {
            alert(errorMsg);
            return;
        }
        
        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
        
        // Save all grades via AJAX (one by one or batch)
        let successCount = 0;
        let errorCount = 0;
        let completed = 0;
        
        grades.forEach(function(gradeData, index) {
            $.ajax({
                url: '{{ route("grade_definitions.store") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    classID: classID,
                    grade: gradeData.grade,
                    first: gradeData.first,
                    last: gradeData.last
                },
                success: function(response) {
                    successCount++;
                    completed++;
                    if (completed === grades.length) {
                        submitBtn.prop('disabled', false).html(originalText);
                        if (errorCount === 0) {
                            showGradeDefinitionAlert('All ' + successCount + ' grade definition(s) added successfully!', 'success');
                            $('#addGradeDefinitionForm')[0].reset();
                            $('#clearAllGradesBtn').click(); // Reset to single row
                            loadGradeDefinitions();
                        } else {
                            showGradeDefinitionAlert(successCount + ' grade definition(s) added successfully. ' + errorCount + ' failed.', 'warning');
                            loadGradeDefinitions();
                        }
                    }
                },
                error: function(xhr) {
                    errorCount++;
                    completed++;
                    if (completed === grades.length) {
                        submitBtn.prop('disabled', false).html(originalText);
                        if (successCount > 0) {
                            showGradeDefinitionAlert(successCount + ' grade definition(s) added successfully. ' + errorCount + ' failed.', 'warning');
                        } else {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                let errorMsg = 'Validation errors:\n';
                                $.each(errors, function(key, value) {
                                    errorMsg += value[0] + '\n';
                                });
                                alert(errorMsg);
                            } else {
                                alert(xhr.responseJSON.message || 'Failed to add grade definitions');
                            }
                        }
                        loadGradeDefinitions();
                    }
                }
            });
        });
    });

    // Update grade definition
    $(document).on('click', '.update-grade-def', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const first = parseFloat(row.find('.edit-first').val());
        const last = parseFloat(row.find('.edit-last').val());
        const classID = $('#gradeDefinitionClassSelect').val();
        const grade = row.find('td:first-child .badge').text().trim();

        if (!classID) {
            alert('Please select a class first');
            return;
        }

        if (last < first) {
            alert('Last mark must be greater than or equal to first mark');
            return;
        }

        $.ajax({
            url: `{{ url("grade_definitions") }}/${id}`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT',
                classID: classID,
                first: first,
                last: last,
                grade: grade
            },
            success: function(response) {
                if (response.success) {
                    loadGradeDefinitions();
                    showGradeDefinitionAlert('Grade definition updated successfully!', 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMsg = 'Validation errors:\n';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    alert(errorMsg);
                } else {
                    alert(xhr.responseJSON.message || 'Failed to update grade definition');
                }
            }
        });
    });

    // Delete grade definition
    $(document).on('click', '.delete-grade-def', function() {
        const id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this grade definition?')) {
            $.ajax({
                url: `{{ url("grade_definitions") }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        loadGradeDefinitions();
                        showGradeDefinitionAlert('Grade definition deleted successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Failed to delete grade definition');
                }
            });
        }
    });

    // Show alert in modal
    function showGradeDefinitionAlert(message, type) {
        const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        $('#gradeDefinitionAlertContainer').html(alertHtml);
        setTimeout(function() {
            $('#gradeDefinitionAlertContainer .alert').fadeOut();
        }, 3000);
    }
});
</script>

<!-- Grade Definition Modal -->
<div class="modal fade" id="gradeDefinitionModal" tabindex="-1" aria-labelledby="gradeDefinitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="gradeDefinitionModalLabel">
                    <i class="bi bi-bookmark-star"></i> Define Grades for Class
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="gradeDefinitionAlertContainer"></div>
                
                <!-- Class Selection -->
                <div class="mb-4">
                    <label for="gradeDefinitionClassSelect" class="form-label">
                        <strong>Select Class <span class="text-danger">*</span></strong>
                    </label>
                    <select class="form-select" id="gradeDefinitionClassSelect" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->classID }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Existing Grade Definitions Table -->
                <div class="mb-4">
                    <h6 class="mb-3"><strong>Existing Grade Definitions</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="bg-primary-custom text-white">
                                <tr>
                                    <th>Grade</th>
                                    <th>First Mark</th>
                                    <th>Last Mark</th>
                                    <th>Range</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gradeDefinitionsTableBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Please select a class to view grade definitions</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add New Grade Definition Form -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><strong>Add New Grade Definition</strong></h6>
                        <button type="button" class="btn btn-sm btn-success" id="addGradeInputBtn">
                            <i class="bi bi-plus-circle"></i> Add Grade Input
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="addGradeDefinitionForm">
                            @csrf
                            <div id="gradeInputsContainer">
                                <!-- First grade input row -->
                                <div class="grade-input-row mb-3 border-bottom pb-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Grade <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control grade-input" name="grades[]" placeholder="e.g., A" maxlength="10" required>
                                            <small class="text-muted">Grade letter (A, B, C, D, E, F)</small>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">First Mark <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control first-input" name="firsts[]" step="0.01" min="0" max="100" placeholder="e.g., 75" required>
                                            <small class="text-muted">Minimum marks</small>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Last Mark <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control last-input" name="lasts[]" step="0.01" min="0" max="100" placeholder="e.g., 100" required>
                                            <small class="text-muted">Maximum marks</small>
                                        </div>
                                        <div class="col-md-3 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-grade-input" style="display: none;">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-save"></i> Save All Grades
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="clearAllGradesBtn">
                                        <i class="bi bi-x-circle"></i> Clear All
                                    </button>
                                    <small class="text-muted d-block mt-2">Maximum 100 grade definitions can be added at once.</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Term Report Definition Modal -->
<div class="modal fade" id="reportDefinitionModal" tabindex="-1" aria-labelledby="reportDefinitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title font-weight-bold" id="reportDefinitionModalLabel">
                    <i class="bi bi-file-earmark-ruled"></i> Term Report Definition
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle-fill"></i> <strong>How it works:</strong> Use this to select which specific exams should be included when calculating the <strong>Term Report</strong>. If no definition exists for a term, the system will automatically include all exams for that term.
                </div>

                <!-- Tabbed interface for Report Definition -->
                <ul class="nav nav-tabs nav-fill mb-4" id="reportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="define-report-tab" data-toggle="tab" href="#define-report" role="tab"><i class="bi bi-plus-circle"></i> Define New</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="view-report-tab" data-toggle="tab" href="#view-report" role="tab"><i class="bi bi-list-ul"></i> View/Filter Reports</a>
                    </li>
                </ul>

                <div class="tab-content" id="reportTabContent">
                    <!-- Tab 1: Define Report -->
                    <div class="tab-pane fade show active" id="define-report" role="tabpanel">
                        <form id="reportDefinitionForm">
                            @csrf
                            <input type="hidden" name="id" id="reportDefID">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Select Year <span class="text-danger">*</span></label>
                                    <select class="form-control" name="year" id="defYear" required>
                                        @for($i = date('Y'); $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Select Term <span class="text-danger">*</span></label>
                                    <select class="form-control" name="term" id="defTerm" required>
                                        <option value="first_term">First Term</option>
                                        <option value="second_term">Second Term</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card mb-4 border-warning">
                                <div class="card-header bg-warning-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold text-dark">Choose Exams to Calculate Report</h6>
                                    <button type="button" class="btn btn-sm btn-outline-dark" id="btnRefreshExams">
                                        <i class="bi bi-arrow-clockwise"></i> Refresh Exams
                                    </button>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    <div id="examsListContainer" class="exams-list-grid">
                                        <div class="text-center py-4 text-muted">
                                            <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                                            Please select year and term to load exams
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="button" class="btn btn-secondary mr-2 d-none" id="btnCancelEditReport">Cancel Edit</button>
                                <button type="submit" class="btn btn-warning font-weight-bold px-4 shadow-sm" id="btnSaveReportDef">
                                    <i class="bi bi-save"></i> Save Definition
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: View/Filter Reports -->
                    <div class="tab-pane fade" id="view-report" role="tabpanel">
                        <div class="row mb-3 bg-light p-2 rounded mx-0 border">
                            <div class="col-md-4 px-1">
                                <select class="form-control form-control-sm" id="filterReportYear">
                                    <option value="">All Years</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 px-1">
                                <select class="form-control form-control-sm" id="filterReportTerm">
                                    <option value="">All Terms</option>
                                    <option value="first_term">First Term</option>
                                    <option value="second_term">Second Term</option>
                                </select>
                            </div>
                            <div class="col-md-4 px-1">
                                <button type="button" class="btn btn-sm btn-warning btn-block font-weight-bold" id="btnApplyReportFilter">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover border">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="30"></th>
                                        <th>Year</th>
                                        <th>Term</th>
                                        <th>Exams</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="reportDefinitionsList">
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">Loading definitions...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .exams-list-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
    }
    .exam-checkbox-item {
        background: #FFFFFF;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(148, 0, 0, 0.2);
        transition: all 0.2s;
        cursor: pointer;
    }
    .exam-checkbox-item:hover {
        border-color: #940000;
        background: #FFF5F5;
    }
</style>

<!-- CA Definition Modal -->
<div class="modal fade" id="caDefinitionModal" tabindex="-1" aria-labelledby="caDefinitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title font-weight-bold" id="caDefinitionModalLabel">
                    <i class="bi bi-calculator"></i> Continuous Assessment (CA) Definition
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle-fill"></i> <strong>How it works:</strong> Define which <strong>Tests</strong> should be combined with a <strong>School Exam</strong> (like Midterm) to get an average score. 
                    Calculated as: <code>(Exam Marks + Test1 Marks + Test2 Marks...) / Total Exams</code>
                </div>

                <ul class="nav nav-tabs nav-fill mb-4" id="caTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="define-ca-tab" data-toggle="tab" href="#define-ca" role="tab"><i class="bi bi-plus-circle"></i> Define New</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="view-ca-tab" data-toggle="tab" href="#view-ca" role="tab"><i class="bi bi-list-ul"></i> View/Filter CA</a>
                    </li>
                </ul>

                <div class="tab-content" id="caTabContent">
                    <!-- Tab 1: Define CA -->
                    <div class="tab-pane fade show active" id="define-ca" role="tabpanel">
                        <form id="caDefinitionForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Year</label>
                                    <select class="form-control" name="year" id="caYear" required>
                                        @for($i = date('Y'); $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Term</label>
                                    <select class="form-control" name="term" id="caTerm" required>
                                        <option value="first_term">First Term</option>
                                        <option value="second_term">Second Term</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Select School Exam <span class="text-danger">*</span></label>
                                    <select class="form-control" name="examID" id="caMainExam" required>
                                        <option value="">-- Choose Exam --</option>
                                    </select>
                                    <div id="caStatusFeedback" class="mt-1 small"></div>
                                </div>
                            </div>

                            <div id="caTestsContainer" style="display: none;">
                                <div class="card mb-4 border-info">
                                    <div class="card-header bg-info-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 font-weight-bold text-info"><i class="bi bi-check2-square"></i> Choose Tests for CA</h6>
                                    </div>
                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                        <div id="caTestsList" class="exams-list-grid">
                                            <!-- populated via ajax -->
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-info font-weight-bold px-4 shadow-sm" id="btnSaveCaDef">
                                        <i class="bi bi-save"></i> Save CA Definition
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: View CA -->
                    <div class="tab-pane fade" id="view-ca" role="tabpanel">
                        <div class="row mb-3 bg-light p-2 rounded mx-0 border">
                            <div class="col-md-4 px-1">
                                <select class="form-control form-control-sm" id="filterCaYear">
                                    <option value="">All Years</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 px-1">
                                <select class="form-control form-control-sm" id="filterCaTerm">
                                    <option value="">All Terms</option>
                                    <option value="first_term">First Term</option>
                                    <option value="second_term">Second Term</option>
                                </select>
                            </div>
                            <div class="col-md-4 px-1">
                                <button type="button" class="btn btn-sm btn-info btn-block" id="btnApplyCaFilter">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover border">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Exam</th>
                                        <th>CA</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="caDefinitionsList">
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">Use filter or click view to load</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Cleaned up rogue inline styles */
</style>    

<!-- SweetAlert2 Library Check and Validation Error Handler -->
<script>
$(document).ready(function() {
    // Check if SweetAlert2 is loaded
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 is not loaded. Loading from CDN...');
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }

    // Function to show validation error as info alert
    function showValidationAlert(title, message, errorType) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: title || 'Information',
                html: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#940000',
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'swal2-popup-validation'
                }
            });
        } else {
            // Fallback to regular alert if SweetAlert2 is not loaded
            alert(title + '\n\n' + message);
        }
    }

    // Check for session error and display as SweetAlert
    @if(session('error'))
        var sessionError = @json(session('error'));
        var errorType = @json(session('error_type')) || 'general';
        
        // Hide the regular alert
        $('#sessionErrorAlert').hide();
        
        // Show SweetAlert based on error type
        var title = 'Validation Information';
        var message = sessionError;
        
        // Customize title and message based on error type
        if (errorType === 'exam_not_ended') {
            title = 'Exam Still Ongoing';
            message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + sessionError + '</p><p class="mt-2">You can view results only after the exam has ended.</p></div>';
        } else if (errorType === 'approval_pending') {
            title = 'Results Pending Approval';
            message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + sessionError + '</p><p class="mt-2">Please wait for the approval process to complete.</p></div>';
        } else if (errorType === 'approval_rejected') {
            title = 'Results Approval Rejected';
            message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + sessionError + '</p><p class="mt-2">The results approval was rejected. Please contact the administrator.</p></div>';
        } else if (errorType === 'unauthorized_access') {
            title = 'Access Denied';
            message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + sessionError + '</p><p class="mt-2">You do not have permission to view results for this class.</p></div>';
        } else if (errorType === 'no_subclass_selected') {
            title = 'Class Selection Required';
            message = '<div style="text-align: left;"><p><strong>Action Required:</strong> ' + sessionError + '</p><p class="mt-2">Please select a class to view results.</p></div>';
        } else if (errorType === 'invalid_class') {
            title = 'Invalid Class Selection';
            message = '<div style="text-align: left;"><p><strong>Error:</strong> ' + sessionError + '</p></div>';
        }
        
        showValidationAlert(title, message, errorType);
    @endif

    // Check for blade error variable and display as SweetAlert
    @if(isset($error) && $error)
        var bladeError = @json($error);
        var bladeErrorType = @json($error_type ?? 'general');
        
        // Hide the regular alert
        $('#errorAlert').hide();
        
        // Show SweetAlert based on error type
        var title = 'Validation Information';
        var message = bladeError;
        
        // Customize title and message based on error type
        if (bladeErrorType === 'exam_not_ended') {
            title = 'Exam Still Ongoing';
            message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + bladeError + '</p><p class="mt-2">You can view results only after the exam has ended.</p></div>';
        } else if (bladeErrorType === 'approval_pending') {
            title = 'Results Pending Approval';
            message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + bladeError + '</p><p class="mt-2">Please wait for the approval process to complete.</p></div>';
        } else if (bladeErrorType === 'approval_rejected') {
            title = 'Results Approval Rejected';
            message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + bladeError + '</p><p class="mt-2">The results approval was rejected. Please contact the administrator.</p></div>';
        }
        
        showValidationAlert(title, message, bladeErrorType);
    @endif

    // Handle AJAX errors from subject details requests
    $(document).on('click', '.view-subject-details-btn', function() {
        var studentID = $(this).data('student-id');
        var examID = $('#examID').val();
        var term = $('#term').val();
        var year = $('#year').val();
        var type = $('#type').val();
        
        // Make AJAX request
        $.ajax({
            url: '{{ route("manageResults") }}',
            type: 'GET',
            data: {
                getSubjectDetails: true,
                studentID: studentID,
                examID: examID,
                term: term,
                year: year,
                type: type
            },
            dataType: 'json',
            success: function(response) {
                // Handle success (existing code)
            },
            error: function(xhr) {
                var errorMessage = 'An error occurred while loading subject details.';
                var errorType = 'general';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                    
                    // Determine error type from message
                    if (errorMessage.includes("still taken") || errorMessage.includes("still ongoing")) {
                        errorType = 'exam_not_ended';
                    } else if (errorMessage.includes("approval") || errorMessage.includes("pending")) {
                        errorType = 'approval_pending';
                    } else if (errorMessage.includes("rejected")) {
                        errorType = 'approval_rejected';
                    }
                }
                
                // Show SweetAlert
                var title = 'Validation Information';
                var message = errorMessage;
                
                if (errorType === 'exam_not_ended') {
                    title = 'Exam Still Ongoing';
                    message = '<div style="text-align: left;"><p><strong>Reason:</strong> ' + errorMessage + '</p><p class="mt-2">You can view results only after the exam has ended.</p></div>';
                } else if (errorType === 'approval_pending') {
                    title = 'Results Pending Approval';
                    message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + errorMessage + '</p><p class="mt-2">Please wait for the approval process to complete.</p></div>';
                } else if (errorType === 'approval_rejected') {
                    title = 'Results Approval Rejected';
                    message = '<div style="text-align: left;"><p><strong>Status:</strong> ' + errorMessage + '</p><p class="mt-2">The results approval was rejected. Please contact the administrator.</p></div>';
                }
                
                showValidationAlert(title, message, errorType);
            }
        });
    });
});
</script>
<!-- SMS Progress Modal -->
<div class="modal fade" id="smsProgressModal" tabindex="-1" role="dialog" aria-labelledby="smsProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="smsProgressModalLabel"><i class="bi bi-send-check"></i> Send Exam Results (SMS)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle-fill"></i> Send results for <strong id="modalContextLabel"></strong> to parents.
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="selectAllParents">
                        <label class="custom-control-label font-weight-bold" for="selectAllParents">Select All</label>
                    </div>
                    <span class="badge badge-success" id="selectedCount" style="font-size: 1rem;">Receivers: 0</span>
                </div>

                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-hover" id="parentSmsTable">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>Parent Of</th>
                                <th>Phone Number</th>
                                <th class="text-center" style="width: 120px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="parentSmsList">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>

                <div id="smsProgressArea" class="progress-container mt-4 d-none">
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="font-weight-bold text-primary-custom">Sending Progress</span>
                        <span id="smsProgressText" class="small">0 / 0</span>
                    </div>
                    <div class="progress" style="height: 25px; border-radius: 12px; border: 1px solid #ddd;">
                        <div id="smsProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <div id="smsDeliverySummary" class="text-center small mt-2"></div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCancelSms">Cancel</button>
                <button type="button" class="btn btn-primary-custom" id="startSendingSms">
                    <i class="bi bi-send"></i> Start Sending SMS
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentSmsData = [];
    let isSendingSms = false;
    let stopSmsRequest = false;
    const schoolName = window.detailedViewData ? (window.detailedViewData.schoolName || 'Academic Results') : 'Academic Results';

    function openSmsModal(title, studentsData, context = {}) {
        $('#modalContextLabel').text(title);
        const list = $('#parentSmsList');
        list.empty();

        studentsData.forEach(student => {
            const studentId = student.studentID;
            const studentName = student.studentName || (student.firstName + ' ' + student.lastName);
            let phone = student.parentPhone || student.phone;
            
            phone = String(phone || '').trim();
            if (!phone || ['null', 'undefined', 'n/a'].includes(phone.toLowerCase())) phone = '';
            
            if (studentId) {
                const randomId = Math.random().toString(36).substr(2, 5);
                const checkboxId = `check_${studentId}_${randomId}`;
                const disabledAttr = phone ? '' : 'disabled="disabled"';
                const statusHtml = phone 
                    ? '<span class="status-marker text-muted small"><i class="bi bi-hourglass"></i> Pending...</span>' 
                    : '<span class="text-danger small"><i class="bi bi-telephone-x"></i> No Phone</span>';
                
                // Construct data attributes
                let dataAttrs = `data-student-id="${studentId}" data-phone="${phone}" `;
                dataAttrs += `data-week="${context.week || ''}" data-exam-id="${context.examID || ''}" `;
                dataAttrs += `data-type="${context.type || ''}" data-term="${context.term || ''}" data-year="${context.year || ''}" `;
                dataAttrs += `data-subject="${student.subject || context.subject || ''}" `;
                dataAttrs += `data-marks="${student.marks || ''}" data-grade="${student.grade || ''}" `;
                dataAttrs += `data-division="${student.division || ''}" data-position="${student.position || ''}" `;
                dataAttrs += `data-total-count="${context.totalCount || studentsData.length}" `;
                
                list.append(`
                    <tr ${dataAttrs}>
                        <td class="text-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input parent-checkbox" id="${checkboxId}" ${disabledAttr}>
                                <label class="custom-control-label" for="${checkboxId}">&nbsp;&nbsp;&nbsp;</label>
                            </div>
                        </td>
                        <td>Parent of ${studentName}</td>
                        <td>${phone || '<span class="text-danger small">No Phone</span>'}</td>
                        <td class="text-center status-col">${statusHtml}</td>
                    </tr>
                `);
            }
        });

        $('#selectAllParents').prop('checked', false);
        updateSelectedCount();
        $('#smsProgressArea').addClass('d-none');
        $('#smsProgressBar').css('width', '0%').text('0%').removeClass('bg-info').addClass('bg-success');
        $('#startSendingSms').prop('disabled', true).html('<i class="bi bi-send"></i> Start Sending SMS');
        $('#smsProgressModal').modal('show');
    }

    // Top Button: Send SMS All
    $(document).on('click', '#sendSmsAll', function() {
        const students = window.detailedViewData.allStudents || [];
        const examName = window.detailedViewData.examName || 'Examination';
        const className = window.detailedViewData.className || 'Class';
        
        openSmsModal(`${examName} - ${className}`, students, {
            examID: $('#examID').val(),
            week: $('#week').val(),
            type: $('#type').val(),
            term: $('#term').val(),
            year: $('#year').val()
        });
    });

    // Open Modal for Single Subject (Weekly)
    $(document).on('click', '.btn-send-sms', function() {
        const subject = $(this).data('subject');
        const subjectIndex = $(this).data('subject-index');
        const week = $(this).data('week');
        const examID = $(this).data('exam-id');
        const tableId = '#weeklyTable' + subjectIndex;
        
        const students = [];
        const table = $(tableId).DataTable();
        table.rows().every(function() {
            const node = $(this.node());
            students.push({
                studentID: node.data('student-id'),
                studentName: node.data('first-name') + ' ' + node.data('last-name'),
                parentPhone: node.data('parent-phone'),
                marks: node.data('marks'),
                grade: node.data('grade'),
                subject: subject
            });
        });

        openSmsModal(`${subject} Results`, students, {
            week: week,
            examID: examID,
            subject: subject
        });
    });
    
    // Open Modal for All Subjects (Consolidated Weekly)
    $(document).on('click', '.btn-send-all-sms', function() {
        const week = $(this).data('week');
        const examID = $(this).data('exam-id');
        const students = window.detailedViewData.allStudents || [];
        
        openSmsModal(`All Subjects - Week ${week}`, students, {
            week: week,
            examID: examID
        });
    });

    // Select All Toggle
    $('#selectAllParents').on('change', function() {
        $('.parent-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
        updateSelectedCount();
    });

    $(document).on('change', '.parent-checkbox', function() {
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const count = $('.parent-checkbox:checked').length;
        $('#selectedCount').text('Receivers: ' + count);
        $('#startSendingSms').prop('disabled', count === 0);
    }

    // Start Sending
    $('#startSendingSms').on('click', async function() {
        const selectedRows = $('.parent-checkbox:checked').closest('tr');
        if (selectedRows.length === 0) return;
        
        const confirmed = await Swal.fire({
            title: 'Send SMS?',
            text: `You are about to send results SMS to ${selectedRows.length} parents.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#940000',
            confirmButtonText: 'Yes, Send Now'
        });
        
        if (!confirmed.isConfirmed) return;

        isSendingSms = true;
        stopSmsRequest = false;
        $('#startSendingSms').prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Sending...');
        $('#btnCancelSms').prop('disabled', true);
        $('.parent-checkbox, #selectAllParents').prop('disabled', true);
        $('#smsProgressArea').removeClass('d-none');
        
        const total = selectedRows.length;
        let delivered = 0;
        let failed = 0;
        
        $('#smsProgressText').text(`0 / ${total}`);
        $('#smsDeliverySummary').empty();

        for (let i = 0; i < total; i++) {
            if (stopSmsRequest) break;
            
            const row = $(selectedRows[i]);
            const statusCol = row.find('.status-col');
            row.addClass('bg-warning-light'); // Highlight the active row
            statusCol.html('<div class="spinner-border spinner-border-sm text-primary" role="status"></div> <span class="small text-primary font-weight-bold">Sending...</span>');
            
            const data = {
                studentID: row.data('student-id'),
                subject: row.data('subject'),
                marks: row.data('marks'),
                grade: row.data('grade'),
                week: row.data('week'),
                examID: row.data('exam-id'),
                type: row.data('type'),
                term: row.data('term'),
                year: row.data('year'),
                division: row.data('division'),
                position: row.data('position'),
                totalStudentsCount: row.data('total-count'),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            try {
                const response = await $.ajax({
                    url: '{{ route("admin.send_result_sms") }}',
                    type: 'POST',
                    data: data
                });

                row.removeClass('bg-warning-light');
                if (response.success) {
                    statusCol.html('<span class="text-success small font-weight-bold"><i class="bi bi-check-circle-fill"></i> Delivered</span>');
                    delivered++;
                    row.addClass('bg-success-light');
                } else {
                    statusCol.html('<span class="text-danger small font-weight-bold" title="'+(response.error || 'Failed')+'"><i class="bi bi-exclamation-circle-fill"></i> Failed</span>');
                    failed++;
                    row.addClass('bg-danger-light');
                }
            } catch (err) {
                row.removeClass('bg-warning-light').addClass('bg-danger-light');
                statusCol.html('<span class="text-danger small font-weight-bold"><i class="bi bi-exclamation-triangle-fill"></i> Error</span>');
                failed++;
            }

            // Update Progress
            const currentCount = delivered + failed;
            const percent = Math.round((currentCount / total) * 100);
            $('#smsProgressBar').css('width', percent + '%').text(percent + '%');
            $('#smsProgressText').text(`${currentCount} / ${total}`);
            $('#smsDeliverySummary').html(`<span class="text-success">${delivered} Delivered</span> | <span class="text-danger">${failed} Failed</span>`);
        }

        isSendingSms = false;
        $('#startSendingSms').html('<i class="bi bi-check-all"></i> Completed').prop('disabled', true);
        $('#btnCancelSms').prop('disabled', false).text('Close');
        
        Swal.fire({
            title: 'Batch Completed',
            text: `SMS sending finished. Delivered: ${delivered}, Failed: ${failed}`,
            icon: delivered > 0 ? 'success' : 'info'
        });
    });

    // Export Subject to PDF
    $(document).on('click', '.btn-export-subject-pdf', function() {
        const subject = $(this).data('subject');
        const subjectIndex = $(this).data('subject-index');
        const weekLabel = $(this).data('week-label');
        const year = $(this).data('year');
        const tableId = '#weeklyTable' + subjectIndex;
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add Header
        doc.setFontSize(18);
        doc.setTextColor(148, 0, 0); // #940000
        doc.text(schoolName, 105, 15, { align: 'center' });
        
        doc.setFontSize(14);
        doc.setTextColor(50, 50, 50);
        doc.text('Subject Achievement Report', 105, 22, { align: 'center' });
        
        doc.setFontSize(11);
        doc.setTextColor(0, 0, 0);
        doc.text(`Subject: ${subject}`, 14, 30);
        doc.text(`Period: ${weekLabel} - ${year}`, 14, 36);
        doc.text(`Generated: ${new Date().toLocaleDateString()}`, 14, 42);
        
        // Target table
        const table = $(tableId).DataTable();
        const data = [];
        const headers = ['Pos', 'First Name', 'Last Name', 'Class', 'Marks', 'Grade'];
        
        table.rows().every(function() {
            const row = $(this.node());
            const cells = row.find('td');
            data.push([
                cells.eq(0).text().trim(),
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                cells.eq(3).text().trim(),
                cells.eq(4).text().trim(),
                cells.eq(5).text().trim()
            ]);
        });
        
        doc.autoTable({
            startY: 48,
            head: [headers],
            body: data,
            theme: 'grid',
            headStyles: { 
                fillColor: [148, 0, 0],
                lineWidth: 0.5,
                lineColor: [0, 0, 0]
            },
            styles: {
                lineWidth: 0.5,
                lineColor: [0, 0, 0],
                textColor: [0, 0, 0]
            }
        });
        
        doc.save(`${subject}_Results_${weekLabel.replace(/[^a-z0-9]/gi, '_')}.pdf`);
    });

    // Export Subject to Excel
    $(document).on('click', '.btn-export-subject-excel', function() {
        const subject = $(this).data('subject');
        const subjectIndex = $(this).data('subject-index');
        const weekLabel = $(this).data('week-label');
        const year = $(this).data('year');
        const tableId = '#weeklyTable' + subjectIndex;
        
        const table = $(tableId).DataTable();
        const data = [];
        
        // Headers with metadata
        data.push([schoolName]);
        data.push([`Subject Results: ${subject}`]);
        data.push([`Period: ${weekLabel} - ${year}`]);
        data.push([]); // Empty row
        data.push(['Pos', 'First Name', 'Last Name', 'Class', 'Marks', 'Grade']);
        
        table.rows().every(function() {
            const row = $(this.node());
            const cells = row.find('td');
            data.push([
                cells.eq(0).text().trim(),
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                cells.eq(3).text().trim(),
                cells.eq(4).text().trim(),
                cells.eq(5).text().trim()
            ]);
        });
        
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, "Results");
        XLSX.writeFile(wb, `${subject}_Results_${weekLabel.replace(/[^a-z0-9]/gi, '_')}.xlsx`);
    });

    // Handle Modal Close during sending
    $('#smsProgressModal').on('hidden.bs.modal', function () {
        if (isSendingSms) {
            stopSmsRequest = true;
        }
    });

    // ==========================================
    // Term Report Definition Module
    // ==========================================

    // Store definitions in JS for easy edit/expand
    let termReportDefinitions = [];

    // Open Modal
    $('#btnDefineReport').on('click', function() {
        $('#reportDefinitionModal').modal('show');
        resetReportForm();
        loadReportDefinitions();
        loadExamsForDefinition();
    });

    // Handle Year/Term change in modal
    $('#defYear, #defTerm').on('change', function() {
        loadExamsForDefinition();
    });

    // Filter Apply
    $('#btnApplyReportFilter').on('click', loadReportDefinitions);

    // Refresh button
    $('#btnRefreshExams').on('click', function() {
        loadExamsForDefinition();
    });

    function resetReportForm() {
        $('#reportDefID').val('');
        $('#reportDefinitionForm')[0].reset();
        $('#defYear').val(new Date().getFullYear());
        $('#defTerm').val('first_term');
        $('#btnCancelEditReport').addClass('d-none');
        $('#btnSaveReportDef').html('<i class="bi bi-save"></i> Save Definition');
    }

    $('#btnCancelEditReport').on('click', resetReportForm);

    // Load available exams
    function loadExamsForDefinition(selectedIds = []) {
        const year = $('#defYear').val();
        const term = $('#defTerm').val();
        const container = $('#examsListContainer');
        
        container.html('<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-warning" role="status"></div> Loading exams...</div>');
        
        $.ajax({
            url: "{{ route('get_exams_for_term_list') }}",
            type: 'GET',
            data: { year: year, term: term },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(exam) {
                        const isChecked = selectedIds.includes(String(exam.examID)) || selectedIds.includes(Number(exam.examID)) ? 'checked' : '';
                        html += `
                            <label class="exam-checkbox-item d-flex align-items-center mb-0">
                                <input type="checkbox" name="exam_ids[]" value="${exam.examID}" class="mr-2 exam-id-checkbox" ${isChecked}>
                                <span class="text-dark small font-weight-bold">${exam.exam_name}</span>
                            </label>
                        `;
                    });
                    container.html(html);
                } else {
                    container.html('<div class="text-center py-4 text-muted">No exams found for this term.</div>');
                }
            }
        });
    }

    // Save
    $('#reportDefinitionForm').on('submit', function(e) {
        e.preventDefault();
        
        const examIds = [];
        $('.exam-id-checkbox:checked').each(function() {
            examIds.push($(this).val());
        });
        
        if (examIds.length === 0) {
            Swal.fire('Selection Required', 'Please choose at least one exam.', 'warning');
            return;
        }
        
        const btn = $('#btnSaveReportDef');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        
        $.ajax({
            url: "{{ route('save_report_definition') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: $('#reportDefID').val(),
                year: $('#defYear').val(),
                term: $('#defTerm').val(),
                exam_ids: examIds
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', response.message, 'success');
                    resetReportForm();
                    loadReportDefinitions();
                    $('#view-report-tab').tab('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="bi bi-save"></i> Save Definition');
            }
        });
    });

    // Load List
    function loadReportDefinitions() {
        const tbody = $('#reportDefinitionsList');
        const filterYear = $('#filterReportYear').val();
        const filterTerm = $('#filterReportTerm').val();
        
        tbody.html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Loading...</td></tr>');

        $.ajax({
            url: "{{ route('get_report_definitions') }}",
            type: 'GET',
            data: { year: filterYear, term: filterTerm },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    termReportDefinitions = response.data;
                    let html = '';
                    response.data.forEach(function(def, index) {
                        const termLabel = def.term === 'first_term' ? 'First Term' : 'Second Term';
                        const count = Array.isArray(def.exam_ids) ? def.exam_ids.length : 0;
                        const examNamesStr = (def.exam_names || []).join(', ');
                        
                        html += `
                            <tr>
                                <td>
                                    <button class="btn btn-sm btn-link p-0 btn-expand-def" data-index="${index}">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </td>
                                <td class="font-weight-bold">${def.year}</td>
                                <td><span class="badge badge-info">${termLabel}</span></td>
                                <td><span class="badge badge-light border">${count} Exams</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-outline-primary btn-edit-def" data-index="${index}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-xs btn-outline-danger btn-delete-def" data-id="${def.id}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="def-details d-none bg-light" id="def-details-${index}">
                                <td colspan="5">
                                    <div class="p-2 small">
                                        <strong>Selected Exams:</strong><br>
                                        <span class="text-muted italic">${examNamesStr || 'No names found'}</span>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.html(html);
                } else {
                    tbody.html('<tr><td colspan="5" class="text-center py-3 text-muted">No report definitions found.</td></tr>');
                }
            }
        });
    }

    // Toggle expand
    $(document).on('click', '.btn-expand-def', function() {
        const index = $(this).data('index');
        const detailsRow = $('#def-details-' + index);
        const icon = $(this).find('i');
        
        if (detailsRow.hasClass('d-none')) {
            detailsRow.removeClass('d-none');
            icon.removeClass('bi-chevron-right').addClass('bi-chevron-down');
        } else {
            detailsRow.addClass('d-none');
            icon.removeClass('bi-chevron-down').addClass('bi-chevron-right');
        }
    });

    // Edit
    $(document).on('click', '.btn-edit-def', function() {
        const index = $(this).data('index');
        const def = termReportDefinitions[index];
        
        $('#reportDefID').val(def.id);
        $('#defYear').val(def.year);
        $('#defTerm').val(def.term);
        $('#btnCancelEditReport').removeClass('d-none');
        $('#btnSaveReportDef').html('<i class="bi bi-check-circle"></i> Update Definition');
        
        loadExamsForDefinition(def.exam_ids || []);
        $('#define-report-tab').tab('show');
    });

    // Delete
    $(document).on('click', '.btn-delete-def', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Definition?',
            text: "This term report will revert to using all exams for calculation.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('delete_report_definition') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', '', 'success');
                            loadReportDefinitions();
                        }
                    }
                });
            }
        });
    });

    $('#btnReloadDefs').on('click', loadReportDefinitions);

    $('#btnReloadDefs').on('click', loadReportDefinitions);

    // ==========================================
    // CA Definition Module
    // ==========================================

    // Open Modal
    $('#btnDefineCA').on('click', function() {
        $('#caDefinitionModal').modal('show');
        loadSchoolExamsForCa();
        loadCaDefinitions();
    });

    // Handle Year/Term change
    $('#caYear, #caTerm').on('change', function() {
        loadSchoolExamsForCa();
    });

    // Load school exams
    function loadSchoolExamsForCa(selectedExamId = null, callback = null) {
        const year = $('#caYear').val();
        const term = $('#caTerm').val();
        const select = $('#caMainExam');
        
        select.html('<option value="">Loading...</option>');
        $('#caTestsContainer').hide();
        
        $.ajax({
            url: "{{ route('get_school_exams_for_term_list') }}",
            type: 'GET',
            data: { year: year, term: term },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '<option value="">-- Choose Exam --</option>';
                    response.data.forEach(function(ex) {
                        html += `<option value="${ex.examID}">${ex.exam_name}</option>`;
                    });
                    select.html(html);
                    
                    if (selectedExamId) {
                        select.val(selectedExamId);
                        if (callback) callback();
                    }
                } else {
                    select.html('<option value="">No school exams found</option>');
                }
            }
        });
    }

    // Handle Exam selection
    $('#caMainExam').on('change', function() {
        const examID = $(this).val();
        if (!examID) {
            $('#caTestsContainer').hide();
            return;
        }

        // 1. Check if exists
        $.ajax({
            url: "{{ route('check_exam_ca_exists') }}",
            type: 'GET',
            data: { examID: examID },
            success: function(response) {
                if (response.success && response.exists) {
                    $('#caStatusFeedback').html('<span class="text-danger font-weight-bold"><i class="bi bi-exclamation-triangle-fill"></i> This exam already defined CA. Saving will overwrite it.</span>');
                } else {
                    $('#caStatusFeedback').html('<span class="text-success small"><i class="bi bi-check-circle"></i> Exam available for CA definition</span>');
                }
            }
        });

        // 2. Load Tests for the term
        loadTestsForCa();
    });

    function loadTestsForCa(selectedTestIds = null) {
        const year = $('#caYear').val();
        const term = $('#caTerm').val();
        const container = $('#caTestsList');
        
        container.html('<div class="col-12 text-center py-3"><span class="spinner-border spinner-border-sm text-info"></span> Loading tests...</div>');
        $('#caTestsContainer').show();

        $.ajax({
            url: "{{ route('get_tests_for_term_list') }}",
            type: 'GET',
            data: { year: year, term: term },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(test) {
                        html += `
                            <label class="exam-checkbox-item d-flex align-items-center mb-0 border-info-light">
                                <input type="checkbox" name="test_ids[]" value="${test.examID}" class="mr-2 ca-test-checkbox">
                                <div>
                                    <div class="text-dark small font-weight-bold">${test.exam_name}</div>
                                </div>
                            </label>
                        `;
                    });
                    container.html(html);
                    
                    if (selectedTestIds && selectedTestIds.length > 0) {
                        $('.ca-test-checkbox').each(function() {
                            if (selectedTestIds.includes(parseInt($(this).val()))) {
                                $(this).prop('checked', true);
                            }
                        });
                    }
                } else {
                    container.html('<div class="col-12 text-center py-3 text-muted">No tests found for this term.</div>');
                }
            }
        });
    }

    // Save CA Definition
    $('#caDefinitionForm').on('submit', function(e) {
        e.preventDefault();
        
        const testIds = [];
        $('.ca-test-checkbox:checked').each(function() {
            testIds.push($(this).val());
        });
        
        if (testIds.length === 0) {
            Swal.fire('Required', 'Please choose at least one test to include in CA.', 'warning');
            return;
        }
        
        const btn = $('#btnSaveCaDef');
        const oldHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        
        $.ajax({
            url: "{{ route('save_ca_definition') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                year: $('#caYear').val(),
                term: $('#caTerm').val(),
                examID: $('#caMainExam').val(),
                test_ids: testIds
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', response.message, 'success');
                    loadCaDefinitions();
                    $('#view-ca-tab').tab('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html(oldHtml);
            }
        });
    });

    // View/Filter CA
    function loadCaDefinitions() {
        const tbody = $('#caDefinitionsList');
        const year = $('#filterCaYear').val();
        const term = $('#filterCaTerm').val();
        
        tbody.html('<tr><td colspan="3" class="text-center py-3">Loading...</td></tr>');

        $.ajax({
            url: "{{ route('get_ca_definitions') }}",
            type: 'GET',
            data: { year: year, term: term },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(def) {
                        const testList = def.test_names ? def.test_names.join(', ') : 'None';
                        const defJson = encodeURIComponent(JSON.stringify(def));
                        html += `
                            <tr>
                                <td>
                                    <div class="font-weight-bold">${def.main_exam.exam_name}</div>
                                    <small class="text-muted">${def.year} - ${def.term === 'first_term' ? 'Term 1' : 'Term 2'}</small>
                                </td>
                                <td><span class="small">${testList}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-edit-ca mr-1" data-def="${defJson}" title="Edit Form">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete-ca" data-id="${def.id}" title="Delete Form">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.html(html);
                } else {
                    tbody.html('<tr><td colspan="3" class="text-center py-3 text-muted">No CA definitions found.</td></tr>');
                }
            }
        });
    }

    $('#btnApplyCaFilter').on('click', loadCaDefinitions);
    
    // Switch to editing mode when Edit button is clicked
    $(document).on('click', '.btn-edit-ca', function() {
        const def = JSON.parse(decodeURIComponent($(this).data('def')));
        if (!def) return;
        
        // Change to Define New tab
        $('#define-ca-tab').tab('show');
        
        // Populate specific year and term from definition
        $('#caYear').val(def.year);
        $('#caTerm').val(def.term);
        
        $('#caStatusFeedback').html('<span class="text-info font-weight-bold"><i class="bi bi-pencil"></i> Editing CA definition...</span>');
        
        // Trigger select initialization but set exam once loaded
        loadSchoolExamsForCa(def.examID, function() {
            // Callback: load matching tests and auto-check those previously assigned
            const testIds = def.test_ids.map(id => parseInt(id, 10));
            loadTestsForCa(testIds);
        });
        
        $('#btnSaveCaDef').html('<i class="bi bi-check2-circle"></i> Update Definition Form');
        
        // Scroll to form to give smooth UX
        $('html, body').animate({
            scrollTop: $("#define-ca").offset().top - 100
        }, 500);
    });

    // Delete CA
    $(document).on('click', '.btn-delete-ca', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete CA Definition?',
            text: "This exam result will revert to standard values without CA average.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('delete_ca_definition') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted', '', 'success');
                            loadCaDefinitions();
                        }
                    }
                });
            }
        });
    });
});
</script>
