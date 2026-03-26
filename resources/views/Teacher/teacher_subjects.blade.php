@include('includes.teacher_nav')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Color scheme for #940000 */
    .bg-primary-custom {
        background-color: #940000 !important;
    }
    .text-primary-custom {
        color: #940000 !important;
    }
    .border-primary-custom {
        border-color: #940000 !important;
    }
    .btn-primary-custom {
        background-color: #940000;
        border-color: #940000;
        color: #ffffff;
    }
    /*gyguggjgjggjfgjffffjgngjvfgjghg*/
    .btn-primary-custom:hover {
        background-color: #b30000;
        border-color: #b30000;
        color: #ffffff;
    }
    .subject-card {
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1), 0 4px 16px rgba(148, 0, 0, 0.08) !important;
        border: 1px solid rgba(148, 0, 0, 0.1) !important;
    }
    .subject-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(148, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    .action-icon {
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.2s;
        padding: 8px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .action-icon:hover {
        background-color: rgba(148, 0, 0, 0.1);
        transform: scale(1.1);
    }
    .action-icon.view-students {
        color: #17a2b8;
    }
    .action-icon.view-results {
        color: #28a745;
    }
    .action-icon.edit-results {
        color: #ffc107;
    }
    .action-icon.add-results {
        color: #940000;
    }
    .stat-badge {
        background-color: rgba(148, 0, 0, 0.1);
        color: #940000;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    /* Scrollbar for Election Modal */
    #subjectElectionModal .modal-body {
        overflow-y: auto !important;
        max-height: 80vh;
        scrollbar-width: thin;
    }
    #subjectElectionModal .modal-body::-webkit-scrollbar {
        width: 8px;
        display: block !important;
    }
    #subjectElectionModal .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    #subjectElectionModal .modal-body::-webkit-scrollbar-thumb {
        background: #940000;
        border-radius: 4px;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- jsPDF Library for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>

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
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Page Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-primary-custom text-white rounded">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-0">
                            <i class="bi bi-book"></i> My Subjects
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="subjectSearchInput">
                                    <i class="bi bi-search"></i> Search Subjects or Classes
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="subjectSearchInput" placeholder="Search by subject name, subject code, or class name...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" style="display: none;">
                                            <i class="bi bi-x-circle"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle"></i> Type to filter subjects by name, code, or class name.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="row" id="subjectsGrid">
                @if($classSubjects && $classSubjects->count() > 0)
                    @foreach($classSubjects as $classSubject)
                        @php
                            // Only display if subject exists and has Active status
                            if (!$classSubject->subject || $classSubject->subject->status !== 'Active' || $classSubject->status !== 'Active') {
                                continue;
                            }
                            $subjectName = strtolower($classSubject->subject->subject_name ?? '');
                            $subjectCode = strtolower($classSubject->subject->subject_code ?? '');
                            $className = $classSubject->subclass ? strtolower($classSubject->subclass->subclass_name ?? '') : 'all subclasses';
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4 subject-item"
                             data-subject-name="{{ $subjectName }}"
                             data-subject-code="{{ $subjectCode }}"
                             data-class-name="{{ $className }}">
                            <div class="card subject-card border-0 h-100">
                                <div class="card-body">
                                    <!-- Subject Icon and Name -->
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-book-half text-primary-custom" style="font-size: 2.5rem;"></i>
                                        <div class="ml-3">
                                            <h5 class="card-title text-primary-custom mb-0">
                                                {{ $classSubject->subject->subject_name ?? 'N/A' }}
                                            </h5>
                                            @if($classSubject->subject->subject_code)
                                                <small class="text-muted">Code: {{ $classSubject->subject->subject_code }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Statistics -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">
                                                <i class="bi bi-people"></i> Total Students:
                                            </span>
                                            <span class="stat-badge">{{ $classSubject->total_students ?? 0 }}</span>
                                        </div>

                                        @if($classSubject->subclass)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="bi bi-diagram-3"></i> Class:
                                                </span>
                                                <strong>
                                                    @if($classSubject->subclass->class)
                                                        {{ $classSubject->subclass->class->class_name }} - {{ $classSubject->subclass->subclass_name }}
                                                    @else
                                                        {{ $classSubject->subclass->subclass_name ?? 'N/A' }}
                                                    @endif
                                                </strong>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="bi bi-diagram-3"></i> Class:
                                                </span>
                                                <strong class="text-info">
                                                    @if($classSubject->class)
                                                        {{ $classSubject->class->class_name }} - All Subclasses
                                                    @else
                                                        All Subclasses
                                                    @endif
                                                </strong>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Icons -->
                                    <div class="d-flex justify-content-around align-items-center pt-3 border-top flex-wrap">
                                        <div class="text-center mb-2" title="View Students">
                                            <i class="bi bi-people-fill action-icon view-students" onclick="viewStudents({{ $classSubject->class_subjectID }})"></i>
                                            <small class="d-block text-muted mt-1">Students</small>
                                        </div>
                                        <div class="text-center mb-2" title="Session Attendance">
                                            <i class="bi bi-clock-history action-icon" style="color: #17a2b8;" onclick="viewSessionAttendance({{ $classSubject->class_subjectID }})"></i>
                                            <small class="d-block text-muted mt-1">Session Attendance</small>
                                        </div>
                                        <div class="text-center mb-2" title="Exam Attendance">
                                            <i class="bi bi-calendar-check action-icon" style="color: #940000;" onclick="viewExamAttendance({{ $classSubject->class_subjectID }}, {{ $classSubject->subject->subjectID ?? 0 }})"></i>
                                            <small class="d-block text-muted mt-1">Exam Attendance</small>
                                        </div>
                                        <div class="text-center mb-2" title="View Results">
                                            <i class="bi bi-clipboard-check action-icon view-results" onclick="viewResults({{ $classSubject->class_subjectID }})"></i>
                                            <small class="d-block text-muted mt-1">View Results</small>
                                        </div>
                                        <div class="text-center mb-2" title="Edit Results">
                                            <i class="bi bi-pencil-square action-icon edit-results" onclick="editResults({{ $classSubject->class_subjectID }})"></i>
                                            <small class="d-block text-muted mt-1">Edit Result</small>
                                        </div>
                                        <div class="text-center mb-2" title="Add Results">
                                            <i class="bi bi-plus-circle-fill action-icon add-results" onclick="addResults({{ $classSubject->class_subjectID }})"></i>
                                            <small class="d-block text-muted mt-1">Add Result</small>
                                        </div>
                                        @if($classSubject->student_status === 'Optional')
                                            <div class="text-center mb-2" title="Subject Election">
                                                <i class="bi bi-person-check action-icon" style="color: #007bff;" 
                                                   onclick="openElectionModal({{ $classSubject->class_subjectID }}, '{{ $classSubject->subject->subject_name }}', {{ $classSubject->subclassID ?? 0 }})"></i>
                                                <small class="d-block text-muted mt-1">Election</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No subjects assigned to you yet.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Subject Election Modal -->
<div class="modal fade" id="subjectElectionModal" tabindex="-1" aria-labelledby="subjectElectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 95%; width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="subjectElectionModalLabel">
                    <i class="bi bi-person-check"></i> <span id="electionSubjectTitle">Subject Election</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                    <div class="small text-muted">
                        <i class="bi bi-info-circle"></i> Use buttons to bulk select/deselect students for this subject.
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-success" id="electionSelectAllBtn">
                            <i class="bi bi-check-all"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="electionDeselectAllBtn">
                            <i class="bi bi-dash-circle"></i> Deselect All
                        </button>
                    </div>
                </div>
                <div id="electionStudentsContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary-custom" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading students...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary-custom" id="saveElectionBtn">
                    <i class="bi bi-save"></i> Save Election
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Students Modal -->
<div class="modal" id="viewStudentsModal" role="dialog" aria-labelledby="viewStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="viewStudentsModalLabel">
                    <i class="bi bi-people"></i> Students
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="studentsModalBody" style="max-height: 80vh; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    #viewStudentsModal .modal-body::-webkit-scrollbar {
                        width: 0px;
                        background: transparent;
                    }
                    #viewStudentsModal .modal-body::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    #viewStudentsModal .modal-body::-webkit-scrollbar-thumb {
                        background: transparent;
                    }
                </style>
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Session Attendance Modal -->
<div class="modal" id="sessionAttendanceModal" role="dialog" aria-labelledby="sessionAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="sessionAttendanceModalLabel">
                    <i class="bi bi-clock-history"></i> Session Attendance
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sessionAttendanceModalBody" style="max-height: 80vh; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    #sessionAttendanceModal .modal-body::-webkit-scrollbar {
                        width: 0px;
                        background: transparent;
                    }
                    #sessionAttendanceModal .modal-body::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    #sessionAttendanceModal .modal-body::-webkit-scrollbar-thumb {
                        background: transparent;
                    }
                </style>
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Attendance Modal -->
<div class="modal" id="examAttendanceModal" role="dialog" aria-labelledby="examAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="examAttendanceModalLabel">
                    <i class="bi bi-calendar-check"></i> Exam Attendance
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="examAttendanceModalBody" style="max-height: 80vh; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    #examAttendanceModal .modal-body::-webkit-scrollbar {
                        width: 0px;
                        background: transparent;
                    }
                    #examAttendanceModal .modal-body::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    #examAttendanceModal .modal-body::-webkit-scrollbar-thumb {
                        background: transparent;
                    }
                </style>
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Results Modal -->
<div class="modal" id="viewResultsModal" role="dialog" aria-labelledby="viewResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="viewResultsModalLabel">
                    <i class="bi bi-clipboard-check"></i> Results
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultsModalBody" style="max-height: 80vh; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    #viewResultsModal .modal-body::-webkit-scrollbar {
                        width: 0px;
                        background: transparent;
                    }
                    #viewResultsModal .modal-body::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    #viewResultsModal .modal-body::-webkit-scrollbar-thumb {
                        background: transparent;
                    }
                </style>
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Results Modal -->
<div class="modal" id="addEditResultsModal" role="dialog" aria-labelledby="addEditResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="addEditResultsModalLabel">
                    <i class="bi bi-plus-circle"></i> Add Results
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="addEditResultsModalBody" style="max-height: 75vh; overflow-y: auto; overflow-x: hidden;">
                <div class="text-center">
                    <div class="spinner-border text-primary-custom" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Excel Modal -->
<div class="modal" id="uploadExcelModal" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="uploadExcelModalLabel">
                    <i class="bi bi-upload"></i> Upload Excel Results
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadExcelForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="upload_class_subject_id" name="class_subject_id">
                    <input type="hidden" id="upload_exam_id" name="exam_id">
                    <div class="form-group">
                        <label>Select Microsoft Excel Worksheet File <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required>
                            <label class="custom-file-label" for="excel_file" id="excel_file_label">
                                <i class="bi bi-file-earmark-excel"></i> Choose Excel file (.xlsx or .xls)
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i> Only Microsoft Excel Worksheet files (.xlsx or .xls) are allowed.
                        </small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Note:</strong> Make sure the Excel file matches the downloaded template format.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-upload"></i> Upload & Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Ensure jQuery is available
