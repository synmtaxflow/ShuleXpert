<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #940000; padding-bottom: 10px; }
        .school-name { font-size: 18px; font-weight: bold; color: #940000; text-transform: uppercase; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 10px; background: #f8f9fa; padding: 5px; }

        .info-section { margin-bottom: 20px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #555; width: 25%; }

        .analysis-card { margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .subject-header { border-bottom: 2px solid #940000; padding-bottom: 5px; margin-bottom: 15px; }
        .subject-title { font-size: 14px; font-weight: bold; color: #940000; }
        .teacher-info { font-style: italic; color: #666; font-size: 10px; }

        .stats-grid { width: 100%; margin-bottom: 15px; }
        .stats-grid td { width: 20%; text-align: center; padding: 10px; border: 1px solid #eee; }
        .stat-val { font-size: 16px; font-weight: bold; color: #333; }
        .stat-label { font-size: 9px; color: #777; text-transform: uppercase; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data-table th, table.data-table td { border: 1px solid #eee; padding: 6px; text-align: left; }
        table.data-table th { background-color: #f8f9fa; font-weight: bold; color: #940000; }

        .bar-container { background-color: #eee; width: 100%; height: 10px; border-radius: 5px; margin-top: 5px; }
        .bar-fill { height: 100%; border-radius: 5px; }
        .bar-pass { background-color: #28a745; }
        .bar-fail { background-color: #dc3545; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }

        /* ── SIGNATURE AND STAMP ─────────────────── */
        .sig-table { width: 100%; margin-top: 30px; border-collapse: collapse; page-break-inside: avoid; }
        .sig-table td { border: none; padding: 0; vertical-align: bottom; }
        .stamp-cell { width: 50%; text-align: center; vertical-align: middle; }
        .stamp-cell img { max-width: 120px; max-height: 120px; opacity: 0.85; }
        .sign-cell { width: 45%; text-align: center; padding-bottom: 5px; }
        .school-signature-img { height: 40px; margin-bottom: 4px; }
        .school-signature-img img { max-width: 150px; max-height: 40px; }
        .sig-line   { border-top: 1.5px solid #940000; width: 160px; margin: 0 auto; display: block; }
        .sig-label  { font-size: 8.5px; color: #940000; font-weight: bold; padding-top: 4px; display: block; text-transform: uppercase;}

        .summary-box { background: #fdf2f2; border: 1px solid #fbd5d5; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .summary-title { font-weight: bold; color: #940000; margin-bottom: 5px; border-bottom: 1px solid #fbd5d5; }

        .best-worst { width: 100%; margin-top: 10px; }
        .best-worst td { padding: 5px; border: 1px solid #eee; width: 50%; }
        .best-label { color: #28a745; font-weight: bold; }
        .worst-label { color: #dc3545; font-weight: bold; }

        /* ── CHART IMAGES (QUICKCHART) ─────────────────── */
        .charts-row { width: 100%; margin: 15px 0; clear: both; }
        .chart-wrapper { width: 100%; margin-bottom: 20px; text-align: center; }
        .chart-img { width: 100%; max-width: 650px; height: auto; border: 1px solid #f1f1f1; border-radius: 4px; padding: 10px; background: #fff; }

        /* ── WATERMARK ─────────────────── */
        .watermark {
            position: fixed;
            top: 45%;
            left: 5%;
            width: 100%;
            text-align: center;
            opacity: 0.05;
            transform: rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            color: #000;
            z-index: 1000;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="watermark">ShuleXpert</div>
    @php
        if (!function_exists('getSmartBase64')) {
            function getSmartBase64($path) {
                if (!$path) return null;
                $path = ltrim($path, '/');
                $possibilities = [
                    public_path($path),
                    public_path('uploads/' . $path),
                    base_path('../public_html/' . $path),
                    base_path('../public_html/uploads/' . $path),
                    base_path($path),
                    base_path('public/' . $path),
                ];
                foreach ($possibilities as $fullPath) {
                    if (@file_exists($fullPath) && is_file($fullPath)) {
                        try {
                            $data = base64_encode(file_get_contents($fullPath));
                            $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                            return 'data:image/' . ($ext ?: 'png') . ';base64,' . $data;
                        } catch (\Exception $e) {}
                    }
                }
                return null;
            }
        }
        $schoolLogoBase64  = getSmartBase64($school->school_logo ?? '');
        $schoolStampBase64 = getSmartBase64($school->school_stamp ?? '');
        $schoolSignBase64  = getSmartBase64($school->school_signature ?? '');
    @endphp

    <div class="header">
        @if($schoolLogoBase64)
            <img src="{{ $schoolLogoBase64 }}" style="max-height: 60px; margin-bottom: 5px;">
        @endif
        <div class="school-name">{{ $school->school_name ?? 'School' }}</div>
        <div>{{ $school->address ?? '' }}</div>
        <div>Phone: {{ $school->phone ?? '' }} | Email: {{ $school->email ?? '' }}</div>
        <div class="report-title">SUBJECT ANALYSIS REPORT</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Examination:</td>
                <td>{{ $selectedExam->exam_name }}</td>
                <td class="label">Academic Year:</td>
                <td>{{ $selectedExam->year }}</td>
            </tr>
            <tr>
                <td class="label">Term:</td>
                <td>{{ ucfirst(str_replace('_', ' ', $selectedExam->term)) }}</td>
                <td class="label">Date Generated:</td>
                <td>{{ date('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>

    @foreach($groupedAnalysis as $classDisplay => $subjects)
        <h3 style="color: #940000; border-bottom: 1px solid #940000; padding-bottom: 5px;">Class: {{ $classDisplay }}</h3>
        
        @foreach($subjects as $subject)
            <div class="analysis-card">
                <div class="subject-header">
                    <div class="subject-title">{{ $subject['subject_name'] }}</div>
                    <div class="teacher-info">Teacher: {{ $subject['teacher'] ? $subject['teacher']->first_name . ' ' . $subject['teacher']->last_name : 'N/A' }}</div>
                </div>

                <table class="stats-grid">
                    <tr>
                        <td>
                            <div class="stat-val">{{ $subject['overall_stats']['answered'] }}</div>
                            <div class="stat-label">Total Sat</div>
                        </td>
                        <td>
                            <div class="stat-val" style="color: #28a745;">{{ $subject['overall_stats']['pass'] }}</div>
                            <div class="stat-label">Passed</div>
                        </td>
                        <td>
                            <div class="stat-val" style="color: #dc3545;">{{ $subject['overall_stats']['fail'] }}</div>
                            <div class="stat-label">Failed</div>
                        </td>
                        <td>
                            <div class="stat-val" style="color: #28a745;">{{ $subject['overall_stats']['pass_rate'] }}%</div>
                            <div class="stat-label">Pass Rate</div>
                        </td>
                        <td>
                            <div class="stat-val" style="color: #dc3545;">{{ $subject['overall_stats']['fail_rate'] }}%</div>
                            <div class="stat-label">Fail Rate</div>
                        </td>
                    </tr>
                </table>

                <div class="summary-box">
                    <div class="summary-title">Performance Summary</div>
                    <p style="margin: 0;">This subject has an overall remark of <strong>{{ $subject['overall_stats']['remark'] }}</strong> with a <strong>{{ $subject['overall_stats']['pass_rate'] }}%</strong> success rate.</p>
                </div>

                @if(!empty($subject['question_stats']))
                    <div class="summary-title" style="font-size: 11px; margin-top: 10px;">Question Performance Analysis</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Qn</th>
                                <th>Total Mark</th>
                                <th>Avg Scored</th>
                                <th>Success %</th>
                                <th>Performance Bar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject['question_stats'] as $stat)
                                <tr>
                                    <td>Qn {{ $stat['question']->question_number }}{{ $stat['question']->question_description ? ': ' . $stat['question']->question_description : '' }}</td>
                                    <td>{{ $stat['question']->marks }}</td>
                                    <td>{{ $stat['average'] ?? '0' }}</td>
                                    <td>{{ $stat['percent'] ?? '0' }}%</td>
                                    <td style="width: 150px;">
                                        <div class="bar-container">
                                            <div class="bar-fill {{ ($stat['percent'] ?? 0) >= 50 ? 'bar-pass' : 'bar-fail' }}" 
                                                 style="width: {{ $stat['percent'] ?? 0 }}%;"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="charts-row">
                        @php
                            $labels = [];
                            $passData = [];
                            $failData = [];
                            foreach($subject['question_stats'] as $stat) {
                                $labels[] = 'Q'.$stat['question']->question_number;
                                $passVal = $stat['percent'] ?? 0;
                                $passData[] = round($passVal, 0);
                                $failData[] = round(max(0, 100 - $passVal), 0);
                            }
                            
                            $baseUrl = "http://quickchart.io/chart?w=650&h=300&bkg=transparent&c=";
                            
                            // Line Chart config with % suffix in datalabels
                            $lineConfig = [
                                'type' => 'line',
                                'data' => [
                                    'labels' => $labels,
                                    'datasets' => [
                                        [
                                            'label' => 'Pass %',
                                            'data' => $passData,
                                            'borderColor' => '#28a745',
                                            'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                                            'fill' => true,
                                            'datalabels' => [
                                                'display' => true,
                                                'align' => 'top',
                                                'anchor' => 'end',
                                                'font' => ['size' => 10, 'weight' => 'bold'],
                                                'formatter' => ' (v) => v + "%" '
                                            ]
                                        ],
                                        [
                                            'label' => 'Fail %',
                                            'data' => $failData,
                                            'borderColor' => '#dc3545',
                                            'backgroundColor' => 'rgba(220, 53, 69, 0.1)',
                                            'fill' => true,
                                            'datalabels' => [
                                                'display' => true,
                                                'align' => 'bottom',
                                                'anchor' => 'start',
                                                'font' => ['size' => 10, 'weight' => 'bold'],
                                                'formatter' => ' (v) => v + "%" '
                                            ]
                                        ]
                                    ]
                                ],
                                'options' => [
                                    'title' => ['display' => true, 'text' => 'Question Performance Trend (Pass% vs Fail%)'],
                                    'scales' => [
                                        'yAxes' => [['ticks' => ['min' => 0, 'max' => 100, 'stepSize' => 20]]]
                                    ]
                                ]
                            ];

                            $lineUrl = $baseUrl . urlencode(json_encode($lineConfig));
                        @endphp

                        <div class="chart-wrapper">
                            <img src="{{ $lineUrl }}" class="chart-img">
                        </div>

                        @if(!empty($subject['optional_selections']))
                            @php
                                $optLabels = [];
                                $optData = [];
                                $allOpt = collect($subject['optional_selections'])->sortBy('question.question_number');
                                $totalAns = max(1, $subject['overall_stats']['answered']);
                                foreach($allOpt as $opt) {
                                    $optLabels[] = 'Q'.$opt['question']->question_number;
                                    $optData[] = round(($opt['count'] / $totalAns) * 100, 0);
                                }

                                $barConfig = [
                                    'type' => 'bar',
                                    'data' => [
                                        'labels' => $optLabels,
                                        'datasets' => [[
                                            'label' => 'Selection %',
                                            'data' => $optData,
                                            'backgroundColor' => '#940000',
                                            'datalabels' => [
                                                'display' => true,
                                                'anchor' => 'end',
                                                'align' => 'top',
                                                'color' => '#000',
                                                'font' => ['size' => 10, 'weight' => 'bold'],
                                                'formatter' => ' (v) => v + "%" '
                                            ]
                                        ]]
                                    ],
                                    'options' => [
                                        'title' => ['display' => true, 'text' => 'Optional Question Selections (%)'],
                                        'scales' => [
                                            'yAxes' => [['ticks' => ['min' => 0, 'max' => 100]]]
                                        ]
                                    ]
                                ];
                                $barUrl = $baseUrl . urlencode(json_encode($barConfig));
                            @endphp
                            <div class="chart-wrapper">
                                <img src="{{ $barUrl }}" class="chart-img">
                            </div>
                        @endif
                    </div>
                    <div style="clear: both; margin-bottom: 20px;"></div>

                    <table class="best-worst">
                        <tr>
                            <td>
                                <div class="best-label">Best Performed Question</div>
                                @if($subject['best_question'])
                                    <div>Question {{ $subject['best_question']['question']->question_number }}{{ $subject['best_question']['question']->question_description ? ': ' . $subject['best_question']['question']->question_description : '' }} ({{ $subject['best_question']['percent'] }}%)</div>
                                @else
                                    <div>N/A</div>
                                @endif
                            </td>
                            <td>
                                <div class="worst-label">Worst Performed Question</div>
                                @if($subject['worst_question'])
                                    <div>Question {{ $subject['worst_question']['question']->question_number }}{{ $subject['worst_question']['question']->question_description ? ': ' . $subject['worst_question']['question']->question_description : '' }} ({{ $subject['worst_question']['percent'] }}%)</div>
                                @else
                                    <div>N/A</div>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if(!empty($subject['optional_selections']))
                        <div class="summary-title" style="font-size: 11px; margin-top: 15px;">Optional Question Selections</div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Selected By</th>
                                    <th>Selection %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subject['optional_selections'] as $opt)
                                    @php
                                        $totalStudents = max(1, $subject['overall_stats']['answered']);
                                        $optPercent = round(($opt['count'] / $totalStudents) * 100, 1);
                                    @endphp
                                    <tr>
                                        <td>Qn {{ $opt['question']->question_number }}{{ $opt['question']->question_description ? ': ' . $opt['question']->question_description : '' }}</td>
                                        <td>{{ $opt['count'] }} Students</td>
                                        <td>{{ $optPercent }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
            </div>
        @endforeach
    @endforeach

    <table class="sig-table">
        <tr>
            <td class="stamp-cell">
                @if($schoolStampBase64)
                    <img src="{{ $schoolStampBase64 }}">
                    <div style="font-size: 9px; color: #777; margin-top: 5px;">SCHOOL OFFICIAL STAMP</div>
                @endif
            </td>
            <td class="sign-cell">
                <div class="school-signature-img">
                    @if($schoolSignBase64)
                        <img src="{{ $schoolSignBase64 }}">
                    @endif
                </div>
                <span class="sig-line"></span>
                <span class="sig-label">HEADMASTER'S SIGNATURE</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated by ShuleXpert (School Management System), Powered By EmCa Techonology &copy; {{ date('Y') }}
    </div>
</body>
</html>
