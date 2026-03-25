<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Results - {{ $school->school_name ?? 'School' }}</title>
    <style>
        @page { margin: 12mm 14mm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1a1a1a;
            line-height: 1.4;
        }

        /* ── HEADER ─────────────────────────────── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .logo-cell { width: 75px; }
        .logo-cell img { max-width: 65px; max-height: 65px; }
        .school-name-cell { text-align: center; }
        .school-name { font-size: 16px; font-weight: bold; color: #940000; text-transform: uppercase; letter-spacing: 1px; }
        .school-sub  { font-size: 8px; color: #555; margin: 1px 0; }
        .header-line { border-bottom: 3px solid #940000; margin: 8px 0 10px 0; }

        /* ── TITLE BAND ──────────────────────────── */
        .title-band {
            background-color: #940000;
            color: #ffffff;
            text-align: center;
            padding: 7px 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* ── META BAR ───────────────────────────── */
        .meta-bar {
            background-color: #FFF5F5;
            border-left: 4px solid #940000;
            padding: 5px 8px;
            margin-bottom: 10px;
            font-size: 8px;
            color: #444;
        }

        /* ── STUDENT INFO ────────────────────────── */
        .student-info {
            background-color: #FFF5F5;
            border: 1px solid #e0cdcd;
            padding: 6px 8px;
            margin-bottom: 10px;
            font-size: 8.5px;
        }
        .s-label { color: #940000; font-weight: bold; }

        /* ── SECTION TITLE ───────────────────────── */
        .section-title {
            color: #940000;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            border-bottom: 1px solid #e0cdcd;
            padding-bottom: 2px;
            margin: 8px 0 4px 0;
        }

        /* ── TABLES ──────────────────────────────── */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8px; }
        table.data thead tr { background-color: #940000; color: #ffffff; }
        table.data th { padding: 5px 5px; text-align: left; font-weight: bold; border: 1px solid #b50000; }
        table.data td { padding: 4px 5px; border: 1px solid #e0cdcd; }
        table.data tbody tr.even { background-color: #FFF5F5; }
        .tc  { text-align: center; }
        .tr  { text-align: right; }
        .bold { font-weight: bold; }

        /* ── BADGES ──────────────────────────────── */
        .badge-red  { background-color: #940000; color: #fff; padding: 1px 5px; font-size: 7px; font-weight: bold; }
        .badge-pale { background-color: #FFF5F5; color: #940000; padding: 1px 5px; font-size: 7px; font-weight: bold; border: 1px solid #940000; }
        .badge-grn  { background-color: #155724; color: #fff; padding: 1px 5px; font-size: 7px; font-weight: bold; }

        /* ── GROUP HEADERS ───────────────────────── */
        .group-head { background-color: #940000; color: #fff; font-size: 9px; font-weight: bold; padding: 4px 7px; margin: 12px 0 0 0; }
        .sub-head   { background-color: #FFF5F5; color: #940000; font-size: 8.5px; font-weight: bold; border-left: 3px solid #940000; padding: 2px 6px; margin: 6px 0 3px 0; }

        /* ── RESULT SUMMARY BAR ──────────────────── */
        .result-bar { background-color: #FFF5F5; border-left: 3px solid #940000; padding: 3px 7px; margin-bottom: 6px; font-size: 8px; }

        /* ── SIGNATURE AND STAMP ─────────────────── */
        .sig-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .sig-table td { border: none; padding: 0; vertical-align: bottom; }
        .stamp-cell { width: 50%; text-align: center; vertical-align: middle; }
        .stamp-cell img { max-width: 125px; max-height: 125px; opacity: 0.85; }
        .sign-cell { width: 50%; text-align: center; padding-bottom: 5px; }
        .school-signature-img { height: 45px; margin-bottom: 4px; }
        .school-signature-img img { max-width: 170px; max-height: 45px; }
        .sig-line   { border-top: 1.5px solid #940000; width: 180px; margin: 0 auto; display: block; }
        .sig-label  { font-size: 8.5px; color: #940000; font-weight: bold; padding-top: 4px; display: block; text-transform: uppercase;}

        /* ── FOOTER ──────────────────────────────── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0; right: 0;
            text-align: center;
            font-size: 7.5px;
            color: #777;
            border-top: 1px solid #e0cdcd;
            padding-top: 3px;
        }
        .footer .brand { color: #940000; font-weight: bold; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td class="logo-cell">
            @if($school && $school->school_logo)
                <img src="{{ public_path($school->school_logo) }}" alt="Logo">
            @endif
        </td>
        <td class="school-name-cell">
            <div class="school-name">{{ $school->school_name ?? 'School' }}</div>
            @if($school)
                <div class="school-sub">{{ $school->address ?? '' }}</div>
                <div class="school-sub">Tel: {{ $school->phone ?? 'N/A' }}  |  Email: {{ $school->email ?? '' }}</div>
            @endif
        </td>
        <td style="width:75px;"></td>
    </tr>
</table>
<div class="header-line"></div>

{{-- TITLE BAND --}}
<div class="title-band">
    @if($option === 'single' && $students->count() > 0)
        @php $stu = $students->first(); @endphp
        {{ strtoupper(trim($stu->first_name . ' ' . ($stu->middle_name ?? '') . ' ' . $stu->last_name)) }}
        - {{ $title ?? ($filters['type'] === 'report' ? 'Term Report' : 'Exam Results') }}
    @else
        {{ $title ?? ($filters['type'] === 'report' ? 'Term Report' : 'Exam Results') }}
    @endif
</div>

{{-- META BAR --}}
<div class="meta-bar">
    <strong>Term:</strong> {{ $filters['term'] ? ucwords(str_replace('_', ' ', $filters['term'])) : 'All Terms' }} &nbsp;|&nbsp;
    <strong>Year:</strong> {{ $filters['year'] }} &nbsp;|&nbsp;
    <strong>Type:</strong> {{ $filters['type'] === 'exam' ? 'Exam Results' : 'Term Report' }}
    @if($filters['examID'])
        @php $exam = \App\Models\Examination::find($filters['examID']); @endphp
        &nbsp;|&nbsp; <strong>Exam:</strong> {{ $exam->exam_name ?? 'N/A' }}
    @endif
    @if($filters['class'])
        @php $cls = \App\Models\ClassModel::find($filters['class']); @endphp
        &nbsp;|&nbsp; <strong>Class:</strong> {{ $cls->class_name ?? 'N/A' }}
    @endif
    @if($filters['subclass'])
        @php $sub = \App\Models\Subclass::find($filters['subclass']); @endphp
        &nbsp;|&nbsp; <strong>Subclass:</strong> {{ $sub->subclass_name ?? 'N/A' }}
    @endif
    @if($filters['grade']) &nbsp;|&nbsp; <strong>Grade:</strong> {{ $filters['grade'] }} @endif
    @if($filters['gender']) &nbsp;|&nbsp; <strong>Gender:</strong> {{ $filters['gender'] }} @endif
    &nbsp;|&nbsp; <strong>Generated:</strong> {{ date('d/m/Y H:i') }}
</div>

{{-- ====================================================
     SINGLE STUDENT
==================================================== --}}
@if($option === 'single' && $students->count() > 0)
    @php $student = $students->first(); $result = $resultsData[$student->studentID] ?? null; @endphp

    <div class="student-info">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="border:none; width:50%;"><span class="s-label">Full Name:</span> {{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->last_name }}</td>
                <td style="border:none;"><span class="s-label">Admission No:</span> {{ $student->admission_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border:none;">
                    <span class="s-label">Class:</span>
                    @if($student->subclass && $student->subclass->class)
                        {{ $student->subclass->class->class_name }} / {{ $student->subclass->subclass_name }}
                    @elseif($student->oldSubclass && $student->oldSubclass->class)
                        {{ $student->oldSubclass->class->class_name }} / {{ $student->oldSubclass->subclass_name }} (History)
                    @else N/A @endif
                </td>
                <td style="border:none;"><span class="s-label">Gender:</span> {{ $student->gender ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if($result)
        @if(isset($detailedSingleData) && isset($detailedSingleData['subjects']) && count($detailedSingleData['subjects']) > 0)
            <div class="section-title">Detailed Performance Breakdown</div>
            <table class="data">
                <thead>
                    <tr>
                        <th>Subject</th>
                        @php 
                            // Determine extra columns from the first subject's exams array
                            $firstSubject = $detailedSingleData['subjects'][0];
                            $hasBreakdown = false;
                            $examHeaders = [];
                            if (isset($firstSubject['exams']) && count($firstSubject['exams']) > 0) {
                                $hasBreakdown = true;
                                foreach ($firstSubject['exams'] as $ex) {
                                    $examHeaders[] = $ex['exam_name'] ?? 'Exam';
                                }
                            }
                            // Sort headers to maintain consistency if needed (usually already sorted by start_date)
                        @endphp
                        
                        @if($hasBreakdown)
                            @foreach($examHeaders as $h)
                                <th class="tc">{{ $h }}</th>
                            @endforeach
                        @endif
                        <th class="tc">Average</th>
                        <th class="tc">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailedSingleData['subjects'] as $i => $subj)
                        <tr class="{{ $i % 2 == 0 ? '' : 'even' }}">
                            <td>{{ $subj['subject_name'] }}</td>
                            @if($hasBreakdown)
                                @foreach($examHeaders as $index => $hName)
                                    @php
                                        // Find the corresponding exam result (whether it is an associative array or indexed)
                                        $exResult = null;
                                        if (isset($subj['exams'])) {
                                            $examsArr = array_values($subj['exams']);
                                            $exResult = $examsArr[$index] ?? null;
                                        }
                                    @endphp
                                    <td class="tc">
                                        @if($exResult && isset($exResult['marks']))
                                            {{ number_format((float)$exResult['marks'], 0) }} 
                                            @if(isset($exResult['grade']))
                                                <small class="badge-pale" style="padding:0 2px; border:none; background:none;">({{ $exResult['grade'] }})</small>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                            <td class="tc bold">
                                {{ isset($subj['average']) ? number_format((float)$subj['average'], 0) : (isset($subj['marks']) ? number_format((float)$subj['marks'], 0) : '—') }}
                            </td>
                            <td class="tc"><span class="badge-red">{{ $subj['grade'] ?? 'N/A' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="result-bar">
                <strong>Total:</strong> {{ number_format($detailedSingleData['totalMarks'] ?? 0, 0) }} &nbsp;|&nbsp;
                <strong>Average:</strong> {{ number_format($detailedSingleData['averageMarks'] ?? 0, 0) }} &nbsp;|&nbsp;
                <strong>Grade:</strong> {{ $detailedSingleData['grade'] ?? 'N/A' }}
                @if(isset($detailedSingleData['division']))
                    &nbsp;|&nbsp; <strong>Division:</strong> {{ $detailedSingleData['division'] }}
                @endif
                @if(isset($detailedSingleData['position']))
                    &nbsp;|&nbsp; <strong>Position:</strong> {{ $detailedSingleData['position'] }} / {{ $detailedSingleData['totalStudentsCount'] ?? 0 }}
                @endif
            </div>

            @if(isset($detailedSingleData['remarks']))
            <div style="margin-top: 10px; border: 1px dashed #e0cdcd; padding: 6px; font-size: 8px;">
                <strong>Remarks:</strong> {{ $detailedSingleData['remarks'] }}
            </div>
            @endif

        @elseif($filters['type'] === 'report')
            <div class="section-title">Term Performance Summary</div>
            <table class="data">
                <thead>
                    <tr>
                        <th>Total Marks</th>
                        <th class="tc">Average (%)</th>
                        <th class="tc">Grade</th>
                        <th class="tc">Division</th>
                        <th class="tc">Position</th>
                        <th class="tc">Exams</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="bold">{{ number_format($result['total_marks'], 0) }}</td>
                        <td class="tc bold">{{ number_format($result['average_marks'], 0) }}</td>
                        <td class="tc"><span class="badge-pale">{{ $result['grade'] ?? 'N/A' }}</span></td>
                        <td class="tc"><span class="badge-red">{{ $result['division'] ?? 'N/A' }}</span></td>
                        <td class="tc"><span class="badge-grn">{{ $result['position'] ?? 'N/A' }}</span></td>
                        <td class="tc">{{ $result['exam_count'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            @foreach($result as $examResult)
                <div class="section-title">{{ $examResult['exam']->exam_name ?? 'N/A' }} - {{ $examResult['exam']->start_date ?? '' }}</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="tc">Marks</th>
                            <th class="tc">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examResult['subjects'] as $i => $subject)
                            <tr class="{{ $i % 2 == 0 ? '' : 'even' }}">
                                <td>{{ $subject['subject_name'] }}</td>
                                <td class="tc bold">{{ $subject['marks'] ?? 'N/A' }}</td>
                                <td class="tc"><span class="badge-pale">{{ $subject['grade'] ?? 'N/A' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="result-bar">
                    <strong>Total:</strong> {{ number_format($examResult['total_marks'], 0) }} &nbsp;|&nbsp;
                    <strong>Average:</strong> {{ number_format($examResult['average_marks'], 0) }} &nbsp;|&nbsp;
                    <strong>Grade:</strong> {{ $examResult['grade'] ?? 'N/A' }} &nbsp;|&nbsp;
                    <strong>Division:</strong> {{ $examResult['division'] ?? 'N/A' }}
                </div>
            @endforeach
        @endif
    @else
        <p style="color:#940000; font-style:italic; margin-top:10px;">No result data found for this student.</p>
    @endif

    <table class="sig-table">
        <tr>
            <td class="stamp-cell">
                @if($school && $school->school_stamp)
                    <img src="{{ public_path($school->school_stamp) }}" alt="Stamp">
                @endif
            </td>
            <td class="sign-cell">
                @if($school && $school->school_signature)
                    <div class="school-signature-img">
                        <img src="{{ public_path($school->school_signature) }}" alt="Signature">
                    </div>
                @else
                    <div style="height: 35px;"></div>
                @endif
                <div class="sig-line"></div>
                <span class="sig-label">Headmaster's Signature</span>
            </td>
        </tr>
    </table>

{{-- ====================================================
     CLASS / SUBCLASS / ALL STUDENTS
==================================================== --}}
@else
    @if($option === 'class' || $option === 'subclass')
        @php
            $groupedStudents = $students->groupBy(function($s) {
                if ($s->subclass && $s->subclass->class) return $s->subclass->class->class_name;
                if ($s->oldSubclass && $s->oldSubclass->class) return $s->oldSubclass->class->class_name;
                return 'Unknown';
            });
        @endphp

        @foreach($groupedStudents as $className => $classStudents)
            <div class="group-head" style="font-size: 11px;">{{ $className }} - PERFORMANCE OVERVIEW</div>
            
            @php
                // CALCULATE STATISTICS FOR THIS GROUP
                $sTotal = 0; $sMale = 0; $sFemale = 0; $sPass = 0;
                $sMarks = 0; $mMarks = 0; $fMarks = 0;
                $divs = ['I'=>['M'=>0,'F'=>0,'T'=>0], 'II'=>['M'=>0,'F'=>0,'T'=>0], 'III'=>['M'=>0,'F'=>0,'T'=>0], 'IV'=>['M'=>0,'F'=>0,'T'=>0], '0'=>['M'=>0,'F'=>0,'T'=>0]];
                $top = [];
                $subjMap = [];
                
                foreach($classStudents as $s) {
                    if(!isset($resultsData[$s->studentID])) continue;
                    $r = $resultsData[$s->studentID];
                    $res = ($filters['type'] === 'report') ? $r : (is_array($r) && !empty($r) ? $r[0] : null);
                    if(!$res) continue;
                    
                    $sTotal++;
                    $marks = (float)($res['average_marks'] ?? 0);
                    $sMarks += $marks;
                    
                    $isMale = strtolower($s->gender ?? '') === 'male';
                    if($isMale) { $sMale++; $mMarks += $marks; } else { $sFemale++; $fMarks += $marks; }
                    
                    $dv = $res['division'] ?? '0';
                    $dvSplit = explode('.', $dv);
                    $dvCode = preg_replace('/[^IV0]/', '', $dvSplit[0]);
                    if($dvCode == '') $dvCode = '0';
                    if(!isset($divs[$dvCode])) $dvCode = '0';
                    
                    $divs[$dvCode][$isMale ? 'M' : 'F']++;
                    $divs[$dvCode]['T']++;
                    
                    if(!in_array($dvCode, ['IV', '0']) && $dvCode !== '') $sPass++;

                    $subjsList = $res['subjects'] ?? [];
                    if(is_array($subjsList)) {
                        foreach($subjsList as $subItem) {
                            $sn = $subItem['subject_name'] ?? 'Unknown';
                            if(!isset($subjMap[$sn])) {
                                $subjMap[$sn] = array_fill_keys(['A','B','C','D','E','F'], ['M'=>0,'F'=>0,'T'=>0]);
                            }
                            $gr = strtoupper($subItem['grade'] ?? 'F');
                            if(!isset($subjMap[$sn][$gr])) $gr = 'F';
                            $subjMap[$sn][$gr][$isMale ? 'M' : 'F']++;
                            $subjMap[$sn][$gr]['T']++;
                        }
                    }
                    
                    $top[] = [
                        'name' => trim($s->first_name . ' ' . ($s->middle_name ?? '') . ' ' . $s->last_name),
                        'div' => $dv,
                        'mark' => $marks
                    ];
                }
                usort($top, function($a, $b) { return $b['mark'] <=> $a['mark']; });
                $top5 = array_slice($top, 0, 5);
                $pRate = $sTotal > 0 ? ($sPass / $sTotal) * 100 : 0;
                $avgMarks = $sTotal > 0 ? $sMarks / $sTotal : 0;
                $mAvg = $sMale > 0 ? $mMarks / $sMale : 0;
                $fAvg = $sFemale > 0 ? $fMarks / $sFemale : 0;
            @endphp

            {{-- ONLY RENDER STATS IF WE HAVE STUDENTS WITH RESULTS --}}
            @if($sTotal > 0)
                <div class="sub-head">Overview Statistics</div>
                <table class="data" style="margin-bottom: 12px;">
                    <tbody>
                        <tr><td style="width: 50%;">Total Students</td><td class="tc bold">{{ $sTotal }}</td></tr>
                        <tr class="even"><td>Male</td><td class="tc">{{ $sMale }}</td></tr>
                        <tr><td>Female</td><td class="tc">{{ $sFemale }}</td></tr>
                        <tr class="even"><td>Pass Rate</td><td class="tc bold" style="color:#155724;">{{ number_format($pRate, 0) }}%</td></tr>
                        <tr><td>Class Average</td><td class="tc bold">{{ number_format($avgMarks, 0) }} marks</td></tr>
                        <tr class="even"><td>Male Average</td><td class="tc">{{ number_format($mAvg, 0) }}</td></tr>
                        <tr><td>Female Average</td><td class="tc">{{ number_format($fAvg, 0) }}</td></tr>
                        <tr class="even"><td>Fail Rate</td><td class="tc bold" style="color:#721c24;">{{ number_format((100 - $pRate), 1) }}%</td></tr>
                        @php
                            $rmk = ''; $cmt = '';
                            if ($avgMarks >= 75) { $rmk = 'Excellent'; $cmt = 'The class has performed excellently with an outstanding average score. Keep up the great work!'; }
                            elseif ($avgMarks >= 65) { $rmk = 'Very Good'; $cmt = 'Good performance, push a little harder to reach excellent.'; }
                            elseif ($avgMarks >= 45) { $rmk = 'Good'; $cmt = 'Average performance, more effort is needed to improve scores.'; }
                            elseif ($avgMarks >= 30) { $rmk = 'Satisfactory'; $cmt = 'Below average performance, students need serious effort.'; }
                            else { $rmk = 'Fail'; $cmt = 'Poor performance, urgent academic intervention is required.'; }
                        @endphp
                        <tr><td colspan="2">
                            <strong>Performance Remark:</strong> {{ $rmk }}<br>
                            <strong>Performance Comment:</strong><br><span style="font-size: 8px;">{{ $cmt }}</span>
                        </td></tr>
                    </tbody>
                </table>

                <div class="sub-head">Division Distribution</div>
                <table class="data" style="margin-bottom: 12px;">
                    <thead>
                        <tr><th style="width: 40%;">Div</th><th class="tc" style="width: 20%;">M</th><th class="tc" style="width: 20%;">F</th><th class="tc" style="width: 20%;">Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach(['I','II','III','IV','0'] as $i => $dCode)
                            <tr class="{{ $i % 2 == 0 ? '' : 'even' }}">
                                <td>Division {{ $dCode }}</td>
                                <td class="tc">{{ $divs[$dCode]['M'] }}</td>
                                <td class="tc">{{ $divs[$dCode]['F'] }}</td>
                                <td class="tc bold">{{ $divs[$dCode]['T'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="sub-head">Top 5 Students</div>
                <table class="data" style="margin-bottom: 12px;">
                    <thead>
                        <tr><th style="width:10%;" class="tc">#</th><th style="width: 60%;">Student Name</th><th class="tc" style="width: 30%;">Div/Gr</th></tr>
                    </thead>
                    <tbody>
                        @foreach($top5 as $i => $t)
                            <tr class="{{ $i % 2 == 0 ? '' : 'even' }}">
                                <td class="tc">{{ $i + 1 }}</td>
                                <td>{{ $t['name'] }}</td>
                                <td class="tc bold">{{ $t['div'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="sub-head" style="margin-top:2px;">Subject Performance Statistics</div>
                <table class="data" style="margin-bottom:15px;">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            @foreach(['A','B','C','D','E','F'] as $gr)
                            <th class="tc">{{ $gr }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php $si = 0; @endphp
                        @foreach($subjMap as $sName => $sGrades)
                            <tr class="{{ $si % 2 == 0 ? '' : 'even' }}">
                                <td class="bold">{{ $sName }}</td>
                                @foreach(['A','B','C','D','E','F'] as $gr)
                                <td class="tc" style="font-size:7.5px;">
                                    <strong>{{ $sGrades[$gr]['T'] }}</strong><br>
                                    <span style="color:#666;">M:{{ $sGrades[$gr]['M'] }}, F:{{ $sGrades[$gr]['F'] }}</span>
                                </td>
                                @endforeach
                            </tr>
                            @php $si++; @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="group-head" style="font-size:10px;">STUDENT RESULTS DETAILED LIST</div>

            @php
                // Sort students by average_marks descending to get rank order
                $classStudentsSorted = $classStudents->sortByDesc(function($s) use ($resultsData, $filters) {
                    if(!isset($resultsData[$s->studentID])) return -1;
                    $r = $resultsData[$s->studentID];
                    $res = ($filters['type'] === 'report') ? $r : (is_array($r) && !empty($r) ? $r[0] : null);
                    return floatval($res['average_marks'] ?? 0);
                });

                // Helper block for rendering the table
                $renderTableItems = function($studentsList, $resultsData, $filters) {
                    $html = '';
                    $pos = 1;
                    foreach($studentsList as $index => $student) {
                        if(isset($resultsData[$student->studentID])) {
                            $r = $resultsData[$student->studentID];
                            $res = ($filters['type'] === 'report') ? $r : (is_array($r) && !empty($r) ? $r[0] : null);
                            if(!$res) continue;

                            $cn = '';
                            if ($student->subclass && $student->subclass->class) {
                                $cn = $student->subclass->class->class_name;
                            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                $cn = $student->oldSubclass->class->class_name;
                            }

                            $divstr = $res['division'] ?? ($res['grade'] ?? 'N/A');

                            $subjStrs = [];
                            if(isset($res['subjects']) && is_array($res['subjects'])) {
                                foreach($res['subjects'] as $subj) {
                                    $sn = $subj['subject_name'] ?? 'U';
                                    $mk = number_format(floatval($subj['marks'] ?? 0), 0);
                                    $gr = $subj['grade'] ?? 'F';
                                    $subjStrs[] = "{$sn}-{$mk}-{$gr}";
                                }
                            }
                            $subText = implode(', ', $subjStrs);

                            $cls = ($pos % 2 == 0) ? 'even' : '';
                            $name = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);

                            $html .= '<tr class="'.$cls.'">
                                <td class="tc">'.$pos.'</td>
                                <td class="bold" style="font-size:7.5px;">'.$name.'</td>
                                <td style="font-size:7px;">'.$cn.'</td>
                                <td style="font-size:7px; line-height:1.2;">'.$subText.'</td>
                                <td class="tc bold">'.$divstr.'</td>
                            </tr>';
                            $pos++;
                        }
                    }
                    return $html;
                };
            @endphp

            @if($option === 'subclass')
                @php
                    $subclassGroups = $classStudentsSorted->groupBy(function($s) {
                        if ($s->subclass) return $s->subclass->subclass_name;
                        if ($s->oldSubclass) return $s->oldSubclass->subclass_name;
                        return 'Unknown';
                    });
                @endphp
                @foreach($subclassGroups as $subclassName => $subclassStudents)
                    <div class="sub-head">{{ $subclassName }}</div>
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width:5%" class="tc">Pos</th>
                                <th style="width:18%">Student Name</th>
                                <th style="width:12%">Class</th>
                                <th style="width:60%">Subject</th>
                                <th style="width:5%" class="tc">Div/Gr</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!! $renderTableItems($subclassStudents, $resultsData, $filters) !!}
                        </tbody>
                    </table>
                @endforeach
            @else
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:5%" class="tc">Pos</th>
                            <th style="width:18%">Student Name</th>
                            <th style="width:12%">Class</th>
                            <th style="width:60%">Subject</th>
                            <th style="width:5%" class="tc">Div/Gr</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $renderTableItems($classStudentsSorted, $resultsData, $filters) !!}
                    </tbody>
                </table>
            @endif


            <table class="sig-table">
                <tr>
                    <td class="stamp-cell">
                        @if($school && $school->school_stamp)
                            <img src="{{ public_path($school->school_stamp) }}" alt="Stamp">
                        @endif
                    </td>
                    <td class="sign-cell">
                        @if($school && $school->school_signature)
                            <div class="school-signature-img">
                                <img src="{{ public_path($school->school_signature) }}" alt="Signature">
                            </div>
                        @else
                            <div style="height: 35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <span class="sig-label">Headmaster's Signature</span>
                    </td>
                </tr>
            </table>
        @endforeach
    @else
        {{-- ALL STUDENTS FLAT TABLE --}}
        <div class="group-head" style="font-size:11px;">ALL STUDENTS RESULTS LIST</div>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:5%" class="tc">Pos</th>
                    <th style="width:18%">Student Name</th>
                    <th style="width:12%">Class</th>
                    <th style="width:60%">Subject</th>
                    <th style="width:5%" class="tc">Div/Gr</th>
                </tr>
            </thead>
            <tbody>
                @php $posCount = 1; @endphp
                @foreach($students as $index => $student)
                    @if(isset($resultsData[$student->studentID]))
                        @php
                            $r = $resultsData[$student->studentID];
                            $res = ($filters['type'] === 'report') ? $r : (is_array($r) && !empty($r) ? $r[0] : null);
                            if(!$res) continue;

                            $cn = '';
                            if ($student->subclass && $student->subclass->class) {
                                $cn = $student->subclass->class->class_name;
                            } elseif ($student->oldSubclass && $student->oldSubclass->class) {
                                $cn = $student->oldSubclass->class->class_name . ' (H)';
                            }

                            $divstr = $res['division'] ?? ($res['grade'] ?? 'N/A');

                            $subjStrs = [];
                            if(isset($res['subjects']) && is_array($res['subjects'])) {
                                foreach($res['subjects'] as $subj) {
                                    $sn = $subj['subject_name'] ?? 'U';
                                    $mk = number_format(floatval($subj['marks'] ?? 0), 0);
                                    $gr = $subj['grade'] ?? 'F';
                                    $subjStrs[] = "{$sn}-{$mk}-{$gr}";
                                }
                            }
                            $subText = implode(', ', $subjStrs);
                            $cls = ($posCount % 2 == 0) ? 'even' : '';
                            $name = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);
                        @endphp
                        <tr class="{{ $cls }}">
                            <td class="tc">{{ $posCount }}</td>
                            <td class="bold" style="font-size:7.5px;">{{ $name }}</td>
                            <td style="font-size:7px;">{{ $cn }}</td>
                            <td style="font-size:7px; line-height:1.2;">{{ $subText }}</td>
                            <td class="tc bold">{{ $divstr }}</td>
                        </tr>
                        @php $posCount++; @endphp
                    @endif
                @endforeach
            </tbody>
        </table>

        <table class="sig-table">
            <tr>
                <td class="stamp-cell">
                    @if($school && $school->school_stamp)
                        <img src="{{ public_path($school->school_stamp) }}" alt="Stamp">
                    @endif
                </td>
                <td class="sign-cell">
                    @if($school && $school->school_signature)
                        <div class="school-signature-img">
                            <img src="{{ public_path($school->school_signature) }}" alt="Signature">
                        </div>
                    @else
                        <div style="height: 35px;"></div>
                    @endif
                    <div class="sig-line"></div>
                    <span class="sig-label">Headmaster's Signature</span>
                </td>
            </tr>
        </table>
    @endif
@endif
<div class="footer">
    {{ $school->school_name ?? 'School' }} &nbsp;|&nbsp; Generated: {{ date('d/m/Y H:i:s') }}
    &nbsp; &mdash; &nbsp; <span class="brand">Powered by EmCa Technologies LTD</span>
</div>

</body>
</html>
