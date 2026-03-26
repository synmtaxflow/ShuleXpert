<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Examination;
use App\Models\Result;
use App\Models\ResultApproval;
use App\Models\Role;
use App\Models\Student;
use App\Models\Subclass;
use App\Models\SchoolSubject;
use App\Models\ClassSubject;
use App\Models\GradeDefinition;
use App\Models\ExamPaper;
use App\Models\ExamPaperQuestion;
use App\Models\ExamPaperQuestionMark;
use App\Models\ExamPaperOptionalRange;
use App\Models\Teacher;
use App\Models\TermReportDefinition;
use App\Models\CaDefinition;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ResultManagementController extends Controller
{
    /**
     * Helper to check permissions with hierarchy support
     */
    private function hasPermission($permission)
    {
        $user_type = Session::get('user_type');
        if ($user_type === 'Admin') {
            return true;
        }

        $teacherPermissions = Session::get('teacherPermissions') ?? collect();
        
        // Define permission aliases for hierarchy
        $aliases = [
            'view_results' => ['view_results', 'result_read_only', 'result_create', 'result_update', 'result_delete', 'manage_results', 'manageResults'],
            'view_class_subjects' => ['view_class_subjects', 'subject_read_only', 'subject_create', 'subject_update', 'subject_delete', 'manage_subjects'],
            'view_exams' => ['view_exams', 'examination_read_only', 'examination_create', 'examination_update', 'examination_delete', 'manage_exam'],
            'view_classes' => ['view_classes', 'classes_read_only', 'classes_create', 'classes_update', 'classes_delete', 'manage_classes', 'create_class', 'edit_class'],
        ];
        
        // If the teacher has broad modify permissions, they also have view permissions
        if (isset($aliases[$permission])) {
            if ($teacherPermissions->intersect($aliases[$permission])->isNotEmpty()) {
                return true;
            }
        }

        return $teacherPermissions->contains($permission);
    }

    /**
     * Display result management page
     */
    public function index(Request $request)
    {
        $userType = Session::get('user_type');
        $schoolID = Session::get('schoolID');
        $teacherID = Session::get('teacherID');

        // Allow both Admin and Teacher access
        if (!$schoolID) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        // Get school details
        $school = \App\Models\School::find($schoolID);
        $schoolType = $school ? $school->school_type : 'Secondary';

        // Get filter parameters
        $termFilter = $request->get('term', '');
        $yearFilter = $request->get('year', date('Y'));
        $typeFilter = $request->get('type', 'exam'); // 'exam' or 'report'
        $statusFilter = $request->get('status', 'active'); // 'active', 'all', 'history'
        $classFilter = $request->get('class', '');
        $subclassFilter = $request->get('subclass', '');
        $subclassIDParam = $request->get('subclassID', ''); // From classManagement link
        $classIDParam = $request->get('classID', ''); // From coordinator view
        $isCoordinatorView = $request->get('coordinator') === 'true';
        $examFilter = $request->get('examID', ''); // Specific exam filter
        $weekFilter = $request->get('week', ''); // Week filter for weekly/monthly tests
        $subjectFilter = $request->get('subjectID', ''); // Specific subject filter

        // BROAD PERMISSION CHECK: If teacher has broad result view permissions, don't restrict to "assigned class"
        $hasBroadResultPermission = $this->hasPermission('view_results');

        // Allow any user with 'result' menu permission to view all classes like Admin
        if (!$hasBroadResultPermission && $userType !== 'Admin') {
            $hasResultPermission = false;
            
            if ($userType === 'Teacher') {
                $teacherID = Session::get('teacherID');
                if ($teacherID) {
                    $roles = DB::table('role_user')->where('teacher_id', $teacherID)->pluck('role_id');
                    $perms = DB::table('permissions')->whereIn('role_id', $roles)->get();
                    if ($perms->where('permission_category', 'result')->count() > 0) {
                        $hasResultPermission = true;
                    } elseif ($perms->contains(function($p) { return strpos(strtolower($p->name), 'result') !== false; })) {
                        $hasResultPermission = true;
                    }
                }
            } elseif ($userType === 'Staff') {
                $staffID = Session::get('staffID');
                if ($staffID) {
                    $profId = DB::table('other_staff')->where('id', $staffID)->value('profession_id');
                    if ($profId) {
                        $perms = DB::table('staff_permissions')->where('profession_id', $profId)->get();
                        if ($perms->where('permission_category', 'result')->count() > 0) {
                            $hasResultPermission = true;
                        } elseif ($perms->contains(function($p) { return strpos(strtolower($p->name), 'result') !== false; })) {
                            $hasResultPermission = true;
                        }
                    }
                }
            }
            
            if ($hasResultPermission) {
                $hasBroadResultPermission = true;
            }
        }

        // Check if coordinator view is requested
        $isCoordinatorResultsView = false;
        if ($isCoordinatorView && !empty($classIDParam) && !empty($teacherID)) {
            // Verify teacher is coordinator of this main class
            $mainClass = \App\Models\ClassModel::find($classIDParam);
            if ($mainClass && ($mainClass->teacherID == $teacherID || $hasBroadResultPermission) && $mainClass->schoolID == $schoolID) {
                // Set default class filter for coordinator view
                $classFilter = $classIDParam;
                $isCoordinatorResultsView = true;
                // Coordinator can only choose subclass, main class is pre-selected and locked
            } else {
                // Coordinator doesn't have access to this class
                if ($userType === 'Teacher' && !$hasBroadResultPermission) {
                    return redirect()->route('AdmitedClasses', ['coordinator' => 'true'])
                        ->with('error', 'Unauthorized access to this class')
                        ->with('error_type', 'unauthorized_access');
                }
            }
        }

        // If subclassID is provided (from classManagement), verify teacher access and set defaults
        $isTeacherView = false;
        if (!empty($subclassIDParam) && !empty($teacherID) && !$isCoordinatorResultsView) {
            // Verify teacher has access to this subclass
            $subclass = Subclass::with('class')->find($subclassIDParam);
            
            // Allow if teacher is assigned OR has broad permissions
            if ($subclass && ($subclass->teacherID == $teacherID || $hasBroadResultPermission)) {
                // Set default filters for teacher view
                $subclassFilter = $subclassIDParam;
                $classFilter = $subclass->classID;
                if ($subclass->teacherID == $teacherID && !$hasBroadResultPermission) {
                    $isTeacherView = true;
                }
            } else {
                // Teacher doesn't have access to this subclass
                if ($userType === 'Teacher' && !$hasBroadResultPermission) {
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'Unauthorized access to this class')
                        ->with('error_type', 'unauthorized_access');
                }
            }
        }

        // SECURITY: For regular teachers without broad permissions, always verify they have access to the selected subclass
        if ($userType === 'Teacher' && !empty($teacherID) && !$hasBroadResultPermission) {
            // If subclassID param is provided, use it
            if (!empty($subclassIDParam)) {
                $subclass = Subclass::with('class')->find($subclassIDParam);
                if ($subclass && $subclass->teacherID == $teacherID) {
                    $subclassFilter = $subclassIDParam;
                    $classFilter = $subclass->classID;
                    $isTeacherView = true;
                }
            }

            // Priority 2: If subclassFilter is set (from form submission), verify access
            if (!empty($subclassFilter) && !$isTeacherView) {
                $subclass = Subclass::with('class')->find($subclassFilter);
                if (!$subclass) {
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'Invalid class ID')
                        ->with('error_type', 'invalid_class');
                }

                // Verify teacher is the class teacher of this subclass
                if ($subclass->teacherID != $teacherID) {
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'You do not have access to view results for this class')
                        ->with('error_type', 'unauthorized_access');
                }

                // Force teacher view mode and lock filters
                $isTeacherView = true;
                $classFilter = $subclass->classID; // Ensure class filter matches subclass
            }

            // If isTeacherView is true, subclassFilter MUST be set
            if ($isTeacherView && empty($subclassFilter)) {
                return redirect()->route('AdmitedClasses')
                    ->with('error', 'Please select a class to view results')
                    ->with('error_type', 'no_subclass_selected');
            }
        }

        // Get available years
        $availableYears = Examination::where('schoolID', $schoolID)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Get classes for filter
        $classes = ClassModel::where('schoolID', $schoolID)
            ->where('status', 'Active')
            ->orderBy('class_name')
            ->get();

        // Get subclasses for filter (only for selected main class) with display names
        $subclasses = collect();
        $allSubclasses = collect();

        // If coordinator view, get all subclasses for the main class
        if ($isCoordinatorResultsView && $classFilter) {
            $classSubclasses = Subclass::with('class')
                ->where('classID', $classFilter)
                ->where('status', 'Active')
                ->orderBy('subclass_name')
                ->get();

            // Group by classID for consistency with other views
            $allSubclasses = $classSubclasses->groupBy('classID');
        } elseif ($userType === 'Teacher' && !empty($teacherID) && !$isCoordinatorResultsView && !$hasBroadResultPermission) {
            // SECURITY: For regular teachers (class teacher view), only show their assigned subclasses
            if ($classFilter) {
                // Get only teacher's subclasses in the selected class
                $allSubclasses = Subclass::with('class')
                    ->whereHas('class', function($query) use ($schoolID, $classFilter) {
                        $query->where('schoolID', $schoolID)
                              ->where('classID', $classFilter);
                    })
                    ->where('teacherID', $teacherID)
                    ->where('status', 'Active')
                    ->get()
                    ->groupBy('classID');
            } else {
                // Get all teacher's assigned subclasses
                $allSubclasses = Subclass::with('class')
                    ->whereHas('class', function($query) use ($schoolID) {
                        $query->where('schoolID', $schoolID);
                    })
                    ->where('teacherID', $teacherID)
                    ->where('status', 'Active')
                    ->get()
                    ->groupBy('classID');
            }
        } elseif ($classFilter) {
            // Admin: Get subclasses for selected class
            $allSubclasses = Subclass::with('class')
                ->whereHas('class', function($query) use ($schoolID, $classFilter) {
                    $query->where('schoolID', $schoolID)
                          ->where('classID', $classFilter);
                })
                ->where('status', 'Active')
                ->get()
                ->groupBy('classID');
        } else {
            // Admin: If no class selected, get all subclasses
            $allSubclasses = Subclass::with('class')
                ->whereHas('class', function($query) use ($schoolID) {
                    $query->where('schoolID', $schoolID);
                })
                ->where('status', 'Active')
                ->get()
                ->groupBy('classID');
        }

        // Format subclasses with display names
        foreach ($allSubclasses as $classID => $classSubclasses) {
            // If class has only one subclass and it's default (empty name), include it but show only class name
            if ($classSubclasses->count() === 1) {
                $subclass = $classSubclasses->first();
                if (trim($subclass->subclass_name) === '') {
                    // Default subclass - show only class name
                    $subclasses->push((object)[
                        'subclassID' => $subclass->subclassID,
                        'subclass_name' => $subclass->subclass_name,
                        'display_name' => $subclass->class->class_name,
                        'class_name' => $subclass->class->class_name,
                    ]);
                } else {
                    // Single subclass with name
                    $subclassName = trim($subclass->subclass_name);
                    $displayName = $isCoordinatorResultsView
                        ? (empty($subclassName) ? $subclass->class->class_name : $subclass->class->class_name . ' - ' . $subclassName)
                        : $subclass->class->class_name . ' ' . $subclass->subclass_name;
                    $subclasses->push((object)[
                        'subclassID' => $subclass->subclassID,
                        'subclass_name' => $subclass->subclass_name,
                        'display_name' => $displayName,
                        'class_name' => $subclass->class->class_name,
                    ]);
                }
            } else {
                // Multiple subclasses - show all with class_name + subclass_name
                foreach ($classSubclasses as $subclass) {
                    $subclassName = trim($subclass->subclass_name);
                    $displayName = empty($subclassName)
                        ? $subclass->class->class_name
                        : ($isCoordinatorResultsView
                            ? $subclass->class->class_name . ' - ' . $subclassName
                            : $subclass->class->class_name . ' ' . $subclassName);

                    $subclasses->push((object)[
                        'subclassID' => $subclass->subclassID,
                        'subclass_name' => $subclass->subclass_name,
                        'display_name' => $displayName,
                        'class_name' => $subclass->class->class_name,
                    ]);
                }
            }
        }

        // Sort by class_name then subclass_name
        $subclasses = $subclasses->sortBy(function($item) {
            return $item->class_name . ' ' . $item->subclass_name;
        })->values();

        // Check if this is a request for subject details (AJAX)
        $getSubjectDetails = $request->get('getSubjectDetails', false);
        $getIncompleteResults = $request->get('getIncompleteResults', false);
        $studentIDFilter = $request->get('studentID', '');
        $typeFilterForDetails = $request->get('type', 'exam');

        // NEW: Fetch Incomplete Results logic (STABLE ULTRA-FAST BATCH SCAN)
        if ($getIncompleteResults && $examFilter) {
            $exam = Examination::find($examFilter);
            if (!$exam) return response()->json(['error' => 'Exam not found'], 404);

            // 1. Get all active students grouped by subclass (minimal columns)
            $studentsBySubclass = DB::table('students')
                ->where('schoolID', $schoolID)
                ->where('status', 'Active')
                ->select('studentID', 'subclassID', 'first_name', 'last_name', 'admission_number')
                ->get()
                ->groupBy('subclassID');

            // 2. Get ONLY IDs of existing marks for this exam
            $completedMarksBySubject = DB::table('results')
                ->where('examID', $examFilter)
                ->whereNotNull('marks')
                ->select('studentID', 'class_subjectID')
                ->get()
                ->groupBy('class_subjectID')
                ->map(function($rows) {
                    return $rows->pluck('studentID')->toArray();
                });

            // 3. Get active class subjects (Scoped correctly without non-existent schoolID column)
            $classIDFilter = $request->get('classID', '');
            $subclassIDFilter = $request->get('subclassID', '');

            $classSubjects = ClassSubject::where('status', 'Active')
                ->whereHas('subclass.class', function($q) use ($schoolID) {
                    $q->where('schoolID', $schoolID);
                })
                ->when($classIDFilter, function($query) use ($classIDFilter) {
                    return $query->whereHas('subclass', function($q) use ($classIDFilter) {
                        $q->where('classID', $classIDFilter);
                    });
                })
                ->when($subclassIDFilter, function($query) use ($subclassIDFilter) {
                    return $query->where('subclassID', $subclassIDFilter);
                })
                ->with(['subject', 'teacher', 'subclass.class'])
                ->get();

            $incompleteData = [];

            foreach ($classSubjects as $cs) {
                if (!$cs->subclassID) continue;

                $expectedStudents = $studentsBySubclass->get($cs->subclassID) ?: collect();
                if ($expectedStudents->isEmpty()) continue;

                $completedIds = $completedMarksBySubject->get($cs->class_subjectID) ?: [];
                
                // Identify missing students (not in results OR have no marks)
                $missing = $expectedStudents->filter(function($st) use ($completedIds) {
                    return !in_array($st->studentID, $completedIds);
                });

                if ($missing->isNotEmpty()) {
                    $teacherId = $cs->teacherID ?: 0;
                    if (!isset($incompleteData[$teacherId])) {
                        $incompleteData[$teacherId] = [
                            'teacher_name' => $cs->teacher ? ($cs->teacher->first_name . ' ' . $cs->teacher->last_name) : 'Unassigned',
                            'teacher_photo' => $cs->teacher ? $cs->teacher->image : null,
                            'teacher_phone' => $cs->teacher ? $cs->teacher->phone_number : null,
                            'teacher_gender' => $cs->teacher ? $cs->teacher->gender : 'Male',
                            'subjects' => []
                        ];
                    }

                    $incompleteData[$teacherId]['subjects'][] = [
                        'subject_name' => $cs->subject ? $cs->subject->subject_name : 'Unknown',
                        'class_name' => ($cs->subclass && $cs->subclass->class) ? ($cs->subclass->class->class_name . ' ' . $cs->subclass->subclass_name) : 'Unknown',
                        'missing_count' => $missing->count(),
                        'students' => $missing->values()->take(150)->toArray()
                    ];
                }
            }

            return response()->json([
                'exam_name' => $exam->exam_name,
                'incomplete_count' => count($incompleteData),
                'data' => array_values($incompleteData)
            ]);
        }

        // Support both exam and term report subject details
        if ($getSubjectDetails && $studentIDFilter && (($typeFilterForDetails === 'exam' && $examFilter) || ($typeFilterForDetails === 'report' && $termFilter && $yearFilter))) {
            // Return JSON response with subject details
            $student = Student::where('studentID', $studentIDFilter)
                ->where('schoolID', $schoolID)
                ->with(['subclass.class', 'oldSubclass.class'])
                ->first();

            if (!$student) {
                return response()->json([
                    'error' => 'Student not found'
                ], 404);
            }

            // Removed view permissions validation as requested
            
            // Get results for this student
            if ($typeFilterForDetails === 'report') {
                // For term report: get only exams defined in TermReportDefinition if available
                $reportDefinition = TermReportDefinition::where('schoolID', $schoolID)
                    ->where('year', $yearFilter)
                    ->where('term', $termFilter)
                    ->first();

                $examinationsQuery = Examination::where('schoolID', $schoolID)
                    ->where('year', $yearFilter)
                    ->where('term', $termFilter)
                    ->orderBy('start_date');

                if ($reportDefinition && !empty($reportDefinition->exam_ids)) {
                    $examinationsQuery->whereIn('examID', $reportDefinition->exam_ids);
                }

                $examinations = $examinationsQuery->get();
                $examIDs = $examinations->pluck('examID')->toArray();

                $results = Result::where('studentID', $studentIDFilter)
                    ->whereIn('examID', $examIDs)
                    ->with(['classSubject.subject', 'examination'])
                    ->get();
            } else {
                // For exam: get results for specific exam
                $resultsQuery = Result::where('studentID', $studentIDFilter)
                    ->where('examID', $examFilter);
                
                if ($weekFilter && $weekFilter !== 'all') {
                    $resultsQuery->where('test_week', $weekFilter);
                }

                $results = $resultsQuery->with(['classSubject.subject'])
                    ->get();
            }

            // Get class name and classID
            $className = '';
            $classID = null;
            if ($student->subclass && $student->subclass->class) {
                $className = $student->subclass->class->class_name ?? '';
                $classID = $student->subclass->class->classID ?? null;
            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                $className = $student->oldSubclass->class->class_name ?? '';
                $classID = $student->oldSubclass->class->classID ?? null;
            }

            // Process results differently for exam vs term report
            $examsData = []; // Initialize outside if block for use in response
            $caDefinition = null;
            if ($typeFilterForDetails === 'exam' && $examFilter) {
                $caDefinition = CaDefinition::where('examID', $examFilter)->where('schoolID', $schoolID)->first();
            }

            if ($typeFilterForDetails === 'report') {
                // For term report: group by subject and collect exam results
                $subjectData = [];

                // Check for report definition to filter exams
                $reportDefinition = TermReportDefinition::where('schoolID', $schoolID)
                    ->where('year', $yearFilter)
                    ->where('term', $termFilter)
                    ->first();

                // Get only exams for the term that are in the definition (if exists)
                $examinationsQuery = Examination::where('schoolID', $schoolID)
                    ->where('year', $yearFilter)
                    ->where('term', $termFilter);
                
                if ($reportDefinition && !empty($reportDefinition->exam_ids)) {
                    $examinationsQuery->whereIn('examID', $reportDefinition->exam_ids);
                }
                
                // For regular teachers without broad permission, only show approved exams
                if ($userType !== 'Admin' && !$hasBroadResultPermission) {
                    $examinationsQuery->where('approval_status', 'Approved');
                }
                
                $examinations = $examinationsQuery->orderBy('start_date')->get();

                foreach ($examinations as $exam) {
                    $examsData[$exam->examID] = [
                        'examID' => $exam->examID,
                        'exam_name' => $exam->exam_name,
                        'start_date' => $exam->start_date
                    ];
                }

                // Group results by subject
                foreach ($results as $result) {
                    $subjectName = $result->classSubject->subject->subject_name ?? 'N/A';
                    $examID = $result->examID;

                    if (!isset($subjectData[$subjectName])) {
                        $subjectData[$subjectName] = [
                            'subject_name' => $subjectName,
                            'exams' => [],
                            'marks_sum' => 0,
                            'marks_count' => 0
                        ];
                    }

                    if ($result->marks !== null && $result->marks !== '') {
                        $marks = (float)$result->marks;
                        $gradePoints = $this->calculateGradePoints($marks, $schoolType, $className, $classID);
                        $grade = $result->grade ?? $gradePoints['grade'];

                        // Get exam name from $examsData or from result relationship
                        $examName = 'N/A';
                        if (isset($examsData[$examID]) && isset($examsData[$examID]['exam_name'])) {
                            $examName = $examsData[$examID]['exam_name'];
                        } elseif ($result->examination && $result->examination->exam_name) {
                            $examName = $result->examination->exam_name;
                        }

                        $subjectData[$subjectName]['exams'][$examID] = [
                            'marks' => $marks,
                            'grade' => $grade,
                            'exam_name' => $examName
                        ];

                        $subjectData[$subjectName]['marks_sum'] += $marks;
                        $subjectData[$subjectName]['marks_count']++;
                    }
                }

                // Calculate average and overall grade for each subject
                $subjects = [];
                $totalMarks = 0;
                $subjectCount = 0;
                $subjectPoints = [];

                foreach ($subjectData as $subjectName => $data) {
                    $average = $data['marks_count'] > 0 ? $data['marks_sum'] / $data['marks_count'] : 0;

                    // Calculate grade based on average using grade_definitions
                    $overallGrade = null;
                    if ($classID && $average > 0) {
                        $gradeResult = $this->getGradeFromDefinition($average, $classID);
                        $overallGrade = $gradeResult['grade'];
                    } else {
                        // Fallback to old logic
                        if ($average >= 75) $overallGrade = 'A';
                        elseif ($average >= 65) $overallGrade = 'B';
                        elseif ($average >= 45) $overallGrade = 'C';
                        elseif ($average >= 30) $overallGrade = 'D';
                        else $overallGrade = 'F';
                    }

                    $subjects[] = [
                        'subject_name' => $subjectName,
                        'exams' => $data['exams'], // Associative array keyed by examID
                        'average' => $average,
                        'grade' => $overallGrade,
                        'is_term_report' => true
                    ];

                    $totalMarks += $data['marks_sum'];
                    $subjectCount += $data['marks_count'];

                    // Store points for division calculation
                    if ($schoolType === 'Secondary' && $average > 0) {
                        $avgGradePoints = $this->calculateGradePoints($average, $schoolType, $className, $classID);
                        $subjectPoints[] = [
                            'points' => $avgGradePoints['points'] ?? 5,
                            'marks' => $average
                        ];
                    }
                }

                $averageMarks = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
            } elseif ($caDefinition) {
                // For school exam with CA: Group by subject and calculate average from Tests + Exam
                $subjectData = [];
                $allInvolvedExams = array_merge([$examFilter], $caDefinition->test_ids);
                $involvedExams = Examination::whereIn('examID', $allInvolvedExams)->orderBy('end_date')->get()->keyBy('examID');
                
                $caResults = Result::where('studentID', $studentIDFilter)
                    ->whereIn('examID', $allInvolvedExams)
                    ->with(['classSubject.subject', 'examination'])
                    ->get();
                
                foreach ($caResults as $result) {
                    $subjectName = $result->classSubject->subject->subject_name ?? 'N/A';
                    $examID = $result->examID;
                    $examModel = $involvedExams[$examID] ?? null;
                    $examName = $examModel ? $examModel->exam_name : 'Unknown';
                    if ($examModel && $examModel->exam_category === 'test') {
                        $examName .= " (CA)";
                    }

                    if (!isset($subjectData[$subjectName])) {
                        $subjectData[$subjectName] = [
                            'subject_name' => $subjectName,
                            'exams' => [],
                            'marks_sum' => 0,
                            'marks_count' => 0
                        ];
                    }

                    if ($result->marks !== null && $result->marks !== '') {
                        $marks = (float)$result->marks;
                        $gradePoints = $this->calculateGradePoints($marks, $schoolType, $className, $classID);
                        
                        $subjectData[$subjectName]['exams'][] = [
                            'marks' => $marks,
                            'grade' => $result->grade ?? $gradePoints['grade'],
                            'exam_name' => $examName
                        ];
                        $subjectData[$subjectName]['marks_sum'] += $marks;
                        $subjectData[$subjectName]['marks_count']++;
                    }
                }

                $subjects = [];
                $totalMarks = 0;
                $subjectCount = 0;
                $subjectPoints = [];

                foreach ($subjectData as $subjectName => $data) {
                    $average = $data['marks_count'] > 0 ? $data['marks_sum'] / $data['marks_count'] : 0;
                    $gradeResult = $classID ? $this->getGradeFromDefinition($average, $classID) : null;
                    $overallGrade = $gradeResult ? $gradeResult['grade'] : null;
                    // Fallback: if grade_definitions table has no data, calculate manually
                    if (!$overallGrade) {
                        if ($average >= 75) $overallGrade = 'A';
                        elseif ($average >= 65) $overallGrade = 'B';
                        elseif ($average >= 45) $overallGrade = 'C';
                        elseif ($average >= 30) $overallGrade = 'D';
                        else $overallGrade = 'F';
                    }

                    $subjects[] = [
                        'subject_name' => $subjectName,
                        'exams' => $data['exams'], // Shows breakdown in View More
                        'marks' => $average,
                        'grade' => $overallGrade,
                        'is_ca_averaged' => true
                    ];

                    $totalMarks += $average;
                    $subjectCount++;

                    if ($schoolType === 'Secondary' && $average > 0) {
                        $avgGradePoints = $this->calculateGradePoints($average, $schoolType, $className, $classID);
                        $subjectPoints[] = [
                            'points' => $avgGradePoints['points'] ?? 5,
                            'marks' => $average
                        ];
                    }
                }
                $averageMarks = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
            } else {
                // For exam: original logic
                $totalMarks = 0;
                $subjectCount = 0;
                $subjects = [];
                $subjectPoints = [];

                foreach ($results as $result) {
                    if ($result->marks !== null && $result->marks !== '') {
                        $totalMarks += (float)$result->marks;
                        $subjectCount++;
                    }
                    $gradePoints = $this->calculateGradePoints($result->marks, $schoolType, $className, $classID);
                    $subjects[] = [
                        'subject_name' => $result->classSubject->subject->subject_name ?? 'N/A',
                        'marks' => $result->marks,
                        'grade' => $result->grade ?? $gradePoints['grade'],
                    ];

                    if ($schoolType === 'Secondary' && $result->marks !== null && $result->marks !== '') {
                        $subjectPoints[] = [
                            'points' => $gradePoints['points'] ?? 5,
                            'marks' => (float)$result->marks
                        ];
                    }
                }

                $averageMarks = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
            }

            // Calculate total points for secondary school (best 7 subjects for O-Level, best 3 for A-Level)
            $totalPoints = 0;
            if ($schoolType === 'Secondary' && !empty($subjectPoints)) {
                $classNameLower = strtolower(preg_replace('/[\s\-]+/', '_', $className));

                if (in_array($classNameLower, ['form_one', 'form_two', 'form_three', 'form_four'])) {
                    // O-Level: Best 7 subjects (ascending order of points = best)
                    usort($subjectPoints, function($a, $b) {
                        if ($a['points'] != $b['points']) {
                            return $a['points'] <=> $b['points']; // Ascending (lower points = better)
                        }
                        return $b['marks'] <=> $a['marks']; // If points equal, higher marks = better
                    });

                    // Take best 7 if available
                    $bestSubjects = array_slice($subjectPoints, 0, min(7, count($subjectPoints)));
                    
                    // Sum actual best subjects
                    foreach ($bestSubjects as $subject) {
                        $totalPoints += $subject['points'];
                    }

                    // PAD MISSING SUBJECTS: If less than 7 subjects, add 5 points for each missing subject
                    if (count($bestSubjects) < 7) {
                        $missingCount = 7 - count($bestSubjects);
                        $totalPoints += ($missingCount * 5);
                    }
                } elseif (in_array($classNameLower, ['form_five', 'form_six'])) {
                    // A-Level: Best 3 subjects (descending order of points = best)
                    usort($subjectPoints, function($a, $b) {
                        if ($a['points'] != $b['points']) {
                            return $b['points'] <=> $a['points']; // Descending (higher points = better)
                        }
                        return $b['marks'] <=> $a['marks'];
                    });
                    $bestSubjects = array_slice($subjectPoints, 0, min(3, count($subjectPoints)));

                    // Sum points of best subjects
                    foreach ($bestSubjects as $subject) {
                        $totalPoints += $subject['points'];
                    }
                } else {
                    $bestSubjects = $subjectPoints;
                    // Sum points of all subjects for other levels
                    foreach ($bestSubjects as $subject) {
                        $totalPoints += $subject['points'];
                    }
                }
            }

            // Calculate grade/division (SKIPPED if specific week is selected for weekly/monthly tests)
            $gradeDivision = ['grade' => null, 'division' => null];
            if (!$weekFilter || $weekFilter === 'all') {
                $gradeDivision = $this->calculateGradeDivision($totalMarks, $averageMarks, $subjectCount, $schoolType, $className, $totalPoints, $classID);
            }

            // Always calculate a literal grade (A,B,C,D,F) for the overall average to avoid nulls
            $overallGradeResult = $classID ? $this->getGradeFromDefinition($averageMarks, $classID) : null;
            $calculatedOverallGrade = $overallGradeResult ? $overallGradeResult['grade'] : null;
            
            if (!$calculatedOverallGrade) {
                if ($averageMarks >= 75) $calculatedOverallGrade = 'A';
                elseif ($averageMarks >= 65) $calculatedOverallGrade = 'B';
                elseif ($averageMarks >= 45) $calculatedOverallGrade = 'C';
                elseif ($averageMarks >= 30) $calculatedOverallGrade = 'D';
                else $calculatedOverallGrade = 'F';
            }

            // Get total students count and position for this student in their class
            // Position should be calculated per class (not overall)
            $totalStudentsCount = 0;
            $studentPosition = 0;

            // Get student's class ID
            $studentClassID = null;
            if ($student->subclass && $student->subclass->class) {
                $studentClassID = $student->subclass->class->classID;
            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                $studentClassID = $student->oldSubclass->class->classID;
            }

            if ($studentClassID) {
                // Get all students in the same class
                $classStudents = Student::where('schoolID', $schoolID)
                    ->where('status', 'Active')
                    ->where(function($query) use ($studentClassID) {
                        $query->whereHas('subclass', function($q) use ($studentClassID) {
                            $q->where('classID', $studentClassID);
                        })->orWhereHas('oldSubclass', function($q) use ($studentClassID) {
                            $q->where('classID', $studentClassID);
                        });
                    })
                    ->with(['subclass.class', 'oldSubclass.class'])
                    ->get();

                // Get results for all students in this class
                $classStudentsWithResults = [];
                foreach ($classStudents as $classStudent) {
                    if ($typeFilterForDetails === 'report') {
                        $examinationsQuery = Examination::where('schoolID', $schoolID)
                            ->where('year', $yearFilter)
                            ->where('term', $termFilter);
                        
                        if ($userType !== 'Admin' && !$hasBroadResultPermission) {
                            $examinationsQuery->where('approval_status', 'Approved');
                        }
                        
                        $examinations = $examinationsQuery->get();
                        $examIDs = $examinations->pluck('examID')->toArray();

                        $classStudentResultsQuery = Result::where('studentID', $classStudent->studentID)
                            ->whereIn('examID', $examIDs)
                            ->whereNotNull('marks');
                        
                        if ($userType !== 'Admin' && !$hasBroadResultPermission) {
                            $classStudentResultsQuery->where('status', 'allowed');
                        }
                        
                        $classStudentResults = $classStudentResultsQuery->with(['classSubject.subject'])
                            ->get();
                    } else {
                        // For exam: get results for specific exam
                        $classStudentResultsQuery = Result::where('studentID', $classStudent->studentID)
                            ->where('examID', $examFilter)
                            ->whereNotNull('marks');
                        
                        if ($userType !== 'Admin' && !$hasBroadResultPermission) {
                            $classStudentResultsQuery->where('status', 'allowed');
                        }
                        
                        $classStudentResults = $classStudentResultsQuery->with(['classSubject.subject'])
                            ->get();
                    }

                    if ($classStudentResults->isNotEmpty()) {
                        if ($typeFilterForDetails === 'report') {
                            // For term report: calculate average per exam, then overall average
                            $examAverages = [];
                            $examResultsByExam = $classStudentResults->groupBy('examID');

                            foreach ($examResultsByExam as $examID => $examResults) {
                                $examTotalMarks = 0;
                                $examSubjectCount = 0;

                                foreach ($examResults as $examResult) {
                                    if ($examResult->marks !== null && $examResult->marks !== '') {
                                        $examTotalMarks += (float)$examResult->marks;
                                        $examSubjectCount++;
                                    }
                                }

                                if ($examSubjectCount > 0) {
                                    $examAverages[] = $examTotalMarks / $examSubjectCount;
                                }
                            }

                            // Overall average is average of exam averages
                            $overallAverage = count($examAverages) > 0 ? array_sum($examAverages) / count($examAverages) : 0;

                            $classStudentsWithResults[] = [
                                'student' => $classStudent,
                                'average_marks' => $overallAverage,
                                'total_marks' => 0, // Not used for term report sorting
                                'total_points' => 0 // Not used for term report sorting
                            ];
                        } else {
                            // For exam: calculate total marks and points
                            $classStudentTotalMarks = 0;
                            $classStudentSubjectCount = 0;
                            $classStudentSubjectPoints = [];

                            foreach ($classStudentResults as $classResult) {
                                if ($classResult->marks !== null && $classResult->marks !== '') {
                                    $classStudentTotalMarks += (float)$classResult->marks;
                                    $classStudentSubjectCount++;
                                }

                                if ($schoolType === 'Secondary' && $classResult->marks !== null && $classResult->marks !== '') {
                                    // Get classID for this student
                                    $studentClassID = null;
                                    if ($classStudent->subclass && $classStudent->subclass->class) {
                                        $studentClassID = $classStudent->subclass->class->classID ?? null;
                                    } elseif ($classStudent->oldSubclass && $classStudent->oldSubclass->class) {
                                        $studentClassID = $classStudent->oldSubclass->class->classID ?? null;
                                    }
                                    $classGradePoints = $this->calculateGradePoints($classResult->marks, $schoolType, $className, $studentClassID);
                                    $classStudentSubjectPoints[] = $classGradePoints['points'] ?? 5;
                                }
                            }

                            // Calculate total points for secondary
                            $classStudentTotalPoints = 0;
                            if ($schoolType === 'Secondary' && !empty($classStudentSubjectPoints)) {
                                $classNameLower = strtolower(preg_replace('/[\s\-]+/', '_', $className));
                                if (in_array($classNameLower, ['form_one', 'form_two', 'form_three', 'form_four'])) {
                                    // O-Level: Best 7
                                    sort($classStudentSubjectPoints);
                                    $bestSubjects = array_slice($classStudentSubjectPoints, 0, min(7, count($classStudentSubjectPoints)));
                                    $classStudentTotalPoints = array_sum($bestSubjects);
                                } elseif (in_array($classNameLower, ['form_five', 'form_six'])) {
                                    // A-Level: Best 3
                                    rsort($classStudentSubjectPoints);
                                    $bestSubjects = array_slice($classStudentSubjectPoints, 0, min(3, count($classStudentSubjectPoints)));
                                    $classStudentTotalPoints = array_sum($bestSubjects);
                                }
                            }

                            $classStudentsWithResults[] = [
                                'student' => $classStudent,
                                'total_marks' => $classStudentTotalMarks,
                                'total_points' => $classStudentTotalPoints,
                                'best_seven_total_marks' => 0
                            ];
                        }
                    }
                }

                $totalStudentsCount = count($classStudentsWithResults);

                // Sort students by performance (same logic as in frontend)
                if ($typeFilterForDetails === 'report') {
                    // For term report: sort by average (descending for primary, by grade then average for secondary)
                    if ($schoolType === 'Secondary') {
                        usort($classStudentsWithResults, function($a, $b) {
                            // Sort by grade order (A is best), then by average
                            $gradeOrder = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6];
                            // Calculate grade for average using grade_definitions
                            $avgA = $a['average_marks'] ?? 0;
                            $avgB = $b['average_marks'] ?? 0;

                            // Get classID for students
                            $classIDA = null;
                            $classIDB = null;
                            if (isset($a['student'])) {
                                if ($a['student']->subclass && $a['student']->subclass->class) {
                                    $classIDA = $a['student']->subclass->class->classID ?? null;
                                } elseif ($a['student']->oldSubclass && $a['student']->oldSubclass->class) {
                                    $classIDA = $a['student']->oldSubclass->class->classID ?? null;
                                }
                            }
                            if (isset($b['student'])) {
                                if ($b['student']->subclass && $b['student']->subclass->class) {
                                    $classIDB = $b['student']->subclass->class->classID ?? null;
                                } elseif ($b['student']->oldSubclass && $b['student']->oldSubclass->class) {
                                    $classIDB = $b['student']->oldSubclass->class->classID ?? null;
                                }
                            }

                            $gradeResultA = $classIDA ? $this->getGradeFromDefinition($avgA, $classIDA) : null;
                            $gradeResultB = $classIDB ? $this->getGradeFromDefinition($avgB, $classIDB) : null;
                            $gradeA = $gradeResultA['grade'] ?? (($avgA >= 75) ? 'A' : (($avgA >= 65) ? 'B' : (($avgA >= 45) ? 'C' : (($avgA >= 30) ? 'D' : 'F'))));
                            $gradeB = $gradeResultB['grade'] ?? (($avgB >= 75) ? 'A' : (($avgB >= 65) ? 'B' : (($avgB >= 45) ? 'C' : (($avgB >= 30) ? 'D' : 'F'))));
                            $orderA = $gradeOrder[$gradeA] ?? 999;
                            $orderB = $gradeOrder[$gradeB] ?? 999;

                            if ($orderA != $orderB) {
                                return $orderA <=> $orderB; // Lower number = better grade
                            }
                            return $b['average_marks'] <=> $a['average_marks']; // Higher average = better
                        });
                    } else {
                        // Primary: Sort by average descending
                        usort($classStudentsWithResults, function($a, $b) {
                            return $b['average_marks'] <=> $a['average_marks'];
                        });
                    }
                } else {
                    // For exam: original sorting logic
                    if ($schoolType === 'Secondary') {
                        usort($classStudentsWithResults, function($a, $b) {
                            // First sort by total points (ascending - lower is better)
                            if ($a['total_points'] != $b['total_points']) {
                                return $a['total_points'] <=> $b['total_points'];
                            }
                            // If points are equal, sort by total marks descending (higher marks = better)
                            return $b['total_marks'] <=> $a['total_marks'];
                        });
                    } else {
                        // Primary: Sort by total marks descending
                        usort($classStudentsWithResults, function($a, $b) {
                            return $b['total_marks'] <=> $a['total_marks'];
                        });
                    }
                }

                // Find position of current student
                foreach ($classStudentsWithResults as $index => $classStudentData) {
                    if ($classStudentData['student']->studentID == $studentIDFilter) {
                        $studentPosition = $index + 1;
                        break;
                    }
                }
                $totalStudentsCount = count($classStudentsWithResults);
            }

            // Get student photo path or placeholder
            $studentPhoto = null;
            if ($student->photo) {
                $studentPhoto = asset('userImages/' . $student->photo);
            } else {
                // Use placeholder based on gender
                $placeholderPath = $student->gender === 'Female'
                    ? asset('placeholder/female.png')
                    : asset('placeholder/male.png');
                $studentPhoto = $placeholderPath;
            }

            $responseData = [
                'subjects' => $subjects,
                'student' => [
                    'studentID' => $student->studentID,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'admission_number' => $student->admission_number,
                    'gender' => $student->gender,
                    'photo' => $studentPhoto
                ],
                'totalMarks' => $totalMarks,
                'averageMarks' => $averageMarks,
                'subjectCount' => $subjectCount,
                'grade' => $calculatedOverallGrade,
                'division' => $gradeDivision['division'] ?? null,
                'totalStudentsCount' => $totalStudentsCount,
                'position' => $studentPosition
            ];

            // For term report, include exams data
            if ($typeFilterForDetails === 'report') {
                // Use the exams we already fetched earlier
                $responseData['exams'] = array_values($examsData);
                $responseData['term'] = $termFilter;
                $responseData['year'] = $yearFilter;
            }

            return response()->json($responseData);
        }

        // Get available exams for the selected term and year
        $availableExams = collect();
        if ($yearFilter) {
            $examQuery = Examination::where('schoolID', $schoolID)
                ->where('year', $yearFilter)
                ->orderBy('start_date');
            if ($termFilter) {
                $examQuery->where('term', $termFilter);
            }
            $availableExams = $examQuery->get();
        }

        // Get Available Weeks if it's a test
        $availableWeeks = [];
        $examStatusMessage = null;
        if ($examFilter) {
            $selExam = Examination::find($examFilter);
            if ($selExam && $selExam->exam_category === 'test') {
                $weeksInfo = ExamPaper::where('examID', $examFilter)
                    ->whereNotNull('test_week')
                    ->select('test_week', 'test_week_range',
                        \DB::raw('MIN(test_date) as start_date'), 
                        \DB::raw('MAX(test_date) as end_date'));
                
                if ($subclassFilter) {
                    $weeksInfo->whereHas('classSubject', function($q) use ($subclassFilter) {
                        $q->where('subclassID', $subclassFilter);
                    });
                } elseif ($classFilter) {
                    $weeksInfo->whereHas('classSubject', function($q) use ($classFilter) {
                        $q->where('classID', $classFilter);
                    });
                }

                $weeksData = $weeksInfo->groupBy('test_week', 'test_week_range')
                    ->orderByRaw("CAST(REPLACE(test_week, 'Week ', '') AS UNSIGNED) ASC")
                    ->get();

                $availableWeeks = [];
                $today = date('Y-m-d');
                foreach ($weeksData as $winfo) {
                    $isCurrent = ($today >= $winfo->start_date && $today <= $winfo->end_date);
                    
                    $rangeDisplay = $winfo->test_week_range;
                    if (empty($rangeDisplay) && $winfo->start_date && $winfo->end_date) {
                         $rangeDisplay = date('d M', strtotime($winfo->start_date)) . " - " . date('d M', strtotime($winfo->end_date));
                    }

                    $label = $winfo->test_week;
                    if (!empty($rangeDisplay)) {
                        $label .= " (" . $rangeDisplay . ")";
                    }

                    if ($isCurrent) {
                        $label = "CURRENT: " . $label;
                    } else if ($winfo->end_date < $today) {
                        $label = "OLD: " . $label;
                    }
                    
                    $availableWeeks[] = [
                        'week' => $winfo->test_week,
                        'display' => $label
                    ];
                }

                // Determine Exam Status Message
                // Check if there are any papers with test_date >= today
                $ongoing = ExamPaper::where('examID', $examFilter)
                    ->where('test_date', '>=', date('Y-m-d'))
                    ->exists();
                
                if ($ongoing) {
                    $examStatusMessage = "This exam is ongoing";
                    
                    try {
                        if ($selExam->end_date && !in_array($selExam->end_date, ['every_week', 'every_month'])) {
                            $today = \Carbon\Carbon::now()->startOfDay();
                            $endDate = \Carbon\Carbon::parse($selExam->end_date)->startOfDay();
                            
                            if ($endDate->gt($today)) {
                                $weeks = $today->diffInWeeks($endDate);
                                if ($weeks > 0) {
                                    $examStatusMessage .= " (" . $weeks . " " . ($weeks == 1 ? "week" : "weeks") . " remaining)";
                                } else {
                                    $days = $today->diffInDays($endDate);
                                    $examStatusMessage .= " (" . $days . " " . ($days == 1 ? "day" : "days") . " remaining)";
                                }
                            } elseif ($endDate->eq($today)) {
                                $examStatusMessage .= " (Ends today)";
                            }
                        }
                    } catch (\Exception $e) {
                        // Keep simple message if date parsing fails
                    }
                } else if (!empty($availableWeeks)) {
                    $examStatusMessage = "This exam has ended. You can view the results for all weeks.";
                }
            }
        }

        // Check result approvals if exam filter is selected
        if ($examFilter && $typeFilter === 'exam') {
            $exam = Examination::where('examID', $examFilter)
                ->where('schoolID', $schoolID)
                ->first();

            if ($exam) {
            }
        }

        // Build query for students based on status filter
        $studentQuery = Student::where('schoolID', $schoolID);

        if ($statusFilter === 'active') {
            $studentQuery->where('status', 'Active');
        } elseif ($statusFilter === 'history') {
            // Students who have old_subclassID (were shifted) - show their history
            $studentQuery->whereNotNull('old_subclassID');
        }
        // 'all' includes all students

        // SECURITY: For regular teachers without broad permissions, ensure they can only access their assigned subclass
        if ($userType === 'Teacher' && !empty($teacherID) && !$hasBroadResultPermission) {
            // If teacher view is active (from classManagement), MUST have subclassFilter set
            if ($isTeacherView) {
                if (empty($subclassFilter)) {
                    // If isTeacherView is true but no subclassFilter, redirect back
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'Please select a class to view results')
                        ->with('error_type', 'no_subclass_selected');
                }

                // Verify teacher has access to this subclass
                $subclass = Subclass::find($subclassFilter);
                if (!$subclass || $subclass->teacherID != $teacherID) {
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'You do not have access to view results for this class')
                        ->with('error_type', 'unauthorized_access');
                }

                // ALWAYS filter by the specific subclass - no exceptions
                $studentQuery->where('subclassID', $subclassFilter);
            } elseif ($subclassFilter) {
                // If subclassFilter is set but not teacher view, verify access
                $subclass = Subclass::find($subclassFilter);
                if (!$subclass || $subclass->teacherID != $teacherID) {
                    return redirect()->route('AdmitedClasses')
                        ->with('error', 'You do not have access to view results for this class')
                        ->with('error_type', 'unauthorized_access');
                }
                $studentQuery->where('subclassID', $subclassFilter);
            } else {
                // If no subclass selected and not teacher view, show nothing
                $studentQuery->whereRaw('1 = 0'); // Return no students
            }
        } elseif ($subclassFilter) {
            // Admin or non-teacher: filter by subclass if selected
            $studentQuery->where('subclassID', $subclassFilter);
        } elseif ($classFilter) {
            // Filter by main class if subclass not selected
            // Check both current subclass and old subclass (for history)
            $studentQuery->where(function($query) use ($classFilter) {
                $query->whereHas('subclass', function($q) use ($classFilter) {
                    $q->where('classID', $classFilter);
                })->orWhereHas('oldSubclass', function($q) use ($classFilter) {
                    $q->where('classID', $classFilter);
                });
            });
        }


        $students = $studentQuery->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
            ->orderBy('first_name')
            ->get();

        // Get results data
        $resultsData = [];
        $debugInfo = [];

        // Only process if we have term and year (required for results)
        if ($termFilter && $yearFilter) {
            if ($typeFilter === 'exam') {
                // Get exam results
                $resultsData = $this->getExamResults($students, $termFilter, $yearFilter, $schoolType, $examFilter, $weekFilter, $subjectFilter);
            } elseif ($typeFilter === 'report') {
                // Get term report
                $resultsData = $this->getTermReport($students, $termFilter, $yearFilter, $schoolType, $subjectFilter);
            }
                // Debug: Check why results might be empty
                if (empty($resultsData) && $students->count() > 0) {
                    // Check if exam exists and is approved
                    if ($examFilter) {
                        $exam = Examination::where('examID', $examFilter)
                            ->where('schoolID', $schoolID)
                            ->first();
                        if (!$exam) {
                            $debugInfo[] = "Exam with ID {$examFilter} not found.";
                        } elseif ($exam->approval_status !== 'Approved') {
                            $debugInfo[] = "Exam '{$exam->exam_name}' is not approved yet.";
                        } else {
                            // Check if exam has ended
                            $isWeeklyTest = ($exam->exam_name === 'Weekly Test' || $exam->start_date === 'every_week' || $exam->end_date === 'every_week');
                            $isMonthlyTest = ($exam->exam_name === 'Monthly Test' || $exam->start_date === 'every_month' || $exam->end_date === 'every_month');
                            if (!$isWeeklyTest && !$isMonthlyTest) {
                                try {
                                    $today = now()->startOfDay();
                                    $endDate = \Carbon\Carbon::parse($exam->end_date)->startOfDay();
                                    if ($endDate >= $today) {
                                        $debugInfo[] = "Exam '{$exam->exam_name}' has not ended yet (ends on {$exam->end_date}).";
                                    }
                                } catch (\Exception $e) {
                                    // Date parsing failed
                                }
                            }
                        }
                    }
                    
                    // Check if students have results but with wrong status or no marks
                    $studentIDs = $students->pluck('studentID')->toArray();
                    $totalResults = \App\Models\Result::whereIn('studentID', $studentIDs);
                    if ($examFilter) {
                        $totalResults->where('examID', $examFilter);
                    } else {
                        $examsQuery = Examination::where('schoolID', $schoolID)
                            ->where('year', $yearFilter)
                            ->where('term', $termFilter);
                        $examIDs = $examsQuery->pluck('examID')->toArray();
                        $totalResults->whereIn('examID', $examIDs);
                    }
                    
                    $totalCount = $totalResults->count();
                    $allowedCount = (clone $totalResults)->where('status', 'allowed')->count();
                    $withMarksCount = (clone $totalResults)->whereNotNull('marks')->count();
                    $allowedWithMarksCount = (clone $totalResults)->where('status', 'allowed')->whereNotNull('marks')->count();
                    
                    if ($totalCount > 0) {
                        $notAllowedCount = $totalCount - $allowedCount;
                        if ($allowedCount < $totalCount) {
                            if ($notAllowedCount > 0) {
                                $debugInfo[] = "Found {$totalCount} results total. {$notAllowedCount} results have status 'not_allowed' and need to be allowed first. Only results with status 'allowed' can be viewed.";
                            } else {
                                $debugInfo[] = "Found {$totalCount} results, but only {$allowedCount} have status 'allowed'. Results need to be allowed first.";
                            }
                        }
                        if ($withMarksCount < $totalCount) {
                            $debugInfo[] = "Found {$totalCount} results, but only {$withMarksCount} have marks entered.";
                        }
                        if ($allowedWithMarksCount < $totalCount) {
                            $debugInfo[] = "Found {$totalCount} results, but only {$allowedWithMarksCount} have both status 'allowed' and marks entered. To view results, you need to change the status from 'not_allowed' to 'allowed'.";
                        }
                    } else {
                        $debugInfo[] = "No results found in database for selected students and exam.";
                    }
                }
            }

        return view('Admin.result_management', [
            'school' => $school,
            'schoolType' => $schoolType,
            'students' => $students,
            'resultsData' => $resultsData,
            'classes' => $classes,
            'subclasses' => $subclasses,
            'availableYears' => $availableYears,
            'availableWeeks' => $availableWeeks,
            'examStatusMessage' => $examStatusMessage,
            'filters' => [
                'term' => $termFilter,
                'year' => $yearFilter,
                'type' => $typeFilter,
                'status' => $statusFilter,
                'class' => $classFilter,
                'subclass' => $subclassFilter,
                'examID' => $examFilter,
                'week' => $weekFilter,
                'subjectID' => $subjectFilter,
            ],
            'availableExams' => $availableExams,
            'schoolSubjects' => SchoolSubject::where('schoolID', $schoolID)
                ->where('status', 'Active')
                ->orderBy('subject_name')
                ->get(),
            'isTeacherView' => $isTeacherView, // Pass teacher view flag
            'isCoordinatorResultsView' => $isCoordinatorResultsView ?? false,
            'debugInfo' => $debugInfo ?? [],
            'user_type' => $userType, // Pass user type for navigation
            'error' => null, // No error if we reach here
        ]);
    }

    /**
     * Send SMS reminder to a single teacher
     */
    public function sendTeacherReminder(Request $request)
    {
        try {
            $phoneNumber = $request->input('phone');
            $message = $request->input('message');
            $teacherName = $request->input('name');

            if (!$phoneNumber || !$message) {
                return response()->json(['success' => false, 'error' => 'Phone number and message are required.'], 400);
            }

            $smsService = new SmsService();
            $result = $smsService->sendSms($phoneNumber, $message);

            if ($result['success']) {
                return response()->json(['success' => true, 'message' => "Reminder sent to {$teacherName}"]);
            } else {
                return response()->json(['success' => false, 'error' => 'Gateway error: ' . $result['message']], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'System error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Broadcast SMS reminder to all teachers with incomplete results for an exam
     */
    public function sendBroadcastReminder(Request $request)
    {
        try {
            $examID = $request->input('examID');
            $customMessageTemplate = $request->input('message'); // e.g. "Habari {name}, ..." or generic
            $schoolID = Session::get('schoolID');

            if (!$schoolID || !$examID || !$customMessageTemplate) {
                return response()->json(['success' => false, 'error' => 'Missing required broadcast parameters.'], 400);
            }

            // Reuse the scanning logic to identify teachers with incomplete results
            // 1. Get all students grouped by subclass
            $studentsBySubclass = DB::table('students')
                ->where('schoolID', $schoolID)
                ->where('status', 'Active')
                ->pluck('subclassID', 'studentID')
                ->groupBy(function($item) { return $item; });

            // 2. Get students by subclass properly
            $subclassStudentsCount = DB::table('students')
                ->where('schoolID', $schoolID)
                ->where('status', 'Active')
                ->select('subclassID', DB::raw('count(*) as total'))
                ->groupBy('subclassID')
                ->pluck('total', 'subclassID');

            // 3. Get completed marks count per subject
            $completedCounts = DB::table('results')
                ->where('examID', $examID)
                ->whereNotNull('marks')
                ->select('class_subjectID', DB::raw('count(*) as count'))
                ->groupBy('class_subjectID')
                ->pluck('count', 'class_subjectID');

            // 4. Find all active class subjects for this school
            $classSubjects = ClassSubject::where('status', 'Active')
                ->whereHas('subclass.class', function($q) use ($schoolID) {
                    $q->where('schoolID', $schoolID);
                })
                ->with(['subject', 'teacher'])
                ->get();

            $teachersToNotify = [];
            foreach ($classSubjects as $cs) {
                if (!$cs->teacher || !$cs->teacher->phone_number) continue;
                
                $expected = $subclassStudentsCount->get($cs->subclassID) ?: 0;
                $actual = $completedCounts->get($cs->class_subjectID) ?: 0;

                if ($actual < $expected) {
                    $teachersToNotify[$cs->teacherID] = [
                        'phone' => $cs->teacher->phone_number,
                        'name' => $cs->teacher->first_name . ' ' . $cs->teacher->last_name
                    ];
                }
            }

            if (empty($teachersToNotify)) {
                return response()->json(['success' => true, 'message' => 'No teachers with pending results found.']);
            }

            $smsService = new SmsService();
            $successCount = 0;
            $failCount = 0;

            foreach ($teachersToNotify as $teacher) {
                // Personalize if template contains placeholder
                $msg = str_replace('{name}', $teacher['name'], $customMessageTemplate);
                $res = $smsService->sendSms($teacher['phone'], $msg);
                
                if ($res['success']) $successCount++;
                else $failCount++;
            }

            return response()->json([
                'success' => true, 
                'message' => "Broadcast complete. Sent: {$successCount}, Failed: {$failCount}"
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Broadcast failed: ' . $e->getMessage()], 500);
        }
    }

    public function subjectAnalysis(Request $request)
    {
        $schoolID = Session::get('schoolID');
        if (! $schoolID) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        $year = $request->get('year', '');
        $term = $request->get('term', '');
        $examID = $request->get('examID', '');
        $classID = $request->get('classID', '');
        $subclassID = $request->get('subclassID', '');
        $subjectID = $request->get('subjectID', '');
        $allSubclasses = $request->get('all_subclasses', '') === '1';

        $availableYears = Examination::where('schoolID', $schoolID)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $classes = ClassModel::where('schoolID', $schoolID)
            ->where('status', 'Active')
            ->orderBy('class_name')
            ->get();

        $subjects = SchoolSubject::where('schoolID', $schoolID)
            ->where('status', 'Active')
            ->orderBy('subject_name')
            ->get();

        $examsQuery = Examination::where('schoolID', $schoolID);
        if ($year) {
            $examsQuery->where('year', $year);
        }
        if ($term) {
            $examsQuery->where('term', $term);
        }
        $exams = $examsQuery->orderBy('start_date', 'desc')->get();

        $analysisData = [];
        $selectedExam = null;
        if ($examID) {
            $selectedExam = Examination::where('examID', $examID)
                ->where('schoolID', $schoolID)
                ->first();

            $classSubjectsQuery = ClassSubject::with(['subject', 'class', 'subclass', 'teacher'])
                ->where('status', 'Active');

            if ($classID) {
                $classSubjectsQuery->where('classID', $classID);
            }

            if ($subclassID && ! $allSubclasses) {
                $classSubjectsQuery->where('subclassID', $subclassID);
            }

            if ($subjectID) {
                $classSubjectsQuery->where('subjectID', $subjectID);
            }

            $classSubjects = $classSubjectsQuery->get();

            foreach ($classSubjects as $classSubject) {
                $classSubjectID = $classSubject->class_subjectID;
                $subjectName = $classSubject->subject->subject_name ?? 'N/A';
                $className = $classSubject->class->class_name ?? 'N/A';
                $subclassName = $classSubject->subclass->subclass_name ?? '';
                $classDisplay = trim($className.' '.$subclassName);

                $results = Result::with(['student'])
                    ->where('examID', $examID)
                    ->where('class_subjectID', $classSubjectID)
                    ->get();

                $studentsQuery = Student::where('status', 'Active');
                if ($classSubject->subclassID) {
                    $studentsQuery->where('subclassID', $classSubject->subclassID);
                } elseif ($classSubject->classID) {
                    $subclassIds = Subclass::where('classID', $classSubject->classID)
                        ->pluck('subclassID')
                        ->toArray();
                    $studentsQuery->whereIn('subclassID', $subclassIds);
                }
                $students = $studentsQuery->get();

                $resultsByStudent = $results->keyBy('studentID');
                $resultRows = $students->map(function ($student) use ($resultsByStudent) {
                    $result = $resultsByStudent->get($student->studentID);
                    return [
                        'student' => $student,
                        'marks' => $result ? $result->marks : null,
                        'grade' => $result ? $result->grade : null,
                        'remark' => $result ? $result->remark : null,
                        'result_id' => $result ? $result->resultID : null,
                    ];
                });

                $answeredRows = $resultRows->filter(function ($row) {
                    return $row['marks'] !== null;
                });
                $passCount = $answeredRows->filter(function ($row) {
                    $grade = strtoupper($row['grade'] ?? '');
                    return in_array($grade, ['A', 'B', 'C', 'D']);
                })->count();
                $failCount = $answeredRows->filter(function ($row) {
                    $grade = strtoupper($row['grade'] ?? '');
                    return $grade === 'F';
                })->count();
                $overallRemark = $answeredRows->count() > 0
                    ? ($passCount >= $failCount ? 'Pass' : 'Fail')
                    : 'Incomplete';
                $overallClass = $overallRemark === 'Pass' ? 'success' : ($overallRemark === 'Fail' ? 'danger' : 'secondary');

                $examPaper = ExamPaper::where('examID', $examID)
                    ->where('class_subjectID', $classSubjectID)
                    ->where('status', 'approved')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $questions = collect();
                $optionalRanges = collect();
                $questionStats = [];
                $studentQuestionMarks = [];
                $bestQuestion = null;
                $worstQuestion = null;

                if ($examPaper) {
                    $questions = ExamPaperQuestion::where('exam_paperID', $examPaper->exam_paperID)
                        ->orderBy('question_number')
                        ->get();
                    $optionalRanges = ExamPaperOptionalRange::where('exam_paperID', $examPaper->exam_paperID)
                        ->orderBy('range_number')
                        ->get();

                    $marks = ExamPaperQuestionMark::where('examID', $examID)
                        ->where('class_subjectID', $classSubjectID)
                        ->get();

                    foreach ($marks as $mark) {
                        $studentQuestionMarks[$mark->studentID][$mark->exam_paper_questionID] = $mark->marks;
                    }

                    foreach ($questions as $question) {
                        $questionMarks = $marks->where('exam_paper_questionID', $question->exam_paper_questionID)
                            ->pluck('marks')
                            ->filter(function ($value) {
                                return $value !== null;
                            });
                        $selectedCount = $questionMarks->count();
                        $avg = $selectedCount > 0 ? $questionMarks->avg() : null;
                        $percent = ($selectedCount > 0 && $question->marks > 0)
                            ? round(($avg / $question->marks) * 100, 1)
                            : null;

                        $questionStats[] = [
                            'question' => $question,
                            'average' => $avg !== null ? round($avg, 2) : null,
                            'percent' => $percent,
                            'selected_count' => $selectedCount,
                        ];
                    }

                    $scoredQuestions = collect($questionStats)->filter(function ($stat) {
                        return $stat['percent'] !== null;
                    });
                    if ($scoredQuestions->isNotEmpty()) {
                        $bestQuestion = $scoredQuestions->sortByDesc('percent')->first();
                        $worstQuestion = $scoredQuestions->sortBy('percent')->first();
                    }
                }

                $totalAnswered = $answeredRows->count();
                $passRate = $totalAnswered > 0 ? round(($passCount / $totalAnswered) * 100, 1) : 0;
                $failRate = $totalAnswered > 0 ? round(($failCount / $totalAnswered) * 100, 1) : 0;

                $analysisData[] = [
                    'class_subjectID' => $classSubjectID,
                    'subject_name' => $subjectName,
                    'class_display' => $classDisplay,
                    'teacher' => $classSubject->teacher,
                    'results' => $results,
                    'result_rows' => $resultRows,
                    'questions' => $questions,
                    'optional_ranges' => $optionalRanges,
                    'question_stats' => $questionStats,
                    'best_question' => $bestQuestion,
                    'worst_question' => $worstQuestion,
                    'student_question_marks' => $studentQuestionMarks,
                    'overall_stats' => [
                        'answered' => $totalAnswered,
                        'pass' => $passCount,
                        'fail' => $failCount,
                        'pass_rate' => $passRate,
                        'fail_rate' => $failRate,
                        'remark' => $overallRemark,
                        'remark_class' => $overallClass,
                    ],
                ];
            }
        }

        $groupedAnalysis = collect($analysisData)->groupBy('class_display');

        $user_type = Session::get('user_type');
        return view('Admin.subject_analysis', compact(
            'availableYears',
            'classes',
            'subjects',
            'exams',
            'analysisData',
            'groupedAnalysis',
            'year',
            'term',
            'examID',
            'selectedExam',
            'classID',
            'subclassID',
            'subjectID',
            'allSubclasses',
            'user_type'
        ));
    }

    public function getClassSubjectsForAnalysis(Request $request)
    {
        try {
            $schoolID = Session::get('schoolID');
            $classID = $request->input('classID');
            $subclassID = $request->input('subclassID');

            if (!$schoolID || !$classID) {
                return response()->json(['success' => false, 'error' => 'Class ID and school ID required']);
            }

            $query = DB::table('class_subjects')
                ->join('school_subjects', 'class_subjects.subjectID', '=', 'school_subjects.subjectID')
                ->join('classes', 'class_subjects.classID', '=', 'classes.classID')
                ->where('classes.schoolID', $schoolID)
                ->where('class_subjects.status', 'Active')
                ->where('school_subjects.status', 'Active')
                ->where('class_subjects.classID', $classID);

            if ($subclassID) {
                $query->where(function ($subQuery) use ($subclassID) {
                    $subQuery->where('class_subjects.subclassID', $subclassID)
                        ->orWhereNull('class_subjects.subclassID');
                });
            }

            $subjects = $query->select(
                'school_subjects.subjectID',
                'school_subjects.subject_name'
            )->distinct()->orderBy('school_subjects.subject_name')->get();

            return response()->json(['success' => true, 'subjects' => $subjects]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function sendSubjectAnalysisComment(Request $request)
    {
        $schoolID = Session::get('schoolID');
        if (! $schoolID) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'teacherID' => 'required|integer',
            'message' => 'required|string|max:500',
            'class_subjectID' => 'required|integer',
            'examID' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teacher = Teacher::where('id', $request->teacherID)
            ->where('schoolID', $schoolID)
            ->first();

        if (! $teacher || empty($teacher->phone_number)) {
            return response()->json(['error' => 'Teacher phone number not available'], 422);
        }

        $classSubject = ClassSubject::with(['subject', 'class', 'subclass'])
            ->where('class_subjectID', $request->class_subjectID)
            ->first();

        if (! $classSubject) {
            return response()->json(['error' => 'Class subject not found'], 404);
        }

        $subjectName = $classSubject->subject->subject_name ?? 'Subject';
        $className = $classSubject->class->class_name ?? '';
        $subclassName = $classSubject->subclass->subclass_name ?? '';
        $classDisplay = trim($className.' '.$subclassName);

        $examName = 'Exam';
        if ($request->examID) {
            $exam = Examination::where('examID', $request->examID)
                ->where('schoolID', $schoolID)
                ->first();
            if ($exam) {
                $examName = $exam->exam_name;
            }
        }

        $smsMessage = "Subject analysis comment: {$subjectName} ({$classDisplay}) - {$examName}. {$request->message}";
        try {
            $smsService = new SmsService();
            $smsService->sendSms($teacher->phone_number, $smsMessage);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send message'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function sendResultSms(Request $request)
    {
        \Illuminate\Support\Facades\Log::info("sendResultSms method reached", $request->all());
        $schoolID = Session::get('schoolID');
        if (!$schoolID) {
            \Illuminate\Support\Facades\Log::error("sendResultSms: Access denied - No schoolID in session");
            return response()->json(['success' => false, 'error' => 'Access denied'], 403);
        }

        $studentID = $request->input('studentID');
        $subject = $request->input('subject');
        $marks = $request->input('marks');
        $grade = $request->input('grade');
        $week = $request->input('week');
        $examID = $request->input('examID');

        $student = Student::with(['parent', 'subclass.class', 'oldSubclass.class'])->find($studentID);
        if (!$student) {
            \Illuminate\Support\Facades\Log::warning("sendResultSms: Student not found for ID {$studentID}");
            return response()->json(['success' => false, 'error' => 'Student not found']);
        }

        $parentPhone = (isset($student->parent) && !empty($student->parent->phone)) ? $student->parent->phone : null;
        if (!$parentPhone || $parentPhone === 'null' || $parentPhone === 'undefined') {
            $parentPhone = !empty($student->emergency_contact_phone) ? $student->emergency_contact_phone : null;
        }

        if (empty($parentPhone) || $parentPhone === 'null' || $parentPhone === 'undefined') {
            \Illuminate\Support\Facades\Log::warning("sendResultSms: No valid phone for student {$studentID}");
            return response()->json(['success' => false, 'error' => 'No valid contact phone found for student or parent']);
        }

        $studentName = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
        $studentFirstName = $student->first_name;
        
        $className = '';
        if ($student->subclass && $student->subclass->class) {
            $className = $student->subclass->class->class_name;
        } elseif ($student->oldSubclass && $student->oldSubclass->class) {
            $className = $student->oldSubclass->class->class_name;
        }

        $type = $request->input('type', 'exam');
        $term = $request->input('term');
        $year = $request->input('year', date('Y'));
        $userType = Session::get('user_type');

        $totalSum = 0;
        $formattedResults = [];
        $classID = $student->subclass->classID ?? ($student->oldSubclass->classID ?? null);

        if ($subject) {
            // Single subject mode
            $totalSum = (float)$marks;
            $formattedResults[] = "{$subject}-(".round($marks).")-({$grade})";
            $resultsString = $formattedResults[0];
        } elseif ($type === 'report') {
            // Term report: Average across exams in TermReportDefinition if available
            $reportDefinition = TermReportDefinition::where('schoolID', $schoolID)
                ->where('year', $year)
                ->where('term', $term)
                ->first();

            $examinationsQuery = Examination::where('schoolID', $schoolID)
                ->where('year', $year)
                ->where('term', $term);
            
            if ($reportDefinition && !empty($reportDefinition->exam_ids)) {
                $examinationsQuery->whereIn('examID', $reportDefinition->exam_ids);
            }
            
            $examinations = $examinationsQuery->pluck('examID');

            $results = Result::where('studentID', $studentID)
                ->whereIn('examID', $examinations)
                ->with('classSubject.subject')
                ->get();
            
            if ($results->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning("sendResultSms: No results found for student {$studentID} in term {$term} / year {$year}");
                return response()->json(['success' => false, 'error' => 'No results found for this term']);
            }

            $subjectData = [];
            foreach ($results as $res) {
                $sName = $res->classSubject->subject->subject_name ?? 'N/A';
                if (!isset($subjectData[$sName])) {
                    $subjectData[$sName] = ['total' => 0, 'count' => 0];
                }
                if ($res->marks !== null && $res->marks !== '') {
                    $subjectData[$sName]['total'] += (float)$res->marks;
                    $subjectData[$sName]['count']++;
                }
            }

            foreach ($subjectData as $sName => $data) {
                $avgMarks = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
                $totalSum += $avgMarks;
                $gradeRes = $this->getGradeFromDefinition($avgMarks, $classID);
                $sGrade = $gradeRes['grade'] ?? '-';
                $formattedResults[] = "{$sName}-(".round($avgMarks).")-({$sGrade})";
            }
            $resultsString = implode("\n", $formattedResults);
        } else {
            // Multi-subject mode (Exam level)
            // Check if this exam has CA defined
            $caDefinition = \App\Models\CaDefinition::where('schoolID', $schoolID)
                ->where('examID', $examID)
                ->first();
            
            $allExamIds = [$examID];
            if ($caDefinition && !empty($caDefinition->test_ids)) {
                $allExamIds = array_unique(array_merge([$examID], $caDefinition->test_ids));
            }

            $allResultsQuery = Result::where('studentID', $studentID)
                ->whereIn('examID', $allExamIds);
            
            if ($week && $week !== 'all' && (!$caDefinition || empty($caDefinition->test_ids))) {
                $allResultsQuery->where('test_week', $week);
            }
            
            $results = $allResultsQuery->with('classSubject.subject')->get();
            
            if ($results->isEmpty()) {
                \Log::warning("sendResultSms: No results found for student {$studentID}. ExamID: {$examID}, Week: {$week}, UserType: {$userType}");
                return response()->json(['success' => false, 'error' => 'No results found for this student']);
            }
            
            \Log::info("sendResultSms: Found " . $results->count() . " subjects for student {$studentID}");

            if ($caDefinition && !empty($caDefinition->test_ids)) {
                // Group by subject and average (CA support)
                $subjectData = [];
                foreach ($results as $res) {
                    $sName = $res->classSubject->subject->subject_name ?? 'N/A';
                    if (!isset($subjectData[$sName])) {
                        $subjectData[$sName] = ['total' => 0, 'count' => 0];
                    }
                    if ($res->marks !== null && $res->marks !== '') {
                        $subjectData[$sName]['total'] += (float)$res->marks;
                        $subjectData[$sName]['count']++;
                    }
                }
                
                foreach ($subjectData as $sName => $data) {
                    $avgMarks = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
                    $totalSum += $avgMarks;
                    $gradeRes = $this->getGradeFromDefinition($avgMarks, $classID);
                    $sGrade = $gradeRes['grade'] ?? '-';
                    $formattedResults[] = "{$sName}-(".round($avgMarks).")-({$sGrade})";
                }
            } else {
                // Standard mode
                foreach ($results as $res) {
                    $sName = $res->classSubject->subject->subject_name ?? 'N/A';
                    $sMarks = $res->marks ?? 0;
                    $sGrade = $res->grade ?? '-';
                    $totalSum += (float)$sMarks;
                    $formattedResults[] = "{$sName}-(".round((float)$sMarks).")-({$sGrade})";
                }
            }
            $resultsString = implode("\n", $formattedResults);
        }

        // Get week range
        $weekRange = '';
        $actualExamID = ($examID && $examID !== '1' && $examID !== 'null') ? $examID : null;
        
        // If we have results, use the examID from the first result if the provided one was generic
        if (!$actualExamID && isset($results) && !$results->isEmpty()) {
            $actualExamID = $results->first()->examID;
        }

        if ($week && $actualExamID) {
            $paper = ExamPaper::where('examID', $actualExamID)
                ->where('test_week', $week)
                ->select('test_week_range', 'test_date')
                ->first();
            
            if ($paper) {
                $weekRange = $paper->test_week_range;
                if (empty($weekRange) && $paper->test_date) {
                    $weekRange = date('d M Y', strtotime($paper->test_date));
                }
            }
        }
        
        if (empty($weekRange)) $weekRange = $week;

        $school = \App\Models\School::find($schoolID);
        $schoolName = $school ? $school->school_name : 'ShuleXpert';

        $weekLabel = $week;
        if ($type === 'report') {
            $weekLabel = "Term " . ucfirst($term) . " " . $year;
        } elseif (str_contains(strtolower($week), 'week')) {
            $weekLabel = "wiki ya {$weekRange}";
        } elseif (str_contains(strtolower($week), 'month')) {
            $weekLabel = "mwezi wa {$weekRange}";
        } elseif ($weekRange) {
            $weekLabel = $weekRange;
        }
        
        $division = $request->input('division', '');
        $position = $request->input('position', '');
        $totalStudentsCount = $request->input('totalStudentsCount', '');

        // Determine Exam Context Name
        $examNameContext = ($examID && $actualExamID) ? Examination::where('examID', $actualExamID)->value('exam_name') : ($weekLabel ?: 'MTIHANI');
        if ($type === 'report') {
            $termLabel = ($term === 'first_term') ? 'TERM 1' : 'TERM 2';
            $examNameContext = "RIPOTI YA {$termLabel} {$year}";
        }

        $schoolType = $school ? $school->school_type : 'Secondary';

        // Construct Premium SMS Format
        $msg = "{$schoolName}\n";
        $msg .= "MZAZI WA {$studentName},\n";
        $msg .= "YAFUATAYO NI MATOKEO YA {$studentName} KATIKA {$examNameContext}.\n";
        
        $summary = "JUMLA: " . round($totalSum);
        if ($schoolType === 'Secondary' && $division) {
            $summary .= " | DIV: {$division}";
        }
        if ($position && $totalStudentsCount) {
            $summary .= " | NAFASI: {$position}/{$totalStudentsCount}";
        }
        $msg .= $summary . "\n";
        
        $msg .= "------------------------\n";
        $msg .= "MASOMO YAFUATAYO:\n";
        $msg .= $resultsString;

        $message = $msg;
        \Log::info("Preparing to send Result SMS to {$parentPhone} for student {$studentName}. Message Length: " . strlen($message));

        try {
            $smsService = new SmsService();
            $result = $smsService->sendSms($parentPhone, $message);
            
            if ($result['success']) {
                \Log::info("Result SMS sent successfully to {$parentPhone}. Student: {$studentName}.");
                return response()->json(['success' => true]);
            } else {
                \Log::error("Result SMS failed for {$parentPhone} (Student: {$studentName}). Gateway Error: " . ($result['message'] ?? 'Unknown gateway error'));
                \Log::error("Gateway full response: " . ($result['response'] ?? 'N/A'));
                return response()->json(['success' => false, 'error' => $result['message'] ?? 'Failed to send SMS']);
            }
        } catch (\Exception $e) {
            \Log::error("Result SMS exception for student {$studentName}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Get exam results for students
     */
    private function getExamResults($students, $term, $year, $schoolType, $examID = null, $weekFilter = null, $subjectFilter = null)
    {
        $resultsData = [];

        foreach ($students as $student) {
            $studentResults = [];

            // Get examinations for this term and year
            $examinationsQuery = Examination::where('schoolID', Session::get('schoolID'))
                ->where('year', $year);

            if ($term) {
                $examinationsQuery->where('term', $term);
            }

            // Filter by specific exam if selected
            if ($examID) {
                $examinationsQuery->where('examID', $examID);
            }

            $examinations = $examinationsQuery->orderBy('start_date')->get();

            // Check if selected exam has CA definition
            $caDefinition = null;
            if ($examID) {
                $caDefinition = CaDefinition::where('examID', $examID)->where('schoolID', Session::get('schoolID'))->first();
            }

            foreach ($examinations as $exam) {
                // If CA is defined, we aggregate results from Tests + Main Exam
                if ($caDefinition) {
                    $allInvolvedExams = array_merge([$exam->examID], $caDefinition->test_ids);
                    $results = Result::where('studentID', $student->studentID)
                        ->whereIn('examID', $allInvolvedExams)
                        ->with(['classSubject.subject', 'examination'])
                        ->get();
                    
                    if ($results->isEmpty()) continue;

                    // Group by subject and average
                    $results = $results->groupBy('class_subjectID')->map(function($group) {
                        $marks = $group->filter(fn($r) => $r->marks !== null && $r->marks !== '');
                        $avg = $marks->count() > 0 ? $marks->avg('marks') : null;
                        $first = $group->first();
                        $first->marks = $avg;
                        $first->grade = null; // Re-calculate grade
                        return $first;
                    });
                } else {
                    // Standard fetching
                    $resultsQuery = Result::where('studentID', $student->studentID)
                        ->where('examID', $exam->examID);
                    
                    if ($exam->exam_name === 'Weekly Test' && $weekFilter && $weekFilter !== 'all') {
                        $resultsQuery->where('test_week', $weekFilter);
                    }

                    if ($subjectFilter) {
                        $resultsQuery->whereHas('classSubject', function($q) use ($subjectFilter) {
                            $q->where('subjectID', $subjectFilter);
                        });
                    }

                    $results = $resultsQuery->with(['classSubject.subject', 'examination'])
                        ->get();
                }

                if ($results->isEmpty()) {
                    continue;
                }

                // If Weekly Test and (All Weeks selected or no week filter), aggregate results by subject (Average marks)
                // For other exams, do not aggregate (take first result per subject if multiple exist, though usually 1-to-1)
                $isWeeklyTest = $exam->exam_name === 'Weekly Test';
                
                if ($isWeeklyTest && ($weekFilter === 'all' || !$weekFilter)) {
                    $results = $results->groupBy('classSubjectID')->map(function($group) {
                        $marks = $group->filter(function($r) {
                            return $r->marks !== null && $r->marks !== '';
                        });
                        $avgMarks = $marks->count() > 0 ? $marks->avg('marks') : null;
                        
                        $first = $group->first();
                        $first->marks = $avgMarks;
                        $first->grade = null; // Reset grade to allow recalculation based on average
                        return $first;
                    });
                }

                // If Weekly Test and specific week is selected, fetch detailed breakdown
                if ($isWeeklyTest && $weekFilter && $weekFilter !== 'all') {
                    foreach ($results as $result) {
                        try {
                            $paper = ExamPaper::where('examID', $exam->examID)
                                ->where('class_subjectID', $result->class_subjectID)
                                ->where('test_week', $weekFilter)
                                ->first();

                            if ($paper) {
                                // Get all questions for this paper
                                $allQuestions = \App\Models\ExamPaperQuestion::where('exam_paperID', $paper->exam_paperID)
                                    ->orderBy('question_number')
                                    ->get();

                                // Get existing marks for this student
                                $qMarks = ExamPaperQuestionMark::where('studentID', $student->studentID)
                                    ->whereIn('exam_paper_questionID', $allQuestions->pluck('exam_paper_questionID'))
                                    ->with('question')
                                    ->get()
                                    ->keyBy('exam_paper_questionID');
                                
                                // Merge questions with marks
                                $mergedQuestions = $allQuestions->map(function($q) use ($qMarks) {
                                    $hasMark = $qMarks->has($q->exam_paper_questionID);
                                    $markEntry = $hasMark ? $qMarks->get($q->exam_paper_questionID) : null;
                                    
                                    return (object) [
                                        'question' => $q->question_number,
                                        'question_description' => $q->question_description, // In case you want to show description
                                        'marks' => $hasMark ? $markEntry->marks : 'Incomplete',
                                        'max_marks' => $q->marks,
                                        'is_optional' => $q->is_optional
                                    ];
                                });

                                $result->question_details = $mergedQuestions;
                            }
                        } catch (\Exception $e) {
                            // Ignore
                        }
                    }
                }

                // Calculate totals
                $totalMarks = 0;
                $subjectCount = 0;
                $subjectsData = [];
                $subjectPoints = [];

                // Get class name and classID - handle both current subclass and old subclass (for history students)
                $className = '';
                $classID = null;
                if ($student->subclass && $student->subclass->class) {
                    $className = $student->subclass->class->class_name ?? '';
                    $classID = $student->subclass->class->classID ?? null;
                } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                    $className = $student->oldSubclass->class->class_name ?? '';
                    $classID = $student->oldSubclass->class->classID ?? null;
                }

                foreach ($results as $result) {
                    if ($result->marks !== null && $result->marks !== '') {
                        $totalMarks += (float) $result->marks;
                        $subjectCount++;

                        // Calculate points for this subject (Round averaged marks for grading consistency)
                        $roundedMarks = round((float)$result->marks, 0);
                        $gradePoints = $this->calculateGradePoints($roundedMarks, $schoolType, $className, $classID);
                        if ($gradePoints['points'] !== null) {
                            $subjectPoints[] = $gradePoints['points'];
                        }
                    } else {
                        // Include incomplete subjects in the count to allow viewing
                        $subjectCount++;
                    }
                    $gradePointsForSubject = $this->calculateGradePoints(round((float)$result->marks, 0), $schoolType, $className, $classID);
                    $subjectsData[] = [
                        'subject_name' => $result->classSubject->subject->subject_name ?? 'N/A',
                        'marks' => $result->marks ?? 'incomplete',
                        'grade' => $result->grade ?? $gradePointsForSubject['grade'],
                        'points' => $gradePointsForSubject['points'],
                        'question_marks' => $result->question_details ?? [],
                    ];
                }

                // Removed validation: Only add if student has at least one subject with marks
                // if ($subjectCount == 0) {
                //     continue;
                // }

                $averageMarks = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

                // Calculate total points for division (for O-Level and A-Level)
                $totalPoints = 0;
                $bestSevenTotalMarks = 0;

                if ($schoolType === 'Secondary' && (
                    in_array(strtolower(preg_replace('/[\s\-]+/', '_', $className)), ['form_one', 'form_two', 'form_three', 'form_four', 'form_1', 'form_2', 'form_3', 'form_4']) ||
                    preg_match('/form\s*(one|two|three|four|1|2|3|4)/i', $className)
                )) {
                    // O-Level: Use 7 best subjects (lowest points = best performance)
                    // Sort ascending (lowest points first = best performance)
                    if (count($subjectPoints) > 0) {
                        // Create array with marks and points for sorting
                        $marksPointsArray = [];
                        foreach ($subjectsData as $subject) {
                            if ($subject['marks'] !== null && $subject['marks'] !== 'incomplete' && $subject['marks'] !== '' && $subject['points'] !== null) {
                                $marksPointsArray[] = [
                                    'marks' => (float)$subject['marks'],
                                    'points' => $subject['points']
                                ];
                            }
                        }

                        // Sort by points ascending (lowest points first = best)
                        usort($marksPointsArray, function($a, $b) {
                            if ($a['points'] != $b['points']) {
                                return $a['points'] <=> $b['points'];
                            }
                            // If points are equal, sort by marks descending (higher marks = better)
                            return $b['marks'] <=> $a['marks'];
                        });

                        // Get best 7 (lowest points)
                        $bestSeven = array_slice($marksPointsArray, 0, min(7, count($marksPointsArray)));

                        // Calculate total points
                        $totalPoints = array_sum(array_column($bestSeven, 'points'));
                        
                        // PAD MISSING SUBJECTS: O-Level always uses 7 subjects
                        if (count($bestSeven) < 7) {
                            $totalPoints += (7 - count($bestSeven)) * 5;
                        }

                        // Calculate total marks for best 7 subjects
                        $bestSevenTotalMarks = array_sum(array_column($bestSeven, 'marks'));
                    }
                } elseif ($schoolType === 'Secondary' && (
                    in_array(strtolower(preg_replace('/[\s\-]+/', '_', $className)), ['form_five', 'form_six', 'form_5', 'form_6']) ||
                    preg_match('/form\s*(five|six|5|6)/i', $className)
                )) {
                    // A-Level: Use 3 best principal subjects (highest points)
                    if (count($subjectPoints) > 0) {
                        rsort($subjectPoints); // Sort descending (highest first)
                        $bestThree = array_slice($subjectPoints, 0, min(3, count($subjectPoints)));
                        $totalPoints = array_sum($bestThree);
                    }
                }

                // Calculate grade/division with points (SKIPPED if specific week is selected)
                $gradeDivision = ['grade' => null, 'division' => null];
                if (!$weekFilter || $weekFilter === 'all') {
                    $gradeDivision = $this->calculateGradeDivision($totalMarks, $averageMarks, $subjectCount, $schoolType, $className, $totalPoints, $classID);
                }

                $studentResults[] = [
                    'exam' => $exam,
                    'total_marks' => $totalMarks,
                    'average_marks' => $averageMarks,
                    'subject_count' => $subjectCount,
                    'subjects' => $subjectsData,
                    'grade' => $gradeDivision['grade'] ?? null,
                    'division' => $gradeDivision['division'] ?? null,
                    'total_points' => $totalPoints,
                    'best_seven_total_marks' => $bestSevenTotalMarks,
                ];
            }

            if (!empty($studentResults)) {
                $resultsData[$student->studentID] = $studentResults;
            }
        }

        return $resultsData;
    }

    /**
     * Get term report (average of all exams in term)
     */
    private function getTermReport($students, $term, $year, $schoolType, $subjectFilter = null)
    {
        $resultsData = [];
        $schoolID = Session::get('schoolID');

        // Check if there's a custom report definition for this term
        $reportDefinition = TermReportDefinition::where('schoolID', $schoolID)
            ->where('year', $year)
            ->where('term', $term)
            ->first();

        // OPTIMIZATION: Query examinations
        $examinationsQuery = Examination::where('schoolID', $schoolID)
            ->where('year', $year)
            ->where('term', $term);

        // If definition exists and has exams, restrict to those exams
        if ($reportDefinition && !empty($reportDefinition->exam_ids)) {
            $examinationsQuery->whereIn('examID', $reportDefinition->exam_ids);
        }

        $examinations = $examinationsQuery->orderBy('start_date')->get();

        // No longer filtering by ended status
        $endedExaminations = $examinations;

        $examIDs = $endedExaminations->pluck('examID')->toArray();

        if ($endedExaminations->isEmpty()) {
            return $resultsData;
        }

        // OPTIMIZATION: Get all student IDs
        $studentIDs = $students->pluck('studentID')->toArray();

        // Fetch all results in batch
        $allResultsQuery = Result::whereIn('studentID', $studentIDs)
            ->whereIn('examID', $examIDs)
            ->with(['classSubject.subject']);

        if ($subjectFilter) {
            $allResultsQuery->whereHas('classSubject', function($q) use ($subjectFilter) {
                $q->where('subjectID', $subjectFilter);
            });
        }

        $allResults = $allResultsQuery->get()
            ->groupBy('studentID');

        // Store results by studentID and examID for quick lookup
        $resultsByStudentAndExam = [];
        foreach ($allResults as $studentID => $studentResults) {
            $resultsByStudentAndExam[$studentID] = $studentResults->groupBy('examID');
        }

        foreach ($students as $student) {
            $studentResultsByExam = $resultsByStudentAndExam[$student->studentID] ?? collect();

            if ($studentResultsByExam->isEmpty()) {
                continue;
            }

            // Get class name and classID once
            $className = '';
            $classID = null;
            if ($student->subclass && $student->subclass->class) {
                $className = $student->subclass->class->class_name ?? '';
                $classID = $student->subclass->class->classID ?? null;
            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                $className = $student->oldSubclass->class->class_name ?? '';
                $classID = $student->oldSubclass->class->classID ?? null;
            }

            $allExamResults = [];
            $totalMarksAllExams = 0;
            $totalSubjectCount = 0;
            $allSubjectPoints = []; // Collect points from all exams

            foreach ($endedExaminations as $exam) {
                $results = $studentResultsByExam->get($exam->examID, collect());

                if ($results->isEmpty()) {
                    continue;
                }

                $examTotalMarks = 0;
                $examSubjectCount = 0;

                foreach ($results as $result) {
                    if ($result->marks !== null && $result->marks !== '') {
                        $marks = (float) $result->marks;
                        $examTotalMarks += $marks;
                        $examSubjectCount++;

                        // OPTIMIZATION: Calculate points here instead of querying again
                        $gradePoints = $this->calculateGradePoints($marks, $schoolType, $className, $classID);
                        if ($gradePoints['points'] !== null) {
                            $allSubjectPoints[] = $gradePoints['points'];
                        }
                    }
                }

                if ($examSubjectCount > 0) {
                    $examAverage = $examTotalMarks / $examSubjectCount;

                    // Calculate grade for this exam based on average using grade_definitions
                    $examGrade = null;
                    if ($classID && $examAverage > 0) {
                        $gradeResult = $this->getGradeFromDefinition($examAverage, $classID);
                        $examGrade = $gradeResult['grade'];
                    } else {
                        // Fallback to old logic
                        if ($examAverage >= 75) {
                            $examGrade = 'A';
                        } elseif ($examAverage >= 65) {
                            $examGrade = 'B';
                        } elseif ($examAverage >= 45) {
                            $examGrade = 'C';
                        } elseif ($examAverage >= 30) {
                            $examGrade = 'D';
                        } else {
                            $examGrade = 'F';
                        }
                    }

                    $allExamResults[] = [
                        'exam' => $exam,
                        'total_marks' => $examTotalMarks,
                        'subject_count' => $examSubjectCount,
                        'average' => $examAverage,
                        'grade' => $examGrade,
                    ];
                    $totalMarksAllExams += $examTotalMarks;
                    $totalSubjectCount += $examSubjectCount;
                }
            }

            if (!empty($allExamResults)) {
                // Calculate overall average as average of exam averages (for report view)
                $examAveragesSum = 0;
                foreach ($allExamResults as $examResult) {
                    $examAveragesSum += $examResult['average'];
                }
                $overallAverage = count($allExamResults) > 0 ? $examAveragesSum / count($allExamResults) : 0;

                // Calculate total points for division
                $totalPoints = 0;
                if ($schoolType === 'Secondary' && in_array(strtolower(preg_replace('/[\s\-]+/', '_', $className)), ['form_one', 'form_two', 'form_three', 'form_four'])) {
                    // O-Level: Use 7 best subjects (lowest points = best performance)
                    if (count($allSubjectPoints) > 0) {
                        sort($allSubjectPoints); // Sort ascending (lowest first = best)
                        $bestSeven = array_slice($allSubjectPoints, 0, min(7, count($allSubjectPoints)));
                        $totalPoints = array_sum($bestSeven);
                        
                        // PAD MISSING SUBJECTS: O-Level always uses 7 subjects
                        if (count($bestSeven) < 7) {
                            $totalPoints += (7 - count($bestSeven)) * 5;
                        }
                    }
                } elseif ($schoolType === 'Secondary' && in_array(strtolower(preg_replace('/[\s\-]+/', '_', $className)), ['form_five', 'form_six'])) {
                    // A-Level: Use 3 best principal subjects (highest points)
                    if (count($allSubjectPoints) > 0) {
                        rsort($allSubjectPoints); // Sort descending (highest first)
                        $bestThree = array_slice($allSubjectPoints, 0, min(3, count($allSubjectPoints)));
                        $totalPoints = array_sum($bestThree);
                    }
                }

                // Calculate overall grade based on overall average using grade_definitions
                $overallGrade = null;
                if ($classID && $overallAverage > 0) {
                    $gradeResult = $this->getGradeFromDefinition($overallAverage, $classID);
                    $overallGrade = $gradeResult['grade'];
                } else {
                    // Fallback to old logic
                    if ($overallAverage >= 75) {
                        $overallGrade = 'A';
                    } elseif ($overallAverage >= 65) {
                        $overallGrade = 'B';
                    } elseif ($overallAverage >= 45) {
                        $overallGrade = 'C';
                    } elseif ($overallAverage >= 30) {
                        $overallGrade = 'D';
                    } else {
                        $overallGrade = 'F';
                    }
                }

                // Calculate division for Secondary using points
                $gradeDivision = $this->calculateGradeDivision($totalMarksAllExams, $overallAverage, $totalSubjectCount, $schoolType, $className, $totalPoints, $classID);

                // OPTIMIZATION: Skip position calculation here - it's very expensive and will be calculated in the view per class
                // Position will be calculated in the Blade template where it's actually needed

                $displayGrade = $overallGrade;
                if ($schoolType === 'Secondary' && !empty($gradeDivision['division'])) {
                    $displayGrade = $gradeDivision['division'];
                }

                $resultsData[$student->studentID] = [
                    'exams' => $allExamResults,
                    'total_marks' => $totalMarksAllExams,
                    'average_marks' => $overallAverage,
                    'subject_count' => $totalSubjectCount,
                    'exam_count' => count($allExamResults),
                    'grade' => $displayGrade,
                    'division' => $gradeDivision['division'] ?? null,
                    'position' => null, // Will be calculated in view per class
                    'total_points' => $totalPoints,
                ];
            }
        }

        return $resultsData;
    }

    /**
     * Get grade from grade_definitions table based on classID and marks
     */
    private function getGradeFromDefinition($marks, $classID)
    {
        if ($marks === null || $marks === '' || !$classID) {
            return ['grade' => null, 'points' => null];
        }

        $marksNum = (float) $marks;

        // Get grade definition from database
        $gradeDefinition = GradeDefinition::where('classID', $classID)
            ->where('first', '<=', $marksNum)
            ->where('last', '>=', $marksNum)
            ->first();

        if (!$gradeDefinition) {
            return ['grade' => null, 'points' => null];
        }

        // Calculate points based on grade (for backward compatibility)
        $grade = $gradeDefinition->grade;
        $points = null;

        // Points calculation for O-Level and A-Level (maintain existing logic)
        $className = ClassModel::find($classID)->class_name ?? '';
        $classNameLower = strtolower(preg_replace('/[\s\-]+/', '_', $className));

        if (in_array($classNameLower, ['form_one', 'form_two', 'form_three', 'form_four'])) {
            // O-Level points
            $pointsMap = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'F' => 5];
            $points = $pointsMap[$grade] ?? 5;
        } elseif (in_array($classNameLower, ['form_five', 'form_six'])) {
            // A-Level points
            $pointsMap = ['A' => 5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1, 'S/F' => 0];
            $points = $pointsMap[$grade] ?? 0;
        }

        return ['grade' => $grade, 'points' => $points];
    }

    /**
     * Calculate grade points for a single subject
     * Now uses grade_definitions table based on student's class
     */
    private function calculateGradePoints($marks, $schoolType, $className, $classID = null)
    {
        if ($marks === null || $marks === '') {
            return ['grade' => null, 'points' => null];
        }

        // If classID is provided, use grade_definitions table
        if ($classID) {
            return $this->getGradeFromDefinition($marks, $classID);
        }

        // Fallback to old logic if classID not provided (for backward compatibility)
        $marksNum = (float) $marks;
        $classNameLower = strtolower(preg_replace('/[\s\-]+/', '_', $className));

        if ($schoolType === 'Secondary') {
            if (in_array($classNameLower, ['form_one', 'form_two', 'form_three', 'form_four'])) {
                // O-Level Grading System (Secondary only)
                if ($marksNum >= 75 && $marksNum <= 100) {
                    return ['grade' => 'A', 'points' => 1];
                } elseif ($marksNum >= 65 && $marksNum <= 74) {
                    return ['grade' => 'B', 'points' => 2];
                } elseif ($marksNum >= 45 && $marksNum <= 64) {
                    return ['grade' => 'C', 'points' => 3];
                } elseif ($marksNum >= 30 && $marksNum <= 44) {
                    return ['grade' => 'D', 'points' => 4];
                } elseif ($marksNum <= 29) {
                    return ['grade' => 'F', 'points' => 5];
                } else {
                    return ['grade' => 'F', 'points' => 5];
                }
            } elseif (in_array($classNameLower, ['form_five', 'form_six'])) {
                // A-Level Grading System
                if ($marksNum >= 80) {
                    return ['grade' => 'A', 'points' => 5];
                } elseif ($marksNum >= 70) {
                    return ['grade' => 'B', 'points' => 4];
                } elseif ($marksNum >= 60) {
                    return ['grade' => 'C', 'points' => 3];
                } elseif ($marksNum >= 50) {
                    return ['grade' => 'D', 'points' => 2];
                } elseif ($marksNum >= 40) {
                    return ['grade' => 'E', 'points' => 1];
                } else {
                    return ['grade' => 'S/F', 'points' => 0];
                }
            }
        }

        // Primary or default - no points calculation
        return ['grade' => null, 'points' => null];
    }

    /**
     * Calculate grade or division with points
     * Now uses grade_definitions table for average marks grade
     */
    private function calculateGradeDivision($totalMarks, $averageMarks, $subjectCount, $schoolType, $className, $totalPoints = 0, $classID = null)
    {
        $classNameLower = strtolower(preg_replace('/[\s\-]+/', '_', $className));

        if ($schoolType === 'Secondary' && (in_array($classNameLower, ['form_one', 'form_two', 'form_three', 'form_four', 'form_1', 'form_2', 'form_3', 'form_4']) || preg_match('/form\s*(one|two|three|four|1|2|3|4)/i', $className))) {
            // O-Level: Calculate division based on total points (7 best subjects)
            // 7-17: I
            // 18-21: II
            // 22-25: III
            // 26-33: IV
            // >=34: 0
            if ($totalPoints >= 7 && $totalPoints <= 17) {
                return ['grade' => null, 'division' => 'I.' . $totalPoints];
            } elseif ($totalPoints >= 18 && $totalPoints <= 21) {
                return ['grade' => null, 'division' => 'II.' . $totalPoints];
            } elseif ($totalPoints >= 22 && $totalPoints <= 25) {
                return ['grade' => null, 'division' => 'III.' . $totalPoints];
            } elseif ($totalPoints >= 26 && $totalPoints <= 33) {
                return ['grade' => null, 'division' => 'IV.' . $totalPoints];
            } elseif ($totalPoints >= 34) {
                return ['grade' => null, 'division' => '0.' . $totalPoints];
            } else {
                // If total points is less than 7, still assign Division 0
                return ['grade' => null, 'division' => '0.' . $totalPoints];
            }
        } elseif ($schoolType === 'Secondary' && (in_array($classNameLower, ['form_five', 'form_six', 'form_5', 'form_6']) || preg_match('/form\s*(five|six|5|6)/i', $className))) {
            // A-Level: Calculate division based on total points
            if ($totalPoints >= 12 && $totalPoints <= 15) {
                return ['grade' => null, 'division' => 'I.' . $totalPoints];
            } elseif ($totalPoints >= 9 && $totalPoints <= 11) {
                return ['grade' => null, 'division' => 'II.' . $totalPoints];
            } elseif ($totalPoints >= 6 && $totalPoints <= 8) {
                return ['grade' => null, 'division' => 'III.' . $totalPoints];
            } elseif ($totalPoints >= 3 && $totalPoints <= 5) {
                return ['grade' => null, 'division' => 'IV.' . $totalPoints];
            } else {
                return ['grade' => null, 'division' => '0.' . $totalPoints];
            }
        } else {
            // Primary or Secondary without division: Calculate grade from grade_definitions
            if ($classID && $averageMarks !== null && $averageMarks !== '') {
                $gradeResult = $this->getGradeFromDefinition($averageMarks, $classID);
                return ['grade' => $gradeResult['grade'], 'division' => null];
            }

            // Fallback to old logic if classID not provided
            if ($averageMarks >= 75) {
                return ['grade' => 'A', 'division' => null];
            } elseif ($averageMarks >= 65) {
                return ['grade' => 'B', 'division' => null];
            } elseif ($averageMarks >= 45) {
                return ['grade' => 'C', 'division' => null];
            } elseif ($averageMarks >= 30) {
                return ['grade' => 'D', 'division' => null];
            } else {
                return ['grade' => 'F', 'division' => null];
            }
        }
    }

    /**
     * Calculate student position in main class
     */
    private function calculatePosition($student, $term, $year, $studentAverage, $schoolType)
    {
        // Get main class (class, not subclass) - handle both current and old subclass
        $mainClassID = null;
        if ($student->subclass) {
            $mainClassID = $student->subclass->classID ?? null;
        } elseif ($student->oldSubclass) {
            $mainClassID = $student->oldSubclass->classID ?? null;
        }

        if (!$mainClassID) {
            return null;
        }

        // Get all students in the same main class
        $classStudents = Student::whereHas('subclass', function($query) use ($mainClassID) {
            $query->where('classID', $mainClassID);
        })
        ->where('schoolID', Session::get('schoolID'))
        ->where('status', 'Active')
        ->get();

        // Calculate averages for all students in the class
        $studentAverages = [];

        $userType = Session::get('user_type');
        $hasBroadResultPermission = $this->hasPermission('view_results');
        $bypassRestrictions = ($userType === 'Admin' || $hasBroadResultPermission);

        foreach ($classStudents as $classStudent) {
            $examinationsQuery = Examination::where('schoolID', Session::get('schoolID'))
                ->where('year', $year);
            
            if (!$bypassRestrictions) {
                $examinationsQuery->where('approval_status', 'Approved');
            }

            if ($term) {
                $examinationsQuery->where('term', $term);
            }

            $examinations = $examinationsQuery->get();

            $totalMarks = 0;
            $totalSubjects = 0;

            foreach ($examinations as $exam) {
                // Get results with marks
                $resultsQuery = Result::where('studentID', $classStudent->studentID)
                    ->where('examID', $exam->examID)
                    ->whereNotNull('marks');
                
                if (!$bypassRestrictions) {
                    $resultsQuery->where('status', 'allowed');
                }
                
                $results = $resultsQuery->get();

                foreach ($results as $result) {
                    if ($result->marks !== null && $result->marks !== '') {
                        $totalMarks += (float) $result->marks;
                        $totalSubjects++;
                    }
                }
            }

            if ($totalSubjects > 0) {
                $avg = $totalMarks / $totalSubjects;
                $studentAverages[] = [
                    'studentID' => $classStudent->studentID,
                    'average' => $avg,
                ];
            }
        }

        // Sort by average (descending)
        usort($studentAverages, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });

        // Find position
        $position = null;
        foreach ($studentAverages as $index => $data) {
            if ($data['studentID'] == $student->studentID) {
                $position = $index + 1;
                break;
            }
        }

        return $position;
    }

    /**
     * Download results as PDF
     */
    public function downloadPdf(Request $request)
    {
        $userType = Session::get('user_type');
        $schoolID = Session::get('schoolID');

        // Allow any logged-in user with a valid school session to download PDF
        if (!$schoolID) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        $option = $request->get('option', 'all'); // single, all, class, subclass
        $studentID = $request->get('studentID', null);
        $classID = $request->get('classID', null);
        $subclassID = $request->get('subclassID', null);

        // Get filter parameters from request
        $termFilter = $request->get('term', '');
        $yearFilter = $request->get('year', date('Y'));
        $typeFilter = $request->get('type', 'exam');
        $statusFilter = $request->get('status', 'active');
        $examFilter = $request->get('examID', '');

        // Get school details
        $school = \App\Models\School::find($schoolID);
        $schoolType = $school ? $school->school_type : 'Secondary';

        // Get students based on option
        $students = collect();
        if ($option === 'single' && $studentID) {
            $students = Student::where('studentID', $studentID)
                ->where('schoolID', $schoolID)
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->get();
        } elseif ($option === 'class' && $classID) {
            $students = Student::where('schoolID', $schoolID)
                ->where(function($query) use ($classID) {
                    $query->whereHas('subclass', function($q) use ($classID) {
                        $q->where('classID', $classID);
                    })->orWhereHas('oldSubclass', function($q) use ($classID) {
                        $q->where('classID', $classID);
                    });
                })
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        } elseif ($option === 'subclass' && $subclassID) {
            $students = Student::where('schoolID', $schoolID)
                ->where('subclassID', $subclassID)
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        } else {
            // All students — respect optional class/subclass/gender filters
            $classFilterID    = $request->get('classID', '');
            $subclassFilterID = $request->get('subclassID', '');
            $genderFilter     = $request->get('gender', '');

            $studentQuery = Student::where('schoolID', $schoolID);

            if ($statusFilter === 'active') {
                $studentQuery->where('status', 'Active');
            } elseif ($statusFilter === 'history') {
                $studentQuery->whereNotNull('old_subclassID');
            }

            // Filter by subclass if provided
            if ($subclassFilterID) {
                $studentQuery->where('subclassID', $subclassFilterID);
            // Filter by class if provided (no subclass)
            } elseif ($classFilterID) {
                $studentQuery->where(function($q) use ($classFilterID) {
                    $q->whereHas('subclass', function($sq) use ($classFilterID) {
                        $sq->where('classID', $classFilterID);
                    })->orWhereHas('oldSubclass', function($sq) use ($classFilterID) {
                        $sq->where('classID', $classFilterID);
                    });
                });
            }

            // Filter by gender if provided
            if ($genderFilter) {
                $studentQuery->where('gender', $genderFilter);
            }

            $students = $studentQuery
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        }


        // Get results data
        $resultsData = [];
        if ($termFilter && $yearFilter) {
            if ($typeFilter === 'exam') {
                $resultsData = $this->getExamResults($students, $termFilter, $yearFilter, $schoolType, $examFilter);
            } else {
                $resultsData = $this->getTermReport($students, $termFilter, $yearFilter, $schoolType);
            }
        }

        // Filter students to only those with results
        $students = $students->filter(function($student) use ($resultsData) {
            return isset($resultsData[$student->studentID]);
        });

        // Fetch detailed data for single student (CAs and Exam breakdowns)
        $detailedSingleData = null;
        $detailedBulkData = [];
        if ($option === 'single' && count($students) > 0) {
            $reqClone = $request->duplicate();
            $reqClone->merge(['getSubjectDetails' => true, 'studentID' => $studentID]);
            $res = $this->index($reqClone);
            if ($res instanceof \Illuminate\Http\JsonResponse) {
                $detailedSingleData = json_decode($res->content(), true);
            }
        } elseif ($option === 'bulk_single' && count($students) > 0) {
            set_time_limit(300); // 5 minutes execution time
            ini_set('memory_limit', '1024M'); // Allow up to 1GB for heavy PDF generation
            
            foreach ($students as $student) {
                $reqClone = $request->duplicate();
                $reqClone->merge(['getSubjectDetails' => true, 'studentID' => $student->studentID]);
                $res = $this->index($reqClone);
                if ($res instanceof \Illuminate\Http\JsonResponse) {
                    $detailedBulkData[$student->studentID] = json_decode($res->content(), true);
                }
            }
        }

        // Build title based on filters
        $title = '';
        if ($typeFilter === 'exam') {
            $title = 'Exam Results';
            if ($examFilter) {
                $exam = Examination::find($examFilter);
                if ($exam) {
                    $title = $exam->exam_name;
                }
            }
        } else {
            $title = 'Term Report';
        }

        if ($termFilter) {
            $title .= ' - ' . ucfirst(str_replace('_', ' ', $termFilter));
        }
        $title .= ' - ' . $yearFilter;

        // Prepare data for PDF
        $data = [
            'school' => $school,
            'schoolType' => $schoolType,
            'students' => $students,
            'resultsData' => $resultsData,
            'detailedSingleData' => $detailedSingleData,
            'detailedBulkData' => $detailedBulkData,
            'filters' => [
                'term' => $termFilter,
                'year' => $yearFilter,
                'type' => $typeFilter,
                'status' => $statusFilter,
                'examID' => $examFilter,
                'class' => $request->get('class', ''),
                'subclass' => $request->get('subclass', ''),
                'grade' => $request->get('grade', ''),
                'gender' => $request->get('gender', ''),
            ],
            'option' => $option,
            'title' => $title,
        ];

        // Generate PDF
        $pdf = PDF::loadView('Admin.pdf.results', $data);
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'Results_';
        if ($option === 'single' && $students->count() > 0) {
            $student = $students->first();
            $filename .= $student->first_name . '_' . $student->last_name . '_';
        } elseif ($option === 'class' && $classID) {
            $class = ClassModel::find($classID);
            $filename .= ($class ? str_replace(' ', '_', $class->class_name) : 'Class') . '_';
        } elseif ($option === 'subclass' && $subclassID) {
            $subclass = Subclass::find($subclassID);
            $filename .= ($subclass ? str_replace(' ', '_', $subclass->subclass_name) : 'Subclass') . '_';
        } else {
            $filename .= 'All_Students_';
        }
        $filename .= ($typeFilter === 'exam' ? 'Exam' : 'Term_Report') . '_';
        $filename .= ($termFilter ? str_replace('_', '', $termFilter) . '_' : '') . $yearFilter . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download results as Excel
     */
    public function downloadExcel(Request $request)
    {
        $userType = Session::get('user_type');
        $schoolID = Session::get('schoolID');

        if (($userType !== 'Admin' && !$this->hasPermission('view_results')) || !$schoolID) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        $option = $request->get('option', 'all');
        $studentID = $request->get('studentID', null);
        $classID = $request->get('classID', null);
        $subclassID = $request->get('subclassID', null);

        // Get filter parameters
        $termFilter = $request->get('term', '');
        $yearFilter = $request->get('year', date('Y'));
        $typeFilter = $request->get('type', 'exam');
        $statusFilter = $request->get('status', 'active');
        $examFilter = $request->get('examID', '');

        // Get school details
        $school = \App\Models\School::find($schoolID);
        $schoolType = $school ? $school->school_type : 'Secondary';

        // Get students based on option (same logic as PDF)
        $students = collect();
        if ($option === 'single' && $studentID) {
            $students = Student::where('studentID', $studentID)
                ->where('schoolID', $schoolID)
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->get();
        } elseif ($option === 'class' && $classID) {
            $students = Student::where('schoolID', $schoolID)
                ->where(function($query) use ($classID) {
                    $query->whereHas('subclass', function($q) use ($classID) {
                        $q->where('classID', $classID);
                    })->orWhereHas('oldSubclass', function($q) use ($classID) {
                        $q->where('classID', $classID);
                    });
                })
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        } elseif ($option === 'subclass' && $subclassID) {
            $students = Student::where('schoolID', $schoolID)
                ->where('subclassID', $subclassID)
                ->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        } else {
            $studentQuery = Student::where('schoolID', $schoolID);
            if ($statusFilter === 'active') {
                $studentQuery->where('status', 'Active');
            } elseif ($statusFilter === 'history') {
                $studentQuery->whereNotNull('old_subclassID');
            }
            $students = $studentQuery->with(['subclass.class', 'subclass.combie', 'parent', 'oldSubclass.class'])
                ->orderBy('first_name')
                ->get();
        }

        // Get results data
        $resultsData = [];
        if ($termFilter && $yearFilter) {
            if ($typeFilter === 'exam') {
                $resultsData = $this->getExamResults($students, $termFilter, $yearFilter, $schoolType, $examFilter);
            } else {
                $resultsData = $this->getTermReport($students, $termFilter, $yearFilter, $schoolType);
            }
        }

        // Filter students to only those with results
        $students = $students->filter(function($student) use ($resultsData) {
            return isset($resultsData[$student->studentID]);
        });

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Build title based on filters
        $title = '';
        if ($typeFilter === 'exam') {
            $title = 'Exam Results';
            if ($examFilter) {
                $exam = Examination::find($examFilter);
                if ($exam) {
                    $title = $exam->exam_name;
                }
            }
        } else {
            $title = 'Term Report';
        }

        if ($termFilter) {
            $title .= ' - ' . ucfirst(str_replace('_', ' ', $termFilter));
        }
        $title .= ' - ' . $yearFilter;

        // Add school name and title
        $sheet->setCellValue('A1', $school->school_name ?? 'School');
        $sheet->setCellValue('A2', $title);

        // Merge cells for better appearance
        $lastCol = 'K'; // Adjust based on number of columns
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->mergeCells('A2:' . $lastCol . '2');

        // Style header
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:' . $lastCol . '1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF940000');
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Set headers
        $headers = ['#', 'Student Name', 'Admission No.', 'Class', 'Subclass', 'Total Marks', 'Average', 'Grade', 'Division'];
        if ($typeFilter === 'report') {
            $headers[] = 'Position';
            $headers[] = 'Exams Count';
        } else {
            $headers[] = 'Exam Name';
        }

        $row = 4;
        $col = 'A';
        $lastHeaderCol = '';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $lastHeaderCol = $col;
            $col++;
        }

        // Style header row
        $sheet->getStyle('A4:' . $lastHeaderCol . '4')->getFont()->setBold(true);
        $sheet->getStyle('A4:' . $lastHeaderCol . '4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF940000');
        $sheet->getStyle('A4:' . $lastHeaderCol . '4')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Add data
        $row = 5;
        $index = 1;
        foreach ($students as $student) {
            if (isset($resultsData[$student->studentID])) {
                $result = $resultsData[$student->studentID];

                $className = '';
                $subclassName = '';
                if ($student->subclass && $student->subclass->class) {
                    $className = $student->subclass->class->class_name ?? '';
                    $subclassName = $student->subclass->subclass_name ?? '';
                } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                    $className = $student->oldSubclass->class->class_name ?? '';
                    $subclassName = $student->oldSubclass->subclass_name ?? '';
                }

                if ($typeFilter === 'report') {
                    $sheet->setCellValue('A' . $row, $index);
                    $sheet->setCellValue('B' . $row, $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);
                    $sheet->setCellValue('C' . $row, $student->admission_number ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $className);
                    $sheet->setCellValue('E' . $row, $subclassName);
                    $sheet->setCellValue('F' . $row, number_format($result['total_marks'], 2));
                    $sheet->setCellValue('G' . $row, number_format($result['average_marks'], 2));
                    $sheet->setCellValue('H' . $row, $result['grade'] ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $result['division'] ?? 'N/A');
                    $sheet->setCellValue('J' . $row, $result['position'] ?? 'N/A');
                    $sheet->setCellValue('K' . $row, $result['exam_count'] ?? 0);
                } else {
                    // For exam results, show first exam or aggregate
                    $firstExam = is_array($result) && !empty($result) ? $result[0] : $result;
                    $sheet->setCellValue('A' . $row, $index);
                    $sheet->setCellValue('B' . $row, $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);
                    $sheet->setCellValue('C' . $row, $student->admission_number ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $className);
                    $sheet->setCellValue('E' . $row, $subclassName);
                    $sheet->setCellValue('F' . $row, number_format($firstExam['total_marks'] ?? 0, 2));
                    $sheet->setCellValue('G' . $row, number_format($firstExam['average_marks'] ?? 0, 2));
                    $sheet->setCellValue('H' . $row, $firstExam['grade'] ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $firstExam['division'] ?? 'N/A');
                    $sheet->setCellValue('J' . $row, $firstExam['exam']->exam_name ?? 'N/A');
                }

                $row++;
                $index++;
            }
        }

        // Auto-size columns
        foreach (range('A', $lastHeaderCol) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'Results_';
        if ($option === 'single' && $students->count() > 0) {
            $student = $students->first();
            $filename .= $student->first_name . '_' . $student->last_name . '_';
        } elseif ($option === 'class' && $classID) {
            $class = ClassModel::find($classID);
            $filename .= ($class ? str_replace(' ', '_', $class->class_name) : 'Class') . '_';
        } elseif ($option === 'subclass' && $subclassID) {
            $subclass = Subclass::find($subclassID);
            $filename .= ($subclass ? str_replace(' ', '_', $subclass->subclass_name) : 'Subclass') . '_';
        } else {
            $filename .= 'All_Students_';
        }
        $filename .= ($typeFilter === 'exam' ? 'Exam' : 'Term_Report') . '_';
        $filename .= ($termFilter ? str_replace('_', '', $termFilter) . '_' : '') . $yearFilter . '.xlsx';

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    /**
     * Save term report definition
     */
    public function saveReportDefinition(Request $request)
    {
        $schoolID = Session::get('schoolID');
        if (!$schoolID) {
            return response()->json(['success' => false, 'message' => 'School ID not found in session']);
        }

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer',
            'term' => 'required|string',
            'exam_ids' => 'required|array',
            'exam_ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        try {
            TermReportDefinition::updateOrCreate(
                [
                    'schoolID' => $schoolID,
                    'year' => $request->year,
                    'term' => $request->term
                ],
                [
                    'exam_ids' => $request->exam_ids,
                    'created_by' => Session::get('teacherID') ?? auth()->id() ?? 1
                ]
            );

            return response()->json(['success' => true, 'message' => 'Report definition saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all report definitions for the school
     */
    public function getReportDefinitions(Request $request)
    {
        $schoolID = Session::get('schoolID');
        $year = $request->query('year');
        $term = $request->query('term');

        $query = TermReportDefinition::where('schoolID', $schoolID);

        if ($year) {
            $query->where('year', $year);
        }
        if ($term && $term !== '') {
            $query->where('term', $term);
        }

        $definitions = $query->orderBy('year', 'desc')
            ->orderBy('term', 'asc')
            ->get();

        // Get names for exams in each definition
        foreach ($definitions as $def) {
            if (!empty($def->exam_ids)) {
                $def->exam_names = Examination::whereIn('examID', $def->exam_ids)
                    ->pluck('exam_name')
                    ->toArray();
            } else {
                $def->exam_names = [];
            }
        }
            
        return response()->json(['success' => true, 'data' => $definitions]);
    }

    /**
     * Delete report definition
     */
    public function deleteReportDefinition($id)
    {
        $schoolID = Session::get('schoolID');
        TermReportDefinition::where('id', $id)
            ->where('schoolID', $schoolID)
            ->delete();
            
        return response()->json(['success' => true, 'message' => 'Definition deleted successfully']);
    }

    /**
     * Get exams for a specific term and year
     */
    public function getExamsForTerm(Request $request)
    {
        $schoolID = Session::get('schoolID');
        $term = $request->term;
        $year = $request->year;

        $exams = Examination::where('schoolID', $schoolID)
            ->where('year', $year)
            ->where('term', $term)
            ->orderBy('start_date', 'asc')
            ->get(['examID', 'exam_name']);

        return response()->json(['success' => true, 'data' => $exams]);
    }

    // ==========================================
    // CA Definition Methods
    // ==========================================

    public function getSchoolExamsForTermList(Request $request)
    {
        $schoolID = Session::get('schoolID');
        $year = $request->year;
        $term = $request->term;

        $exams = Examination::where('schoolID', $schoolID)
            ->where('year', $year)
            ->where('term', $term)
            ->where('exam_category', 'school_exams')
            ->orderBy('start_date', 'asc')
            ->get(['examID', 'exam_name']);

        return response()->json(['success' => true, 'data' => $exams]);
    }

    public function getTestsForTermList(Request $request)
    {
        $schoolID = Session::get('schoolID');
        $year = $request->year;
        $term = $request->term;

        $exams = Examination::where('schoolID', $schoolID)
            ->where('year', $year)
            ->where('term', $term)
            ->where('exam_category', 'test')
            ->orderBy('start_date', 'asc')
            ->get(['examID', 'exam_name', 'end_date']);

        return response()->json(['success' => true, 'data' => $exams]);
    }

    public function checkExamCaExists(Request $request)
    {
        $examID = $request->examID;
        $schoolID = Session::get('schoolID');

        $exists = CaDefinition::where('examID', $examID)->where('schoolID', $schoolID)->exists();

        return response()->json(['success' => true, 'exists' => $exists]);
    }

    public function saveCaDefinition(Request $request)
    {
        $schoolID = Session::get('schoolID');
        if (!$schoolID) return response()->json(['success' => false, 'message' => 'Session expired']);

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer',
            'term' => 'required|string',
            'examID' => 'required|integer',
            'test_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'All fields are required.']);
        }

        CaDefinition::updateOrCreate(
            [
                'schoolID' => $schoolID,
                'examID' => $request->examID
            ],
            [
                'year' => $request->year,
                'term' => $request->term,
                'test_ids' => $request->test_ids,
                'created_by' => Session::get('teacherID') ?? Session::get('staffID') ?? 0
            ]
        );

        return response()->json(['success' => true, 'message' => 'CA Definition saved successfully.']);
    }

    public function getCaDefinitions(Request $request)
    {
        $schoolID = Session::get('schoolID');
        $year = $request->year;
        $term = $request->term;

        $query = CaDefinition::where('schoolID', $schoolID)->with('mainExam');
        
        if ($year) $query->where('year', $year);
        if ($term) $query->where('term', $term);
        if ($request->examID) $query->where('examID', $request->examID);

        $defs = $query->orderBy('created_at', 'desc')->get()->map(function($def) {
            $testNames = Examination::whereIn('examID', $def->test_ids)->pluck('exam_name')->toArray();
            $def->test_names = $testNames;
            return $def;
        });

        return response()->json(['success' => true, 'data' => $defs]);
    }

    public function deleteCaDefinition($id)
    {
        $schoolID = Session::get('schoolID');
        $def = CaDefinition::where('id', $id)->where('schoolID', $schoolID)->first();

        if ($def) {
            $def->delete();
            return response()->json(['success' => true, 'message' => 'CA Definition deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'Definition not found.']);
    }
}




