<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Duty Report - {{ $report->report_date->format('d/m/Y') }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #940000; padding-bottom: 10px; }
        .school-name { font-size: 18px; font-weight: bold; color: #940000; text-transform: uppercase; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 10px; background: #f8f9fa; padding: 5px; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .label { font-weight: bold; color: #555; width: 15%; }
        .value { border-bottom: 1px dotted #ccc; width: 35%; }

        table.attendance { width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: center; }
        table.attendance th, table.attendance td { border: 1px solid #ddd; padding: 4px; }
        table.attendance th { background-color: #f2f2f2; font-size: 9px; }
        .class-name { text-align: left; font-weight: bold; background: #fafafa; }

        .observations-grid { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .observations-grid td { vertical-align: top; padding: 10px; border: 1px solid #eee; width: 50%; }
        .obs-title { font-weight: bold; color: #940000; margin-bottom: 5px; border-bottom: 1px solid #eee; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
        
        /* ── SIGNATURE AND STAMP ─────────────────── */
        .sig-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .sig-table td { border: none; padding: 0; vertical-align: bottom; }
        .stamp-cell { width: 50%; text-align: center; vertical-align: middle; }
        .stamp-cell img { max-width: 125px; max-height: 125px; opacity: 0.85; }
        .sign-cell { width: 45%; text-align: center; padding-bottom: 5px; }
        .school-signature-img { height: 45px; margin-bottom: 4px; }
        .school-signature-img img { max-width: 170px; max-height: 45px; }
        .sig-line   { border-top: 1.5px solid #940000; width: 180px; margin: 0 auto; display: block; }
        .sig-label  { font-size: 8.5px; color: #940000; font-weight: bold; padding-top: 4px; display: block; text-transform: uppercase;}
        
        .status-stamp { position: absolute; top: 100px; right: 50px; border: 3px solid #ccc; padding: 10px; font-size: 20px; font-weight: bold; color: #ccc; transform: rotate(-15deg); text-transform: uppercase; border-radius: 5px; opacity: 0.3; }
        .status-approved { border-color: green; color: green; }
        .status-sent { border-color: blue; color: blue; }

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
        /**
         * Smart Image Resolver for DomPDF
         * Checks multiple possible server locations (cPanel vs Local)
         */
        if (!function_exists('getSmartBase64')) {
            function getSmartBase64($path) {
                if (!$path) return null;
                
                $path = ltrim($path, '/');
                $base = base_path();
                $parent = dirname($base);
                
                $possibilities = [
                    public_path($path),                             // Standard Laravel Public
                    public_path('uploads/' . $path),                // Standard Laravel Public Uploads
                    $parent . '/public_html/' . $path,              // cPanel / Shared Hosting
                    $parent . '/public_html/uploads/' . $path,      // cPanel / Shared Hosting Uploads
                ];
                
                if (isset($_SERVER['DOCUMENT_ROOT'])) {
                    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
                    $possibilities[] = $docRoot . '/' . $path;
                    $possibilities[] = $docRoot . '/uploads/' . $path;
                }
                
                $possibilities[] = base_path($path);
                $possibilities[] = base_path('public/' . $path);
                $possibilities[] = base_path('../public_html/' . $path);
                
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
        <div class="school-name">{{ $school->school_name ?? 'School Report' }}</div>
        <div>{{ $school->address ?? '' }}</div>
        <div>Phone: {{ $school->phone ?? 'N/A' }} | Email: {{ $school->email ?? 'N/A' }}</div>
        <div class="report-title">DAILY ATTENDANCE & DUTY REPORT</div>
    </div>
    
    @if($report->status == 'Approved')
        @if($schoolStampBase64)
            <div class="status-stamp" style="border:none; opacity: 0.6; top: 80px; right: 40px; transform: rotate(-10deg);">
                <img src="{{ $schoolStampBase64 }}" style="max-height: 120px; max-width: 120px;">
            </div>
        @else
            <div class="status-stamp status-approved">APPROVED</div>
        @endif
    @elseif($report->status == 'Sent')
        <div class="status-stamp status-sent">SENT</div>
    @endif

    <table class="info-table">
        <tr>
            <td class="label">Teacher:</td>
            <td class="value">{{ $teacher?->first_name ?? 'N/A' }} {{ $teacher?->last_name ?? '' }}</td>
            <td class="label">Date:</td>
            <td class="value">{{ $report->report_date->format('l, d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status:</td>
            <td class="value">{{ $report->status }}</td>
            <td class="label">Attendance:</td>
            <td class="value">{{ $report->attendance_percentage }}%</td>
        </tr>
    </table>

    <table class="attendance">
        <thead>
            <tr>
                <th rowspan="2">CLASS</th>
                <th colspan="3">REGISTERED</th>
                <th colspan="3">PRESENT</th>
                <th colspan="3">ABSENT</th>
                <th colspan="3">NEW COMERS</th>
                <th colspan="3">PERMISSION</th>
                <th colspan="3">SICK</th>
            </tr>
            <tr>
                @for($i=0; $i<6; $i++)
                    <th>B</th><th>G</th><th>T</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php $attData = $report->attendance_data; @endphp
            @foreach($classes as $cls)
                @php $c = $attData[$cls->classID] ?? null; @endphp
                <tr>
                    <td class="class-name">{{ $cls->class_name }}</td>
                    @if($c)
                        <td>{{ $c['reg_b'] }}</td><td>{{ $c['reg_g'] }}</td><td>{{ $c['reg_b'] + $c['reg_g'] }}</td>
                        <td>{{ $c['pres_b'] }}</td><td>{{ $c['pres_g'] }}</td><td>{{ $c['pres_b'] + $c['pres_g'] }}</td>
                        <td>{{ $c['abs_b'] }}</td><td>{{ $c['abs_g'] }}</td><td>{{ $c['abs_b'] + $c['abs_g'] }}</td>
                        <td>{{ $c['new_b'] }}</td><td>{{ $c['new_g'] }}</td><td>{{ $c['new_b'] + $c['new_g'] }}</td>
                        <td>{{ $c['perm_b'] }}</td><td>{{ $c['perm_g'] }}</td><td>{{ $c['perm_b'] + $c['perm_g'] }}</td>
                        <td>{{ $c['sick_b'] ?? 0 }}</td><td>{{ $c['sick_g'] ?? 0 }}</td><td>{{ ($c['sick_b'] ?? 0) + ($c['sick_g'] ?? 0) }}</td>
                    @else
                        @for($i=0; $i<18; $i++) <td>-</td> @endfor
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="observations-grid">
        <tr>
            <td>
                <div class="obs-title">1. School Environment</div>
                <div>{{ $report->school_environment ?: 'N/A' }}</div>
            </td>
            <td>
                <div class="obs-title">2. Pupil's Cleanliness</div>
                <div>{{ $report->pupils_cleanliness ?: 'N/A' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="obs-title">3. Staff Attendance</div>
                <div style="white-space: pre-line;">{{ $report->teachers_attendance ?: 'N/A' }}</div>
            </td>
            <td>
                <div class="obs-title">4. Timetable Follow-up</div>
                <div>{{ $report->timetable_status ?: 'N/A' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="obs-title">5. Outside Activities</div>
                <div>{{ $report->outside_activities ?: 'N/A' }}</div>
            </td>
            <td>
                <div class="obs-title">6. Special Events</div>
                <div>{{ $report->special_events ?: 'N/A' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="obs-title">7. Teacher's General Comments</div>
                <div style="white-space: pre-line;">{{ $report->teacher_comments ?: 'N/A' }}</div>
            </td>
        </tr>
    </table>

    @if($report->admin_comments)
    <div style="margin-top: 20px; padding: 10px; border: 1px solid #ddd; background: #fffdf0;">
        <div class="obs-title">Headmaster Comments</div>
        <div style="font-style: italic;">"{{ $report->admin_comments }}"</div>
    </div>
    @endif

    <table class="sig-table">
        <tr>
            <td class="stamp-cell">
                @if($schoolStampBase64)
                    <img src="{{ $schoolStampBase64 }}" alt="Stamp">
                @else
                    <div style="width:100px; height:100px; border:1px dashed #ccc; margin:0 auto; line-height:100px; color:#ccc;">STAMP</div>
                @endif
            </td>
            <td class="sign-cell">
                @if($report->signature_image)
                    {{-- User specific signature --}}
                    <div class="school-signature-img">
                        <img src="{{ $report->signature_image }}" alt="Signature">
                    </div>
                @elseif($report->signed_at && $schoolSignBase64)
                    {{-- Standard school signature --}}
                    <div class="school-signature-img">
                        <img src="{{ $schoolSignBase64 }}" alt="Signature">
                    </div>
                @else
                    <div style="width:160px; height:45px; border-bottom:1.5px solid #940000; margin: 0 auto 4px auto; color:#ccc;">{{ $report->signed_by ?? 'SIGNATURE' }}</div>
                @endif
                <div class="sig-line"></div>
                <span class="sig-label">
                    {{ $report->signed_by ?: 'Headmaster' }}
                    @if($report->signed_at)
                        <br><small style="font-size: 7px;">Signed on {{ $report->signed_at->format('d/m/Y H:i') }}</small>
                    @endif
                </span>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated by ShuleXpert (School Management System), Powered By EmCa Techonology | {{ date('d/m/Y H:i') }} | Teacher: {{ $teacher?->first_name ?? 'N/A' }} {{ $teacher?->last_name ?? '' }}
    </div>
</body>
</html>