if (typeof $ === 'undefined') {
    var $ = jQuery;
}

const isSecondarySchool = @json(strtolower($schoolType ?? 'Secondary')) === 'secondary';
const questionColspan = isSecondarySchool ? 7 : 6;
let examQuestionData = {
    questions: [],
    marksByStudent: {},
    maxTotal: 0,
    optionalTotal: 0,
    optionalRanges: []
};

jQuery(document).ready(function($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize search functionality
    initializeSubjectSearch();
});

// Initialize Subject Search
function initializeSubjectSearch() {
    jQuery('#subjectSearchInput').on('keyup', function() {
        var searchTerm = jQuery(this).val().toLowerCase().trim();

        if (searchTerm.length > 0) {
            jQuery('#clearSearchBtn').show();
        } else {
            jQuery('#clearSearchBtn').hide();
        }

        // Filter subject cards
        jQuery('.subject-item').each(function() {
            var subjectName = jQuery(this).data('subject-name') || '';
            var subjectCode = jQuery(this).data('subject-code') || '';
            var className = jQuery(this).data('class-name') || '';

            var matches = subjectName.includes(searchTerm) ||
                         subjectCode.includes(searchTerm) ||
                         className.includes(searchTerm);

            if (matches) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        });

        // Show message if no results
        var visibleCount = jQuery('.subject-item:visible').length;

        if (searchTerm.length > 0 && visibleCount === 0) {
            if (jQuery('#noResultsMessage').length === 0) {
                jQuery('#subjectsGrid').append(
                    '<div class="col-12" id="noResultsMessage">' +
                    '<div class="alert alert-info text-center">' +
                    '<i class="bi bi-info-circle"></i> No subjects or classes found matching "' + searchTerm + '".' +
                    '</div>' +
                    '</div>'
                );
            }
        } else {
            jQuery('#noResultsMessage').remove();
        }
    });

    // Clear search button
    jQuery('#clearSearchBtn').on('click', function() {
        jQuery('#subjectSearchInput').val('');
        jQuery('#clearSearchBtn').hide();
        jQuery('.subject-item').show();
        jQuery('#noResultsMessage').remove();
    });
}

// Show student photo in larger view
function showStudentPhoto(photoUrl, studentName) {
    Swal.fire({
        title: studentName,
        imageUrl: photoUrl,
        imageWidth: 300,
        imageHeight: 300,
        imageAlt: 'Student Photo',
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'swal2-popup-custom'
        }
    });
}

// Calculate grade and remark from marks
function calculateGradeAndRemark(marks) {
    if (!marks || marks === '' || isNaN(marks)) {
        return { grade: '', remark: '' };
    }

    const marksNum = parseFloat(marks);

    if (marksNum >= 75) {
        return { grade: 'A', remark: 'Excellent' };
    } else if (marksNum >= 65) {
        return { grade: 'B', remark: 'Very Good' };
    } else if (marksNum >= 45) {
        return { grade: 'C', remark: 'Good' };
    } else if (marksNum >= 30) {
        return { grade: 'D', remark: 'Pass' };
    } else {
        return { grade: 'F', remark: 'Fail' };
    }
}

// Get grade cell class for styling
function getGradeCellClass(grade) {
    if (!grade || grade === '' || grade.toLowerCase() === 'incomplete') {
        return 'bg-warning text-dark';
    }
    const gradeUpper = grade.toUpperCase();
    if (gradeUpper === 'A') {
        return 'bg-success text-white';
    } else if (gradeUpper === 'E' || gradeUpper === 'F') {
        return 'bg-danger text-white';
    }
    return 'bg-info text-white';
}

function resetQuestionData() {
    examQuestionData = {
        questions: [],
        marksByStudent: {},
        maxTotal: 0,
        optionalTotal: 0,
        optionalRanges: []
    };
    if (isSecondarySchool) {
        jQuery('.question-details-row').addClass('d-none').removeClass('loaded');
        jQuery('.question-detail-container').html('<div class="text-muted small">Select examination to load question formats.</div>');
    }
    jQuery('#test_week_display_group').hide();
    jQuery('#selected_week_label').text('');
}

function loadExamQuestionData(classSubjectID, examID) {
    resetQuestionData();

    if (!examID) {
        return;
    }

    jQuery.ajax({
        url: `/get_exam_paper_question_data/${classSubjectID}/${examID}`,
        method: 'GET',
        success: function(response) {
            if (!response.success) {
                return;
            }

            examQuestionData.questions = response.questions || [];
            examQuestionData.marksByStudent = response.marks_by_student || {};
            examQuestionData.maxTotal = response.max_total || 0;
            examQuestionData.optionalTotal = response.optional_total || 0;
            examQuestionData.optionalRanges = response.optional_ranges || [];

            if (response.test_week) {
                // Set the week value and hide the selection as it should be default from the paper
                jQuery('#test_week_group').hide();
                jQuery('#test_week_display_group').show();
                jQuery('#selected_week_label').text(response.test_week);

                const $tw = jQuery('#test_week');
                if ($tw.find(`option[value="${response.test_week}"]`).length === 0) {
                    $tw.append(`<option value="${response.test_week}">${response.test_week}</option>`);
                }
                $tw.val(response.test_week);
                // Reload existing results for this specific week
                loadExistingResults(classSubjectID, examID, response.test_week);
            } else {
                jQuery('#test_week_display_group').hide();
            }

            if (examQuestionData.questions.length === 0 && isSecondarySchool) {
                const exam = window.examDataCache[examID];
                if (exam && exam.allow_no_format == 1) {
                    jQuery('.question-detail-container').empty();
                    return;
                }
                
                jQuery('.question-detail-container').html(
                    '<div class="alert alert-warning mb-0">' +
                    '<i class="bi bi-exclamation-triangle"></i> ' + (response.message || 'No approved exam paper question formats found for this subject.') +
                    '</div>'
                );
                return;
            }

            updateTotalsFromCache();

            // Re-render any question detail rows that are already visible/loaded
            // so they show the fresh marks_by_student data
            jQuery('.question-details-row.loaded').each(function() {
                const studentID = jQuery(this).data('student');
                jQuery(this).find('.question-detail-container').html(buildQuestionDetails(studentID));
                // Restore optional-select checkbox state and enable inputs
                jQuery(this).find('.optional-select').each(function() {
                    const questionId = jQuery(this).data('question-id');
                    const hasMark = examQuestionData.marksByStudent[studentID] &&
                        examQuestionData.marksByStudent[studentID][questionId] !== undefined &&
                        examQuestionData.marksByStudent[studentID][questionId] !== null &&
                        examQuestionData.marksByStudent[studentID][questionId] !== '';
                    if (hasMark) {
                        jQuery(this).prop('checked', true);
                        const $input = jQuery(this).closest('.question-details-row')
                            .find(`.question-mark-input[data-question-id="${questionId}"]`);
                        $input.prop('disabled', false);
                    }
                });
                updateStudentTotal(studentID);
            });
        },
        error: function() {
            jQuery('.question-detail-container').html(
                '<div class="alert alert-danger mb-0">' +
                '<i class="bi bi-exclamation-triangle"></i> Failed to load question formats.' +
                '</div>'
            );
        }
    });
}

function buildQuestionDetails(studentID) {
    if (!isSecondarySchool) {
        return '';
    }

    if (!examQuestionData.questions || examQuestionData.questions.length === 0) {
        return `
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle"></i> No question formats available.
            </div>
        `;
    }

    let html = '<div class="question-list">';
    examQuestionData.questions.forEach(function(question) {
        const existingMarks = examQuestionData.marksByStudent[studentID] &&
            examQuestionData.marksByStudent[studentID][question.exam_paper_questionID] !== undefined
            ? examQuestionData.marksByStudent[studentID][question.exam_paper_questionID]
            : '';
        let optionalTag = '';
        let isChecked = false;
        if (question.is_optional) {
            const rangeLabel = question.optional_range_number ? `Opt ${question.optional_range_number}` : 'Optional';
            optionalTag = `<span class="badge badge-warning ml-2">${rangeLabel}</span>`;
            // Only mark as selected if marks value is a real number (not null, not empty string)
            const savedMark = examQuestionData.marksByStudent[studentID] &&
                              examQuestionData.marksByStudent[studentID][question.exam_paper_questionID];
            const markValue = (savedMark !== undefined && savedMark !== null && savedMark !== '') ? parseFloat(savedMark) : NaN;
            isChecked = !isNaN(markValue);
        }
        html += `
            <div class="form-row align-items-end mb-2">
                <div class="col-md-2">
                    <label class="small text-muted mb-1">Qn ${question.question_number}</label>
                </div>
                <div class="col-md-6">
                    <div class="small font-weight-bold">${question.question_description} ${optionalTag}</div>
                    <div class="small text-muted">Max: ${question.marks}</div>
                </div>
                <div class="col-md-4">
                    ${question.is_optional ? `
                        <div class="form-check mb-1">
                            <input class="form-check-input optional-select" type="checkbox"
                                   data-student="${studentID}"
                                   data-question-id="${question.exam_paper_questionID}"
                                   data-optional-range="${question.optional_range_number || 0}"
                                   ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label small">Selected</label>
                        </div>
                    ` : ''}
                    <input type="number"
                           class="form-control form-control-sm question-mark-input"
                           data-student="${studentID}"
                           data-question-id="${question.exam_paper_questionID}"
                           data-optional="${question.is_optional ? 1 : 0}"
                           data-optional-range="${question.optional_range_number || 0}"
                           data-max="${question.marks}"
                           min="0"
                           max="${question.marks}"
                           step="0.01"
                           value="${existingMarks}"
                           ${(question.is_optional && !isChecked) ? 'disabled' : ''}>
                    <div class="text-danger small question-max-warning d-none"></div>
                </div>
            </div>
        `;
    });
    html += `
        </div>
        <div class="text-muted small mt-2">
            Total: <span class="student-question-total" data-student="${studentID}">0</span> / ${examQuestionData.maxTotal || 100}
        </div>
        ${examQuestionData.optionalTotal > 0 ? `
            <div class="text-muted small">
                Optional Total: ${examQuestionData.optionalTotal}
            </div>
        ` : ''}
        <div class="text-danger small optional-total-warning d-none" data-student="${studentID}"></div>
    `;

    return html;
}

