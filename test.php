<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reports = \App\Models\DailyDutyReport::orderBy('created_at', 'desc')->take(2)->get();
foreach ($reports as $report) {
    echo "Report ID: " . $report->reportID . "\n";
    echo "Teacher ID (in report): " . $report->teacherID . "\n";
    
    $teacher = \App\Models\Teacher::find($report->teacherID);
    if ($teacher) {
        echo "Found Teacher via primary key (find): " . $teacher->first_name . " " . $teacher->last_name . "\n";
    } else {
        echo "Teacher not found via primary key (find)!\n";
    }
    
    $teacherFinger = \App\Models\Teacher::where('fingerprint_id', $report->teacherID)->first();
    if ($teacherFinger) {
        echo "Found Teacher via fingerprint_id: " . $teacherFinger->first_name . " " . $teacherFinger->last_name . "\n";
    }
    
    $teacherIdWhere = \App\Models\Teacher::where('id', $report->teacherID)->first();
    if ($teacherIdWhere) {
        echo "Found Teacher via id (where): " . $teacherIdWhere->first_name . " " . $teacherIdWhere->last_name . "\n";
    }

    $user = \App\Models\User::where('fingerprint_id', $report->teacherID)->orWhere('id', $report->teacherID)->first();
    if ($user) {
        echo "Found User: " . $user->name . "\n";
    }
    echo "--------------------------\n";
}