function updateTotalsFromCache() {
    if (!isSecondarySchool || !examQuestionData.marksByStudent) {
        return;
    }

    Object.keys(examQuestionData.marksByStudent).forEach(function(studentID) {
        const questionMarks = examQuestionData.marksByStudent[studentID] || {};
        let total = 0;
        let hasMarks = false;
        Object.keys(questionMarks).forEach(function(questionId) {
            const value = parseFloat(questionMarks[questionId]);
            if (!isNaN(value)) {
                total += value;
                hasMarks = true;
            }
        });

        if (hasMarks) {
            const displayTotal = Number.isInteger(total) ? total : total.toFixed(2);
            jQuery(`#marks_${studentID}`).val(displayTotal);
            autoCalculateGrade(studentID);
        }
    });
}

function updateStudentTotal(studentID) {
    if (!studentID) return;
    
    let total = 0;
    let optionalTotal = 0;
    let optionalTotalsByRange = {};
    let hasMarks = false;
    
    // Find inputs strictly for this student
    const $studentRow = jQuery(`input[id="marks_${studentID}"], .marks-input[data-student="${studentID}"]`).closest('tr');
    
    jQuery(`.question-mark-input[data-student="${studentID}"]`).each(function() {
        const value = parseFloat(jQuery(this).val());
        const isOptional = jQuery(this).data('optional') == 1;
        const rangeNumber = parseInt(jQuery(this).data('optional-range'), 10);
        if (!isNaN(value)) {
            total += value;
            if (isOptional) {
                optionalTotal += value;
                if (rangeNumber > 0) {
                    optionalTotalsByRange[rangeNumber] = (optionalTotalsByRange[rangeNumber] || 0) + value;
                }
            }
            hasMarks = true;
        }
    });

    const $optionalWarning = jQuery(`.optional-total-warning[data-student="${studentID}"]`);
    const rangeExceeded = Object.keys(optionalTotalsByRange).some(function(range) {
        const sum = optionalTotalsByRange[range];
        const rangeTotal = (examQuestionData.optionalRanges || []).find(r => r.range_number == range);
        return rangeTotal && sum > parseFloat(rangeTotal.total_marks);
    });

    if (rangeExceeded) {
        $optionalWarning.text('Optional marks exceed allowed range total.').removeClass('d-none');
    } else if (examQuestionData.optionalTotal > 0 && optionalTotal > examQuestionData.optionalTotal) {
        $optionalWarning.text('Optional marks exceed allowed total.').removeClass('d-none');
    } else {
        $optionalWarning.addClass('d-none').text('');
    }

    const displayTotal = Number.isInteger(total) ? total : total.toFixed(2);
    
    // Update UI elements only for this student
    jQuery(`.student-question-total[data-student="${studentID}"]`).text(hasMarks ? displayTotal : 0);
    const $mainMarksInput = jQuery(`#marks_${studentID}`);
    $mainMarksInput.val(hasMarks ? displayTotal : '');
    
    // Pass the row to autoCalculateGrade for precision
    autoCalculateGrade(studentID, $studentRow);
}

// View Students
function viewStudents(classSubjectID) {
    jQuery('#viewStudentsModal').modal('show');
    jQuery('#studentsModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"></div></div>');

    jQuery.ajax({
        url: '/get_subject_students/' + classSubjectID,
        method: 'GET',
        success: function(response) {
            if (response.success && response.students) {
                let html = `
                    <div class="mb-3">
                        <h6 class="text-primary-custom">
                            <i class="bi bi-book"></i> ${response.class_subject.subject ? response.class_subject.subject.subject_name : 'Subject'}
                            <span class="badge badge-primary ml-2">${response.students.length} Students</span>
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-primary-custom text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Admission No.</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Gender</th>
                                    <th>Date of Birth</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.students.forEach(function(student, index) {
                    // Get student photo or default based on gender
                    const baseUrl = '{{ asset("") }}';
                    let photoUrl = '';
                    if (student.photo) {
                        photoUrl = baseUrl + 'userImages/' + student.photo;
                    } else {
                        photoUrl = student.gender === 'Female'
                            ? baseUrl + 'images/female.png'
                            : baseUrl + 'images/male.png';
                    }

                    const dob = student.date_of_birth ? new Date(student.date_of_birth).toLocaleDateString() : 'N/A';
                    const studentName = (student.first_name || '') + ' ' + (student.middle_name || '') + ' ' + (student.last_name || '');
                    const fallbackPhoto = student.gender === 'Female' ? baseUrl + 'images/female.png' : baseUrl + 'images/male.png';

                    // Add red alarm icon if student has health conditions
                    let healthAlarmIcon = '';
                    if ((student.is_disabled && student.is_disabled == 1) ||
                        (student.has_epilepsy && student.has_epilepsy == 1) ||
                        (student.has_allergies && student.has_allergies == 1)) {
                        healthAlarmIcon = ' <i class="bi bi-exclamation-triangle-fill text-danger" title="Health Condition Alert"></i>';
                    }

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <img src="${photoUrl}"
                                     alt="Student Photo"
                                     class="rounded-circle"
                                     style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #940000; cursor: pointer;"
                                     onclick="showStudentPhoto('${photoUrl.replace(/'/g, "\\'")}', '${studentName.replace(/'/g, "\\'")}')"
                                     onerror="this.src='${fallbackPhoto}'">
                            </td>
                            <td><strong>${student.admission_number || 'N/A'}</strong></td>
                            <td>${studentName}${healthAlarmIcon}</td>
                            <td>${student.subclass ? (student.subclass.subclass_name || 'N/A') : 'N/A'}</td>
                            <td>${student.gender || 'N/A'}</td>
                            <td>${dob}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                jQuery('#studentsModalBody').html(html);
            } else {
                jQuery('#studentsModalBody').html('<div class="alert alert-info">No students found.</div>');
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load students',
                icon: 'error',
                confirmButtonColor: '#940000'
            });
            jQuery('#viewStudentsModal').modal('hide');
        }
    });
}

// View Results
function viewResults(classSubjectID) {
    // Get subject name and class from the card
    const card = jQuery(`i.view-results[onclick="viewResults(${classSubjectID})"]`).closest('.card');
    const subjectName = card.find('.card-title').text().trim();
    const className = card.find('strong').first().text().trim();
    
    // Grab the teacher's name from Auth::user or session if possible, 
    // but since we are in JS let's use the blade variable correctly.
    const teacherName = "{{ Auth::user()->name ?? 'N/A' }}";
    
    window.currentViewSubject = subjectName;
    window.currentViewClass = className;
    window.currentViewTeacher = teacherName;

    jQuery('#viewResultsModal').modal('show');
    jQuery('#viewResultsModalLabel').html(`<i class="bi bi-clipboard-check"></i> Results: ${subjectName} (${className})`);
    jQuery('#resultsModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"></div></div>');

    // First get examinations for this subject
    jQuery.ajax({
        url: '/get_examinations_for_subject/' + classSubjectID,
        method: 'GET',
        success: function(examResponse) {
            if (examResponse.success && examResponse.examinations && examResponse.examinations.length > 0) {
                // Show exam selector and filter options
                let html = `
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label><i class="bi bi-calendar-event"></i> Year:</label>
                            <select class="form-control" id="view_year_filter" onchange="filterViewResultsOptions(${classSubjectID})">
                                <option value="">All Years</option>
                                ${getUniqueYears(examResponse.examinations)}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><i class="bi bi-clock-history"></i> Term:</label>
                            <select class="form-control" id="view_term_filter" onchange="filterViewResultsOptions(${classSubjectID})">
                                <option value="">All Terms</option>
                                <option value="first_term">First Term</option>
                                <option value="second_term">Second Term</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label><i class="bi bi-file-earmark-text"></i> Examination:</label>
                            <select class="form-control" id="examSelector" onchange="handleViewResultsExamSelection(${classSubjectID}, this.value)">
                                <option value="">All Examinations</option>
                                ${generateExamOptions(examResponse.examinations)}
                            </select>
                        </div>
                         <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-block" onclick="exportResultsToPDF(${classSubjectID})">
                                <i class="bi bi-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <!-- Week selection for Weekly Tests in View Modal -->
                    <div class="mb-3" id="view_test_week_group" style="display: none;">
                        <label>Select Week:</label>
                        <select class="form-control" id="view_test_week" onchange="loadResultsForExam(${classSubjectID}, jQuery('#examSelector').val(), this.value)">
                            <option value="">All Weeks</option>
                        </select>
                    </div>
                    <div id="resultsContent"></div>
                `;

                // Store all exams for filtering
                window.viewModalExams = examResponse.examinations;

                jQuery('#resultsModalBody').html(html);
                loadResultsForExam(classSubjectID, '');
            } else {
                jQuery('#resultsModalBody').html('<div class="alert alert-info">No examinations found for this subject.</div>');
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load examinations',
                icon: 'error',
                confirmButtonColor: '#940000'
            });
        }
    });
}

function handleViewResultsExamSelection(classSubjectID, examID) {
    if (!examID) {
        jQuery('#view_test_week_group').hide();
        loadResultsForExam(classSubjectID, '');
        return;
    }

    const examData = window.examDataCache && window.examDataCache[examID];
    if (examData && examData.exam_category === 'test') {
        jQuery('#view_test_week_group').show();
        const $weekSelect = jQuery('#view_test_week');
        $weekSelect.empty().append('<option value="">All Periods</option>');

        const today = new Date();
        const currentYear = today.getFullYear();

        if (examData.test_type === 'monthly_test') {
            const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            for (let i = 0; i < 12; i++) {
                const periodVal = `Month of ${months[i]} ${currentYear}`;
                $weekSelect.append(`<option value="${periodVal}">${periodVal}</option>`);
            }
        } else {
            const startDate = new Date(currentYear, 0, 1);
            while (startDate.getDay() !== 1) startDate.setDate(startDate.getDate() + 1);

            const monthNamesShort = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            function fmtRange(start, end) {
                const sM = monthNamesShort[start.getMonth()];
                const eM = monthNamesShort[end.getMonth()];
                const sD = start.getDate();
                const eD = end.getDate();
                const y = end.getFullYear();
                return `${sM} ${sD} to ${eM} ${eD}, ${y}`;
            }

            for (let i = 0; i < 52; i++) {
                const weekStart = new Date(startDate);
                weekStart.setDate(startDate.getDate() + (i * 7));
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekStart.getDate() + 6);
                const startStr = weekStart.toISOString().split('T')[0];
                const endStr = weekEnd.toISOString().split('T')[0];
                const weekVal = `Week of ${startStr} to ${endStr}`;
                const weekLabel = fmtRange(weekStart, weekEnd);
                if (weekStart <= new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000)) {
                    $weekSelect.append(`<option value="${weekVal}">${weekLabel}</option>`);
                }
            }
        }
    } else {
        jQuery('#view_test_week_group').hide();
    }

    loadResultsForExam(classSubjectID, examID);
}

function loadResultsForExam(classSubjectID, examID, testWeek = null) {
    let url = examID ? `/get_subject_results/${classSubjectID}/${examID}` : `/get_subject_results/${classSubjectID}`;
    const data = {};
    if (testWeek) data.test_week = testWeek;

    jQuery('#resultsContent').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"></div></div>');

    jQuery.ajax({
        url: url,
        method: 'GET',
        data: data,
        success: function(response) {
            if (response.success && response.results) {
                const baseUrl = '{{ asset("") }}';
                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-primary-custom text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Student Name</th>
                                    <th>Admission No.</th>
                                    <th>Examination</th>
                                    ${response.has_ca ? '<th>Exam Marks</th><th>CA</th><th>Total</th><th>Avg</th>' : '<th>Marks</th>'}
                                    <th>Grade</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody id="viewResultsTable">
                `;

                response.results.forEach(function(result, index) {
                    const student = result.student || {};
                    const studentName = (student.first_name || '') + ' ' + (student.middle_name || '') + ' ' + (student.last_name || '');

                    // Add red alarm icon if student has health conditions
                    let healthAlarmIcon = '';
                    if ((student.is_disabled && student.is_disabled == 1) ||
                        (student.has_epilepsy && student.has_epilepsy == 1) ||
                        (student.has_allergies && student.has_allergies == 1)) {
                        healthAlarmIcon = ' <i class="bi bi-exclamation-triangle-fill text-danger" title="Health Condition Alert"></i>';
                    }

                    // Get student photo or default based on gender
                    let photoUrl = '';
                    if (student.photo) {
                        photoUrl = baseUrl + 'userImages/' + student.photo;
                    } else {
                        photoUrl = student.gender === 'Female'
                            ? baseUrl + 'images/female.png'
                            : baseUrl + 'images/male.png';
                    }
                    const fallbackPhoto = student.gender === 'Female' ? baseUrl + 'images/female.png' : baseUrl + 'images/male.png';

                    // Handle marks, grade, and remark
                    const examMarksValue = result.exam_marks !== undefined ? result.exam_marks : result.marks;
                    const marks = examMarksValue !== null && examMarksValue !== '' ? examMarksValue : null;
                    const caValue = response.has_ca ? (result.ca_marks !== undefined ? result.ca_marks : 0) : null;
                    const totalValue = response.has_ca ? (result.total_marks !== undefined ? result.total_marks : marks) : null;

                    let grade = result.grade || '';
                    let remark = result.remark || '';

                    // If marks not filled, show Incomplete
                    if (marks === null) {
                        grade = 'Incomplete';
                        remark = 'Incomplete';
                    }

                    // Get grade cell class for styling
                    const gradeClass = getGradeCellClass(grade);

                    let marksColumnHtml = '';
                    if (response.has_ca) {
                        const avgValue = result.avg_marks !== undefined ? result.avg_marks : (totalValue / 2);
                        marksColumnHtml = `
                            <td><strong>${marks !== null ? marks : '<span class="text-muted">-</span>'}</strong></td>
                            <td><span class="text-info font-weight-bold">${caValue !== null ? caValue : '-'}</span></td>
                            <td><span class="text-success font-weight-bold">${totalValue !== null ? totalValue : '-'}</span></td>
                            <td><span class="text-warning font-weight-bold" style="font-size: 1.1em">${avgValue !== null ? avgValue : '-'}</span></td>
                        `;
                    } else {
                        marksColumnHtml = `<td><strong>${marks !== null ? marks : '<span class="text-muted">-</span>'}</strong></td>`;
                    }

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <img src="${photoUrl}"
                                     alt="Student Photo"
                                     class="rounded-circle"
                                     style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #940000; cursor: pointer;"
                                     onclick="showStudentPhoto('${photoUrl.replace(/'/g, "\\'")}', '${studentName.replace(/'/g, "\\'")}')"
                                     onerror="this.src='${fallbackPhoto}'">
                            </td>
                            <td>${studentName || 'N/A'}${healthAlarmIcon}</td>
                            <td>${student.admission_number || 'N/A'}</td>
                            <td>
                                ${result.examination ? result.examination.exam_name : 'N/A'}
                                ${result.test_week ? `<br><small class="badge badge-info">${result.test_week}</small>` : ''}
                            </td>
                            ${marksColumnHtml}
                            <td><span class="badge ${gradeClass}" style="font-size: 0.9rem; padding: 0.4rem 0.6rem;">${grade || '<span class="text-muted">-</span>'}</span></td>
                            <td>${remark || '<span class="text-muted">-</span>'}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                jQuery('#resultsContent').html(html);
            } else {
                jQuery('#resultsContent').html('<div class="alert alert-info">No results found.</div>');
            }
        },
        error: function(xhr) {
            jQuery('#resultsContent').html('<div class="alert alert-danger">Error loading results.</div>');
        }
    });
}

// Helper: Get Unique Years from exams
function getUniqueYears(exams) {
    let years = [...new Set(exams.map(e => e.year))];
    years.sort((a, b) => b - a);
    return years.map(y => `<option value="${y}">${y}</option>`).join('');
}

// Helper: Generate Exam Options
function generateExamOptions(exams) {
    return exams
        .filter(e => e.enter_result === true || e.enter_result === 1)
        .map(e => `<option value="${e.examID}" data-year="${e.year}" data-term="${e.term}">${e.exam_name} (${e.year})</option>`)
        .join('');
}

// Helper: Filter Exam Selector based on Year/Term
function filterViewResultsOptions(classSubjectID) {
    const year = jQuery('#view_year_filter').val();
    const term = jQuery('#view_term_filter').val();
    const selector = jQuery('#examSelector');
    
    selector.find('option').each(function() {
        const opt = jQuery(this);
        if (!opt.val()) return; // Skip "All"
        
        const optYear = opt.data('year');
        const optTerm = opt.data('term');
        
        let show = true;
        if (year && optYear != year) show = false;
        if (term && optTerm != term) show = false;
        
        if (show) opt.show(); else opt.hide();
    });
    
    // Reset selection if hidden
    if (selector.find('option:selected').css('display') === 'none') {
        selector.val('');
    }
    
    // Trigger result reload
    handleViewResultsExamSelection(classSubjectID, selector.val());
}

// PDF Export Logic (jsPDF)
function exportResultsToPDF(classSubjectID) {
    const examID = jQuery('#examSelector').val() || '';
    const testWeek = jQuery('#view_test_week').val() || '';
    
    let url = `/export_subject_results_pdf/${classSubjectID}`;
    if (examID) {
        url += `/${examID}`;
    }
    
    const params = new URLSearchParams();
    if (testWeek) params.append('test_week', testWeek);
    const queryString = params.toString();
    if (queryString) url += `?${queryString}`;

    // Show loading state
    Swal.fire({
        title: 'Generating PDF...',
        html: 'Please wait while your report is being prepared.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.blob();
        })
        .then(blob => {
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            
            // Generate dynamic filename
            const subjectName = window.currentViewSubject ? window.currentViewSubject.replace(/\s+/g, '_') : 'Subject';
            const className = window.currentViewClass ? window.currentViewClass.replace(/\s+/g, '_') : 'Class';
            a.download = `${className}_${subjectName}_Results.pdf`;
            
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(downloadUrl);
            
            Swal.fire({
                title: 'Success!',
                text: 'Your PDF has been downloaded.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            console.error('Download error:', error);
            Swal.fire('Error', 'Failed to generate PDF. Please try again.', 'error');
        });
}

// Removed checkExamStatusForAddEdit function - now only checking enter_result

// Edit Results
function editResults(classSubjectID) {
    // Clear exam data cache when opening modal
    window.examDataCache = {};
    resetQuestionData();

    // First check if there are any examinations with enter_result = true
    jQuery.ajax({
        url: '/get_examinations_for_subject/' + classSubjectID,
        method: 'GET',
        success: function(examsResponse) {
            // Check if there are any examinations with enter_result = true
            let hasEnterResultEnabled = false;
            if (examsResponse.success && examsResponse.examinations && examsResponse.examinations.length > 0) {
                hasEnterResultEnabled = examsResponse.examinations.some(function(exam) {
                    return exam.enter_result === true || exam.enter_result === 1;
                });
            }

            if (!hasEnterResultEnabled) {
                Swal.fire({
                    title: 'Access Denied!',
                    text: 'You are not allowed to edit results. Result entry has been disabled for all examinations.',
                    icon: 'error',
                    confirmButtonColor: '#940000'
                });
                return;
            }

            // If there are examinations with enter_result = true, proceed with opening modal
            const modalTitle = 'Edit Results';
            jQuery('#addEditResultsModalLabel').html(`<i class="bi bi-pencil"></i> ${modalTitle}`);
            jQuery('#addEditResultsModal').modal('show');
            jQuery('#addEditResultsModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"></div></div>');

            // Get students, examinations, and existing results
            jQuery.ajax({
                url: '/get_subject_students/' + classSubjectID,
                method: 'GET',
                success: function(studentsResponse) {
                    // Get all results for this subject
                    jQuery.ajax({
                        url: '/get_subject_results/' + classSubjectID,
                        method: 'GET',
                        success: function(resultsResponse) {
                            let html = `
                                <form id="resultsForm">
                                    <input type="hidden" id="class_subject_id" value="${classSubjectID}">
                                    <div class="form-group">
                                        <label>Select Examination <span class="text-danger">*</span></label>
                                        <select class="form-control" id="exam_id" name="exam_id" required onchange="handleExamSelection(${classSubjectID}, this.value)">
                                            <option value="">Select Examination</option>
                            `;

                            // Store exam data globally for use in handleExamSelection
                            window.examDataCache = window.examDataCache || {};

                            if (examsResponse.success && examsResponse.examinations) {
                                examsResponse.examinations.forEach(function(exam) {
                                    // Only show examinations where enter_result is true
                                    if (exam.enter_result === true || exam.enter_result === 1) {
                                        // Store exam data in cache
                                        window.examDataCache[exam.examID] = exam;

                                        const statusText = exam.status === 'awaiting_results' ? ' (Awaiting Results)' :
                                                          exam.status === 'ongoing' ? ' (Ongoing)' : ' (Results Available)';
                                        const termClosedText = exam.is_term_closed ? ' (Term Closed - Not Editable)' : '';
                                        const disabledAttr = exam.is_term_closed ? 'disabled style="background-color: #f0f0f0; color: #999;"' : '';
                                        html += `<option value="${exam.examID}" data-status="${exam.status}" data-term-closed="${exam.is_term_closed}" data-enter-result="${exam.enter_result}" ${disabledAttr}>${exam.exam_name} (${exam.year})${statusText}${termClosedText}</option>`;
                                    }
                                });
                            }

                            html += `
                                        </select>
                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-info-circle"></i> Only examinations with "Enter Result" enabled can be edited.
                                        </small>
                                    </div>
                                    ${isSecondarySchool ? `
                                        <div class="alert alert-warning mt-3">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Question-based results are enabled. Excel import/export is disabled.
                                        </div>
                                    ` : `
                                        <div class="alert alert-info mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi bi-file-earmark-excel"></i>
                                                    <strong>Excel Import/Export:</strong> Download template, fill in results, then upload.
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="fillRandomPrimaryResults()" id="fillRandomBtn" disabled>
                                                        <i class="bi bi-dice-5"></i> Fill Random
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success" onclick="downloadExcelTemplate(${classSubjectID})" id="downloadExcelBtn" disabled>
                                                        <i class="bi bi-download"></i> Download Excel Template
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="showUploadExcelModal(${classSubjectID})" id="uploadExcelBtn" disabled>
                                                        <i class="bi bi-upload"></i> Upload Excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `}
                                    <div class="table-responsive mt-3">
                                        <table class="table table-hover">
                                            <thead class="bg-primary-custom text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Name</th>
                                                    <th>Admission No.</th>
                                                    <th class="text-center questions-column-header" style="${isSecondarySchool ? '' : 'display:none;'}"><i class="bi bi-list-check"></i></th>
                                                    <th>Marks</th>
                                                    <th>Grade</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="resultsTableBody">
                            `;

                            if (studentsResponse.success && studentsResponse.students) {
                                studentsResponse.students.forEach(function(student, index) {
                                    html += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${student.first_name} ${student.middle_name || ''} ${student.last_name}</td>
                                            <td>${student.admission_number || 'N/A'}</td>
                                            <td class="text-center questions-column-cell" style="${isSecondarySchool ? '' : 'display:none;'}">
                                                <button type="button" class="btn btn-sm btn-outline-primary toggle-question-btn" data-student="${student.studentID}" title="Add result questions">
                                                    <i class="bi bi-list-check"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm marks-input"
                                                       name="marks[${student.studentID}]"
                                                       id="marks_${student.studentID}"
                                                       data-student="${student.studentID}"
                                                       step="0.01" min="0" max="100" placeholder="0.00"
                                                       oninput="autoCalculateGrade(${student.studentID})"
                                                       ${isSecondarySchool ? 'readonly' : ''}>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm grade-input"
                                                       name="grade[${student.studentID}]"
                                                       id="grade_${student.studentID}"
                                                       data-student="${student.studentID}"
                                                       placeholder="A, B, C..." readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm remark-input"
                                                       name="remark[${student.studentID}]"
                                                       id="remark_${student.studentID}"
                                                       data-student="${student.studentID}"
                                                       placeholder="Remark" readonly>
                                            </td>
                                        </tr>
                                        <tr class="question-details-row questions-detail-row d-none" id="question_details_${student.studentID}" data-student="${student.studentID}">
                                            <td colspan="${questionColspan}">
                                                <div class="question-detail-container" data-student="${student.studentID}">
                                                    <div class="text-muted small">Select examination to load question formats.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                });
                            }

                            html += `
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary-custom">
                                            <i class="bi bi-check-circle"></i> Update Results
                                        </button>
                                    </div>
                                </form>
                            `;

                            jQuery('#addEditResultsModalBody').html(html);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load results',
                                icon: 'error',
                                confirmButtonColor: '#940000'
                            });
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load examinations',
                        icon: 'error',
                        confirmButtonColor: '#940000'
                    });
                }
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load students',
                icon: 'error',
                confirmButtonColor: '#940000'
            });
        }
    });
}

function loadExistingResults(classSubjectID, examID, testWeek = null) {
    if (!examID) {
        // Clear all inputs
        jQuery('.marks-input, .grade-input, .remark-input').val('');
        return;
    }

    const data = {};
    if (testWeek) data.test_week = testWeek;

    jQuery.ajax({
        url: `/get_subject_results/${classSubjectID}/${examID}`,
        method: 'GET',
        data: data,
        success: function(response) {
            if (response.success && response.results) {
                // Clear all inputs first, scoped to the results table
                jQuery('#resultsTableBody .marks-input, #resultsTableBody .grade-input, #resultsTableBody .remark-input').val('');

                // Populate with existing results
                // Only show marks that are genuinely non-zero (> 0) — zero/null means 'not entered'
                response.results.forEach(function(result) {
                    if (result.studentID) {
                        const rawMarks = parseFloat(result.marks);
                        const hasRealMarks = !isNaN(rawMarks) && rawMarks > 0;
                        if (hasRealMarks) {
                            jQuery(`#marks_${result.studentID}`).val(rawMarks);
                            autoCalculateGrade(result.studentID);
                        }
                        // If marks are 0 or null, leave the input empty (placeholder shows)
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading existing results:', xhr);
        }
    });
}

// Auto-calculate grade and remark when marks are entered
// Fill random results for primary/non-secondary schools for testing/balancing
function fillRandomPrimaryResults() {
    jQuery('.marks-input:not([readonly])').each(function() {
        const studentID = jQuery(this).data('student');
        
        // Distribution targets:
        // A: 15%, B: 25%, C: 30%, D: 20%, F: 10%
        const rand = Math.random();
        let marks = 0;
        
        if (rand < 0.15) { // A: 75-100
            marks = 75 + Math.random() * 25;
        } else if (rand < 0.40) { // B: 65-74
            marks = 65 + Math.random() * 10;
        } else if (rand < 0.70) { // C: 45-64
            marks = 45 + Math.random() * 20;
        } else if (rand < 0.90) { // D: 30-44
            marks = 30 + Math.random() * 15;
        } else { // F: 5-29
            marks = 5 + Math.random() * 25;
        }
        
        jQuery(this).val(marks.toFixed(2));
        autoCalculateGrade(studentID);
    });
}

function autoCalculateGrade(studentID, $row = null) {
    if (!studentID) return;
    
    const $targetRow = $row || jQuery(`input[id="marks_${studentID}"], .marks-input[data-student="${studentID}"]`).closest('tr');
    const marksInput = $targetRow.find(`.marks-input[data-student="${studentID}"]`);
    const gradeInput = $targetRow.find(`.grade-input[data-student="${studentID}"]`);
    const remarkInput = $targetRow.find(`.remark-input[data-student="${studentID}"]`);

    const marks = marksInput.val();

    if (marks && marks !== '' && !isNaN(marks)) {
        const result = calculateGradeAndRemark(marks);
        gradeInput.val(result.grade);
        remarkInput.val(result.remark);
    } else {
        gradeInput.val('');
        remarkInput.val('');
    }
}

// Add Results
function addResults(classSubjectID, isEdit = false) {
    const modalTitle = isEdit ? 'Edit Results' : 'Add Results';
    jQuery('#addEditResultsModalLabel').html(`<i class="bi bi-${isEdit ? 'pencil' : 'plus-circle'}"></i> ${modalTitle}`);
    jQuery('#addEditResultsModal').modal('show');
    jQuery('#addEditResultsModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"></div></div>');

    // Clear exam data cache when opening modal
    window.examDataCache = {};
    resetQuestionData();

    // Get students and examinations for this subject
    jQuery.ajax({
        url: '/get_subject_students/' + classSubjectID,
        method: 'GET',
        success: function(studentsResponse) {
            jQuery.ajax({
                url: '/get_examinations_for_subject/' + classSubjectID,
                method: 'GET',
                success: function(examsResponse) {
                    let html = `
                        <form id="resultsForm">
                            <input type="hidden" id="class_subject_id" value="${classSubjectID}">
                            <div class="form-group">
                                <label>Select Examination <span class="text-danger">*</span></label>
                                <select class="form-control" id="exam_id" name="exam_id" required onchange="handleExamSelection(${classSubjectID}, this.value)">
                                    <option value="">Select Examination</option>
                    `;

                    // Store exam data globally for use in handleExamSelection
                    window.examDataCache = window.examDataCache || {};

                    if (examsResponse.success && examsResponse.examinations) {
                        examsResponse.examinations.forEach(function(exam) {
                            // Only show examinations where enter_result is true
                            if (exam.enter_result === true || exam.enter_result === 1) {
                                // Store exam data in cache
                                window.examDataCache[exam.examID] = exam;

                                const statusText = exam.status === 'awaiting_results' ? ' (Awaiting Results)' :
                                                  exam.status === 'ongoing' ? ' (Ongoing)' : ' (Results Available)';
                                const termClosedText = exam.is_term_closed ? ' (Term Closed - Not Editable)' : '';
                                const disabledAttr = exam.is_term_closed ? 'disabled style="background-color: #f0f0f0; color: #999;"' : '';
                                html += `<option value="${exam.examID}" data-status="${exam.status}" data-term-closed="${exam.is_term_closed}" data-enter-result="${exam.enter_result}" ${disabledAttr}>${exam.exam_name} (${exam.year})${statusText}${termClosedText}</option>`;
                            }
                        });
                    }

                    html += `
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Only examinations with "Enter Result" enabled can be used.
                                </small>
                            </div>
                            <!-- Auto-selected Week Display -->
                            <div id="test_week_display_group" style="display: none;" class="alert alert-info py-2 mb-3">
                                <strong><i class="bi bi-calendar-event"></i> Recording Results for:</strong>
                                <span id="selected_week_label" class="ml-1"></span>
                            </div>

                            <!-- Week selection for Weekly Tests -->
                            <div class="form-group" id="test_week_group" style="display: none;">
                                <label for="test_week">Select Week <span class="text-danger">*</span></label>
                                <select class="form-control" id="test_week" name="test_week">
                                    <option value="">Select Week</option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calendar-event"></i> Results will be recorded for the selected week.
                                </small>
                            </div>
                            <div id="results-alert-container">
                                ${isSecondarySchool ? `
                                    <div class="alert alert-warning mt-3 secondary-only-alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Question-based results are enabled. Excel import/export is disabled.
                                    </div>
                                ` : `
                                    <div class="alert alert-info mt-3 non-secondary-alert">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-file-earmark-excel"></i>
                                                <strong>Excel Import/Export:</strong> Download template, fill in results, then upload.
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="fillRandomPrimaryResults()" id="fillRandomBtn" disabled>
                                                    <i class="bi bi-dice-5"></i> Fill Random
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" onclick="downloadExcelTemplate(${classSubjectID})" id="downloadExcelBtn" disabled>
                                                    <i class="bi bi-download"></i> Download Excel Template
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary ml-1" onclick="uploadExcelResults(${classSubjectID})" id="uploadExcelBtn" disabled>
                                                    <i class="bi bi-upload"></i> Upload Results
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `}
                            </div>
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead class="bg-primary-custom text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Admission No.</th>
                                             <th class="text-center questions-column-header" style="${isSecondarySchool ? '' : 'display:none;'}"><i class="bi bi-list-check"></i></th>
                                            <th>Marks</th>
                                            <th>Grade</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultsTableBody">
                    `;

                    if (studentsResponse.success && studentsResponse.students) {
                        studentsResponse.students.forEach(function(student, index) {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${student.first_name} ${student.middle_name || ''} ${student.last_name}</td>
                                    <td>${student.admission_number || 'N/A'}</td>
                                    <td class="text-center questions-column-cell" style="${isSecondarySchool ? '' : 'display:none;'}">
                                        <button type="button" class="btn btn-sm btn-outline-primary toggle-question-btn" data-student="${student.studentID}" title="Add result questions">
                                            <i class="bi bi-list-check"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm marks-input"
                                               name="marks[${student.studentID}]"
                                               id="marks_${student.studentID}"
                                               data-student="${student.studentID}"
                                               step="0.01" min="0" max="100" placeholder="0.00"
                                               oninput="autoCalculateGrade(${student.studentID})"
                                               ${isSecondarySchool ? 'readonly' : ''}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm grade-input"
                                               name="grade[${student.studentID}]"
                                               id="grade_${student.studentID}"
                                               data-student="${student.studentID}"
                                               placeholder="A, B, C..." readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm remark-input"
                                               name="remark[${student.studentID}]"
                                               id="remark_${student.studentID}"
                                               data-student="${student.studentID}"
                                               placeholder="Remark" readonly>
                                    </td>
                                </tr>
                                <tr class="question-details-row questions-detail-row d-none" id="question_details_${student.studentID}" data-student="${student.studentID}">
                                    <td colspan="${questionColspan}">
                                        <div class="question-detail-container" data-student="${student.studentID}">
                                            <div class="text-muted small">Select examination to load question formats.</div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    html += `
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-check-circle"></i> ${isEdit ? 'Update' : 'Save'} Results
                                </button>
                            </div>
                        </form>
                    `;

                    jQuery('#addEditResultsModalBody').html(html);
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load examinations',
                        icon: 'error',
                        confirmButtonColor: '#940000'
                    });
                }
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to load students',
                icon: 'error',
                confirmButtonColor: '#940000'
            });
        }
    });
}

// Form submission for results
jQuery(document).on('submit', '#resultsForm', function(e) {
    e.preventDefault();

    const classSubjectID = jQuery('#class_subject_id').val();
    const examID = jQuery('#exam_id').val();

    // Check if selected exam is in a closed term
    const selectedExam = jQuery('#exam_id option:selected');
    const isTermClosed = selectedExam.data('term-closed');
    if (isTermClosed) {
        Swal.fire({
            title: 'Access Denied!',
            text: 'You cannot edit results for this examination. The term has been closed.',
            icon: 'error',
            confirmButtonColor: '#940000'
        });
        return false;
    }

    // Check only if form is disabled (which happens if enter_result is false)
    const formDisabled = jQuery('#resultsForm button[type="submit"]').prop('disabled');
    if (formDisabled) {
        Swal.fire({
            title: 'Access Denied!',
            text: 'You are not allowed to enter results for this examination. Result entry has been disabled.',
            icon: 'error',
            confirmButtonColor: '#940000'
        });
        return false;
    }

    const results = [];

    const examIDForResults = jQuery('#exam_id').val();
    const selectedExamData = window.examDataCache[examIDForResults];
    const allowNoFormat = selectedExamData && (selectedExamData.allow_no_format == 1);

    if (isSecondarySchool && !allowNoFormat) {
        if (!examQuestionData.questions || examQuestionData.questions.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'No question formats found for this examination.',
                icon: 'warning',
                confirmButtonColor: '#940000'
            });
            return;
        }

        let hasValidationError = false;
        let hasResults = false;
        // Check if any marks have been entered in the current form
        jQuery('#resultsForm .marks-input').each(function() {
            const marks = jQuery(this).val();
            if (marks && marks !== '') {
                hasResults = true;
                return false; // Break loop
            }
        });

        // Scope each loop to the resultsForm to avoid picking up stale DOM elements
        jQuery('#resultsForm .marks-input').each(function() {
            const studentID = jQuery(this).data('student');
            const questionMarks = examQuestionData.marksByStudent[studentID] || {};
            const optionalRanges = examQuestionData.optionalRanges || [];
            const hasAnyMarks = Object.keys(questionMarks).length > 0;
            const selectedOptionalCounts = {};

            jQuery(`.optional-select[data-student="${studentID}"]:checked`).each(function() {
                const rangeNumber = parseInt(jQuery(this).data('optional-range'), 10);
                if (rangeNumber > 0) {
                    selectedOptionalCounts[rangeNumber] = (selectedOptionalCounts[rangeNumber] || 0) + 1;
                }
            });

            if (!hasAnyMarks) {
                return;
            }

            let total = 0;
            const questionPayload = [];
            const selectedOptionalIds = {};

            // IMPORTANT: Don't just look at DOM checkboxes (they don't exist if panel is closed)
            // Look at our marks cache to see which optional questions are active
            if (examQuestionData.marksByStudent[studentID]) {
                Object.keys(examQuestionData.marksByStudent[studentID]).forEach(function(qId) {
                    selectedOptionalIds[qId] = true;
                });
            }
            // Also include anything currently checked in the DOM (for newly selected ones)
            jQuery(`.optional-select[data-student="${studentID}"]:checked`).each(function() {
                selectedOptionalIds[jQuery(this).data('question-id')] = true;
            });

            for (let i = 0; i < examQuestionData.questions.length; i++) {
                const question = examQuestionData.questions[i];
                const value = questionMarks[question.exam_paper_questionID];
                const isOptional = question.is_optional;
                const isSelectedOptional = selectedOptionalIds[question.exam_paper_questionID] === true;

                if (isOptional && !isSelectedOptional) {
                    continue;
                }

                const numericValue = (value === undefined || value === null || value === '') ? 0 : parseFloat(value);
                if (isNaN(numericValue) || numericValue < 0 || numericValue > parseFloat(question.marks)) {
                    hasValidationError = true;
                    Swal.fire({
                        title: 'Error!',
                        text: 'Question marks must not exceed the allowed maximum (Max: ' + question.marks + ').',
                        icon: 'error',
                        confirmButtonColor: '#940000'
                    });
                    return false;
                }

                total += numericValue;
                questionPayload.push({
                    question_id: question.exam_paper_questionID,
                    marks: numericValue
                });
            }

            // Skip this student if their total is 0 (all questions blank/zero = not entered)
            if (total <= 0) {
                return;
            }

            const displayTotal = Number.isInteger(total) ? total : total.toFixed(2);
            jQuery(`#marks_${studentID}`).val(displayTotal);
            autoCalculateGrade(studentID);

            results.push({
                studentID: studentID,
                marks: total,
                grade: jQuery(`.grade-input[data-student="${studentID}"]`).val() || null,
                remark: jQuery(`.remark-input[data-student="${studentID}"]`).val() || null,
                question_marks: questionPayload
            });
        });

        if (hasValidationError) {
            return;
        }
    } else {
        // Scope each loop to the resultsForm to avoid picking up stale DOM elements
        jQuery('#resultsForm .marks-input').each(function() {
            const studentID = jQuery(this).data('student');
            const marks = jQuery(this).val();
            const grade = jQuery(`.grade-input[data-student="${studentID}"]`).val();
            const remark = jQuery(`.remark-input[data-student="${studentID}"]`).val();

            if (marks || grade || remark) {
                results.push({
                    studentID: studentID,
                    marks: marks || null,
                    grade: grade || null,
                    remark: remark || null
                });
            }
        });
    }

    if (results.length === 0) {
        Swal.fire({
            title: 'Warning!',
            text: 'Please enter at least one result',
            icon: 'warning',
            confirmButtonColor: '#940000'
        });
        return;
    }

    Swal.fire({
        title: 'Saving...',
        text: 'Please wait while we save the results.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    jQuery.ajax({
        url: '/save_subject_results',
        method: 'POST',
        data: {
            class_subjectID: classSubjectID,
            examID: examID,
            test_week: jQuery('#test_week').val(),
            results: JSON.stringify(results) // Stringify to bypass PHP max_input_vars limit
        },
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.success || 'Results saved successfully!',
                icon: 'success',
                confirmButtonColor: '#940000'
            }).then(() => {
                jQuery('#addEditResultsModal').modal('hide');
            });
        },
        error: function(xhr) {
            if (xhr.status === 409) {
                Swal.fire({
                    title: 'Existing Results!',
                    text: (xhr.responseJSON && xhr.responseJSON.error) || 'Results already exist for this week. You cannot enter results twice.',
                    icon: 'warning',
                    confirmButtonColor: '#940000'
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: (xhr.responseJSON && xhr.responseJSON.error) || 'Failed to save results',
                    icon: 'error',
                    confirmButtonColor: '#940000'
                });
            }
        }
    });

    // Upload Excel Form Handler
    jQuery('#uploadExcelForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const classSubjectID = jQuery('#upload_class_subject_id').val();
        const examID = jQuery('#upload_exam_id').val();

        if (!examID) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select an examination first.',
                icon: 'error',
                confirmButtonColor: '#940000'
            });
            return;
        }

        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while we process your Excel file.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        jQuery.ajax({
            url: '/upload_excel_results',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Results uploaded successfully!',
                    icon: 'success',
                    confirmButtonColor: '#940000'
                }).then(() => {
                    jQuery('#uploadExcelModal').modal('hide');
                    // Reload the results form
                    const currentClassSubjectID = jQuery('#class_subject_id').val();
                    const currentExamID = jQuery('#exam_id').val();
                    if (currentClassSubjectID && currentExamID) {
                        loadExistingResults(currentClassSubjectID, currentExamID);
                    }
                });
            },
            error: function(xhr) {
                let errorMsg = 'Failed to upload Excel file.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonColor: '#940000'
                });
            }
        });
    });
});

// Handle exam selection - enable/disable Excel buttons
function handleExamSelection(classSubjectID, examID) {
    jQuery('#downloadExcelBtn, #uploadExcelBtn, #fillRandomBtn').prop('disabled', true);
    jQuery('#test_week_group, #test_week_display_group').hide();
    jQuery('#test_week').prop('required', false).val('');
    if (!examID) {
        disableResultsForm();
        resetQuestionData();
        return;
    }

    const exam = window.examDataCache[examID];
    if (exam) {
        // Check if teacher is allowed to enter results
        const enterResult = exam.enter_result === true || exam.enter_result === 1;

        if (!enterResult) {
            disableResultsForm();
            jQuery('#downloadExcelBtn').prop('disabled', true);
            jQuery('#uploadExcelBtn').prop('disabled', true);
            showResultsStatusError('You are not allowed to enter results for this examination. Result entry has been disabled.');
            resetQuestionData();
            return;
        }

        // If enter_result is true, enable form - no other checks
        enableResultsForm(exam);
        jQuery('#downloadExcelBtn, #uploadExcelBtn, #fillRandomBtn').prop('disabled', false);
        jQuery('.results-status-error').remove();


        // Check allow_no_format to toggle alerts and excel buttons
        if (exam.allow_no_format == 1) {
            jQuery('.secondary-only-alert').hide();
            jQuery('.questions-column-header, .questions-column-cell').hide();
            // Show Excel options if it was secondary school but no format allowed
            if (isSecondarySchool) {
               // We need to ensure the Excel alert is available or show/hide carefully
            }
        } else {
            jQuery('.secondary-only-alert').show();
            if (isSecondarySchool) {
               jQuery('.questions-column-header, .questions-column-cell').show();
            }
        }

        // Load existing results after enabling form
        loadExistingResults(classSubjectID, examID);
        loadExamQuestionData(classSubjectID, examID);
    } else {
        // Fallback: try to get exam data from option attributes
        const selectedOption = jQuery(`#exam_id option[value="${examID}"]`);
        const enterResult = selectedOption.data('enter-result') === true || selectedOption.data('enter-result') === 1;

        if (!enterResult) {
            disableResultsForm();
            jQuery('#downloadExcelBtn').prop('disabled', true);
            jQuery('#uploadExcelBtn').prop('disabled', true);
            showResultsStatusError('You are not allowed to enter results for this examination. Result entry has been disabled.');
            resetQuestionData();
            return;
        }

        enableResultsForm();
        jQuery('#downloadExcelBtn').prop('disabled', false);
        jQuery('#uploadExcelBtn').prop('disabled', false);
        jQuery('.results-status-error').remove();
        loadExistingResults(classSubjectID, examID);
        loadExamQuestionData(classSubjectID, examID);
    }
}

// Helper function to disable form inputs
function disableResultsForm() {
    jQuery('.marks-input, .grade-input, .remark-input').prop('disabled', true).css({
        'background-color': '#e9ecef',
        'cursor': 'not-allowed',
        'color': '#dc3545'
    });
    jQuery('.question-mark-input, .toggle-question-btn').prop('disabled', true);
    jQuery('#resultsForm button[type="submit"]').prop('disabled', true);
}

// Helper function to enable form inputs
function enableResultsForm(exam = null) {
    jQuery('.marks-input, .grade-input, .remark-input').prop('disabled', false).css({
        'background-color': '',
        'cursor': '',
        'color': ''
    });
    jQuery('.grade-input, .remark-input').prop('readonly', true); // Keep readonly for grade and remark
    
    // Check allow_no_format
    const allowNoFormat = exam && (exam.allow_no_format == 1);
    
    if (isSecondarySchool && !allowNoFormat) {
        jQuery('.marks-input').prop('readonly', true);
    } else {
        jQuery('.marks-input').prop('readonly', false);
    }
    jQuery('.question-mark-input, .toggle-question-btn').prop('disabled', false);
    jQuery('#resultsForm button[type="submit"]').prop('disabled', false);
}

// Helper function to show error message
function showResultsStatusError(message) {
    // Remove existing error messages
    jQuery('.results-status-error').remove();

    // Add error message above the table
    const errorHtml = `
        <div class="alert alert-danger results-status-error mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Access Denied:</strong> ${message}
        </div>
    `;
    jQuery('#resultsTableBody').closest('.table-responsive').before(errorHtml);
}

// Toggle question details per student
jQuery(document).on('click', '.toggle-question-btn', function() {
    if (examQuestionData.questions.length === 0) {
        Swal.fire({
            title: 'No Question Formats',
            text: 'Please select an examination with approved question formats first.',
            icon: 'warning',
            confirmButtonColor: '#940000'
        });
        return;
    }
    const studentID = jQuery(this).data('student');
    const $detailRow = jQuery(`#question_details_${studentID}`);
    if ($detailRow.length === 0) {
        return;
    }

    // Always re-render to ensure fresh data (marks, optional selections)
    $detailRow.find('.question-detail-container').html(buildQuestionDetails(studentID));
    $detailRow.addClass('loaded');
    // Question fields were already rendered with correct checkbox state and disabled state in buildQuestionDetails


    // Sync rendered input values back into examQuestionData.marksByStudent
    // ONLY for inputs that are ENABLED (i.e. selected optional questions or mandatory)
    // Disabled optional inputs must NOT be synced - they carry stale values
    if (!examQuestionData.marksByStudent[studentID]) {
        examQuestionData.marksByStudent[studentID] = {};
    }
    $detailRow.find('.question-mark-input').each(function() {
        const questionId = jQuery(this).data('question-id');
        const isDisabled = jQuery(this).prop('disabled');
        const val = jQuery(this).val();

        if (isDisabled) {
            // This optional question was NOT selected — remove it from cache
            delete examQuestionData.marksByStudent[studentID][questionId];
        } else if (val !== '' && val !== null && val !== undefined) {
            examQuestionData.marksByStudent[studentID][questionId] = val;
        }
    });

    updateStudentTotal(studentID);

    $detailRow.toggleClass('d-none');
});

jQuery(document).on('change', '.optional-select', function() {
    const $checkbox = jQuery(this);
    const studentID = $checkbox.data('student');
    const rangeNumber = parseInt($checkbox.data('optional-range'), 10);
    const questionId = $checkbox.data('question-id');
    const $input = jQuery(`.question-mark-input[data-student="${studentID}"][data-question-id="${questionId}"]`);
    const rangeMeta = (examQuestionData.optionalRanges || []).find(r => r.range_number == rangeNumber);
    const requiredCount = rangeMeta ? parseInt(rangeMeta.required_questions || 0, 10) : 0;
    const selectedCount = jQuery(`.optional-select[data-student="${studentID}"][data-optional-range="${rangeNumber}"]:checked`).length;

    if (requiredCount > 0 && selectedCount > requiredCount) {
        $checkbox.prop('checked', false);
        Swal.fire({
            title: 'Optional Limit',
            text: `Question needed to opt is ${requiredCount}. You exceed the limit.`,
            icon: 'warning',
            confirmButtonColor: '#940000'
        });
        return;
    }

    if ($checkbox.is(':checked')) {
        $input.prop('disabled', false);
    } else {
        $input.val('');
        $input.prop('disabled', true);
        if (examQuestionData.marksByStudent[studentID]) {
            delete examQuestionData.marksByStudent[studentID][questionId];
        }
    }
    updateStudentTotal(studentID);
});

// Question marks input handler
jQuery(document).on('input', '.question-mark-input', function() {
    const $input = jQuery(this);
    const max = parseFloat($input.data('max'));
    let value = parseFloat($input.val());
    const $warning = $input.siblings('.question-max-warning');
    const isOptional = $input.data('optional') == 1;
    const rangeNumber = parseInt($input.data('optional-range'), 10);

    if (!isNaN(value) && value > max) {
        value = max;
        $input.val(max);
        $warning.text(`Max ${max}`).removeClass('d-none');
    } else {
        $warning.addClass('d-none').text('');
    }

    const studentID = $input.data('student');
    const questionId = $input.data('question-id');

    if (!studentID || !questionId) return;

    if (!examQuestionData.marksByStudent[studentID]) {
        examQuestionData.marksByStudent[studentID] = {};
    }

    if (isOptional && rangeNumber > 0) {
        let optionalSum = 0;
        jQuery(`.question-mark-input[data-student="${studentID}"]`).each(function() {
            if (parseInt(jQuery(this).data('optional-range'), 10) === rangeNumber) {
                const val = parseFloat(jQuery(this).val());
                if (!isNaN(val)) {
                    optionalSum += val;
                }
            }
        });

        const rangeTotal = (examQuestionData.optionalRanges || []).find(r => r.range_number == rangeNumber);
        const rangeLimit = rangeTotal ? parseFloat(rangeTotal.total_marks) : 0;

        if (rangeLimit > 0 && optionalSum > rangeLimit) {
            const otherSum = optionalSum - (isNaN(value) ? 0 : value);
            const remaining = Math.max(rangeLimit - otherSum, 0);
            $input.val(remaining);
            value = remaining;
        }
    }

    if ($input.val() === '') {
        delete examQuestionData.marksByStudent[studentID][questionId];
    } else {
        examQuestionData.marksByStudent[studentID][questionId] = $input.val();
    }

    updateStudentTotal(studentID);
});

// Helper function to check if in edit mode
function checkIfEditMode(examID, classSubjectID) {
    // Check if any results exist for this exam and class subject
    let hasResults = false;
    jQuery('#resultsForm .marks-input').each(function() {
        const marks = jQuery(this).val();
        if (marks && marks !== '') {
            hasResults = true;
            return false; // Break loop
        }
    });
    return hasResults;
}

// Download Excel Template
function downloadExcelTemplate(classSubjectID) {
    const examID = jQuery('#exam_id').val();

    if (!examID) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select an examination first.',
            icon: 'error',
            confirmButtonColor: '#940000'
        });
        return;
    }

    window.location.href = `/download_excel_template/${classSubjectID}/${examID}`;
}

// Show Upload Excel Modal
function showUploadExcelModal(classSubjectID) {
    const examID = jQuery('#exam_id').val();

    if (!examID) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select an examination first.',
            icon: 'error',
            confirmButtonColor: '#940000'
        });
        return;
    }

    jQuery('#upload_class_subject_id').val(classSubjectID);
    jQuery('#upload_exam_id').val(examID);
    jQuery('#excel_file').val('');
    jQuery('#excel_file_label').html('<i class="bi bi-file-earmark-excel"></i> Choose Excel file (.xlsx or .xls)');
    jQuery('#uploadExcelModal').modal('show');

    // Update file label when file is selected
    jQuery('#excel_file').on('change', function() {
        var fileName = jQuery(this).val().split('\\').pop();
        if (fileName) {
            jQuery('#excel_file_label').html('<i class="bi bi-file-earmark-excel-fill"></i> ' + fileName);
        } else {
            jQuery('#excel_file_label').html('<i class="bi bi-file-earmark-excel"></i> Choose Excel file (.xlsx or .xls)');
        }
    });
}

// View Session Attendance
function viewSessionAttendance(classSubjectID) {
    jQuery('#sessionAttendanceModal').modal('show');
    jQuery('#sessionAttendanceModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"><span class="sr-only">Loading...</span></div></div>');

    // Load session attendance view
    jQuery.get(`/teacher/session-attendance/${classSubjectID}`)
    .done(function(response) {
        jQuery('#sessionAttendanceModalBody').html(response);
    })
    .fail(function(xhr) {
        jQuery('#sessionAttendanceModalBody').html('<div class="alert alert-danger">Failed to load session attendance. Please try again.</div>');
    });
}

// View Exam Attendance
function viewExamAttendance(classSubjectID, subjectID) {
    jQuery('#examAttendanceModal').modal('show');
    jQuery('#examAttendanceModalBody').html('<div class="text-center"><div class="spinner-border text-primary-custom" role="status"><span class="sr-only">Loading...</span></div></div>');

    // Load exam attendance view
    jQuery.get(`/teacher/exam-attendance/${classSubjectID}`, {
        subjectID: subjectID
    })
    .done(function(response) {
        jQuery('#examAttendanceModalBody').html(response);
    })
    .fail(function(xhr) {
        jQuery('#examAttendanceModalBody').html('<div class="alert alert-danger">Failed to load exam attendance. Please try again.</div>');
    });
}
// Helper function to generate weeks for the current year
// Helper function to generate periods (weeks/months) for the current year
function generatePeriods(examID, classSubjectID, testType) {
    const $weekSelect = jQuery('#test_week');
    $weekSelect.empty().append('<option value="">Select Period</option>');

    const today = new Date();
    const currentYear = today.getFullYear();
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    function formatDateRange(start, end) {
        const startMonth = monthNames[start.getMonth()];
        const endMonth = monthNames[end.getMonth()];
        const startDay = start.getDate();
        const endDay = end.getDate();
        const year = end.getFullYear();

        return `${startMonth} ${startDay} to ${endMonth} ${endDay}, ${year}`;
    }

    if (testType === 'monthly_test') {
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        for (let i = 0; i < 12; i++) {
            const periodVal = `Month of ${months[i]} ${currentYear}`;
            $weekSelect.append(`<option value="${periodVal}">${periodVal}</option>`);
            // Auto-select current month
            if (i === today.getMonth()) {
                $weekSelect.val(periodVal);
            }
        }
    } else {
        // Find current week Monday
        const curr = new Date(today);
        const day = curr.getDay(); // 0 is Sunday, 1 is Monday...
        const diff = curr.getDate() - day + (day == 0 ? -6 : 1);
        const monday = new Date(curr.setDate(diff));
        monday.setHours(0,0,0,0);

        // Previous Week (Optional, but user said currently and one ahead)
        // Let's add current and next week as requested
        for (let i = 0; i < 2; i++) {
            const weekStart = new Date(monday);
            weekStart.setDate(monday.getDate() + (i * 7));
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);

            const startStr = weekStart.toISOString().split('T')[0];
            const endStr = weekEnd.toISOString().split('T')[0];
            const weekVal = `Week of ${startStr} to ${endStr}`;
            const weekLabel = formatDateRange(weekStart, weekEnd) + (i === 0 ? " (Current Week)" : " (Next Week)");

            $weekSelect.append(`<option value="${weekVal}">${weekLabel}</option>`);
            if (i === 0) {
                $weekSelect.val(weekVal);
            }
        }
    }

    // Add listener for period change
    $weekSelect.off('change').on('change', function() {
        if (jQuery(this).val()) {
            loadExistingResults(classSubjectID, examID, jQuery(this).val());
        } else {
            jQuery('.marks-input, .grade-input, .remark-input').val('');
        }
    });

    if ($weekSelect.val()) {
        $weekSelect.trigger('change');
    }
}

// Function to open Election Modal
function openElectionModal(classSubjectID, subjectName, subclassID) {
    if (!subclassID || subclassID === 0) {
        Swal.fire('Error', 'This subject is assigned to the entire class. Election is only supported for specific subclasses.', 'warning');
        return;
    }

    jQuery('#electionSubjectTitle').text(subjectName + ' Election');
    jQuery('#electionStudentsContainer').html(
        '<div class="text-center py-4">' +
        '<div class="spinner-border text-primary-custom" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
        '<p class="mt-2">Loading students...</p>' +
        '</div>'
    );

    jQuery('#saveElectionBtn').data('class-subject-id', classSubjectID);
    jQuery('#saveElectionBtn').data('subclass-id', subclassID);

    jQuery('#subjectElectionModal').modal('show');

    // Load students
    loadStudentsForElection(classSubjectID, subclassID);
}

function loadStudentsForElection(classSubjectID, subclassID) {
    jQuery.ajax({
        url: "{{ url('get_subclass_students') }}/" + subclassID,
        type: "GET",
        dataType: 'json',
        success: function(response) {
            if (response.success && response.students && response.students.length > 0) {
                // Fetch already elected
                jQuery.ajax({
                    url: "{{ url('get_subject_electors') }}/" + classSubjectID,
                    type: "GET",
                    dataType: 'json',
                    success: function(electorsResponse) {
                        var electedStudentIDs = [];
                        if (electorsResponse.success && electorsResponse.electors) {
                            electedStudentIDs = electorsResponse.electors.map(function(e) {
                                return e.studentID;
                            });
                        }

                        var html = '<div class="table-responsive">';
                        html += '<table class="table table-hover table-bordered" id="electionStudentsTable">';
                        html += '<thead class="bg-primary-custom text-white">';
                        html += '<tr>';
                        html += '<th>#</th>';
                        html += '<th>Student Name</th>';
                        html += '<th>Admission No</th>';
                        html += '<th style="text-align: center;">Election</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody>';

                        response.students.forEach(function(student, index) {
                            var isElected = electedStudentIDs.includes(student.studentID);
                            html += '<tr data-student-id="' + student.studentID + '">';
                            html += '<td>' + (index + 1) + '</td>';
                            html += '<td>' + student.first_name + ' ' + (student.middle_name ? student.middle_name + ' ' : '') + student.last_name + '</td>';
                            html += '<td>' + (student.admission_number || 'N/A') + '</td>';
                            html += '<td style="text-align: center;">';
                            if (isElected) {
                                html += '<button class="btn btn-sm btn-danger deselect-student-btn" ';
                                html += 'data-student-id="' + student.studentID + '" ';
                                html += 'data-class-subject-id="' + classSubjectID + '" ';
                                html += 'data-student-name="' + student.first_name + ' ' + student.last_name + '">';
                                html += '<i class="bi bi-x-circle"></i> Deselect';
                                html += '</button>';
                            } else {
                                html += '<input type="checkbox" class="form-check-input election-checkbox" ';
                                html += 'data-student-id="' + student.studentID + '" ';
                                html += 'value="' + student.studentID + '">';
                            }
                            html += '</td>';
                            html += '</tr>';
                        });

                        html += '</tbody></table></div>';
                        jQuery('#electionStudentsContainer').html(html);

                        if (jQuery.fn.DataTable) {
                            jQuery('#electionStudentsTable').DataTable({
                                "pageLength": 25,
                                "order": [[1, "asc"]]
                            });
                        }
                    },
                    error: function() {
                        jQuery('#electionStudentsContainer').html('<div class="alert alert-danger">Error fetching electors data.</div>');
                    }
                });
            } else {
                jQuery('#electionStudentsContainer').html('<div class="alert alert-info">No students found in this subclass.</div>');
            }
        },
        error: function() {
            jQuery('#electionStudentsContainer').html('<div class="alert alert-danger">Failed to load students.</div>');
        }
    });
}

// Select All Handler
jQuery(document).on('click', '#electionSelectAllBtn', function() {
    if (typeof jQuery.fn.DataTable !== 'undefined' && jQuery.fn.DataTable.isDataTable('#electionStudentsTable')) {
        var table = jQuery('#electionStudentsTable').DataTable();
        table.rows().every(function() {
            var $row = jQuery(this.node());
            $row.find('.election-checkbox').prop('checked', true);
        });
    }
    jQuery('.election-checkbox').prop('checked', true);
    
    Swal.fire({
        icon: 'success',
        title: 'Selected All',
        timer: 1000,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
});

// Deselect All Handler
jQuery(document).on('click', '#electionDeselectAllBtn', function() {
    if (typeof jQuery.fn.DataTable !== 'undefined' && jQuery.fn.DataTable.isDataTable('#electionStudentsTable')) {
        var table = jQuery('#electionStudentsTable').DataTable();
        table.rows().every(function() {
            var $row = jQuery(this.node());
            $row.find('.election-checkbox').prop('checked', false);
        });
    }
    jQuery('.election-checkbox').prop('checked', false);
    
    Swal.fire({
        icon: 'info',
        title: 'Deselected All',
        timer: 1000,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
});

// Save Election Handler
jQuery(document).on('click', '#saveElectionBtn', function() {
    var classSubjectID = jQuery(this).data('class-subject-id');
    var subclassID = jQuery(this).data('subclass-id');
    var selectedStudents = [];
    
    if (typeof jQuery.fn.DataTable !== 'undefined' && jQuery.fn.DataTable.isDataTable('#electionStudentsTable')) {
        var table = jQuery('#electionStudentsTable').DataTable();
        table.rows().every(function() {
            var $row = jQuery(this.node());
            var $checkbox = $row.find('.election-checkbox');
            if ($checkbox.is(':checked') && $row.find('.deselect-student-btn').length === 0) {
                selectedStudents.push($checkbox.val());
            }
        });
    } else {
        jQuery('.election-checkbox:checked').each(function() {
            var $row = jQuery(this).closest('tr');
            if ($row.find('.deselect-student-btn').length === 0) {
                selectedStudents.push(jQuery(this).val());
            }
        });
    }

    var $btn = jQuery(this);
    var originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');

    jQuery.ajax({
        url: "{{ route('save_subject_election') }}",
        type: "POST",
        data: {
            classSubjectID: classSubjectID,
            subclassID: subclassID,
            selectedStudents: selectedStudents,
            _token: jQuery('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $btn.prop('disabled', false).html(originalText);
            if (response.success) {
                jQuery('#subjectElectionModal').modal('hide');
                Swal.fire('Success', response.success, 'success').then(() => {
                    location.reload();
                });
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html(originalText);
            Swal.fire('Error', 'Failed to save election.', 'error');
        }
    });
});

// Deselect Individual Handler
jQuery(document).on('click', '.deselect-student-btn', function() {
    var $btn = jQuery(this);
    var studentID = $btn.data('student-id');
    var classSubjectID = $btn.data('class-subject-id');
    var studentName = $btn.data('student-name');

    Swal.fire({
        title: 'Deselect Student?',
        text: 'Remove ' + studentName + ' from this subject?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, remove!'
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: "{{ route('deselect_student') }}",
                type: "POST",
                data: {
                    classSubjectID: classSubjectID,
                    studentID: studentID,
                    _token: jQuery('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Removed', response.success, 'success').then(() => {
                            loadStudentsForElection(classSubjectID, jQuery('#saveElectionBtn').data('subclass-id'));
                        });
                    }
                }
            });
        }
    });
});
</script>

@include('includes.footer')
