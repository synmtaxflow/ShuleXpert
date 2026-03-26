<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Helper simulation
function debugSmartBase64($path) {
    if (!$path) return "PATH IS EMPTY";
    
    $base = base_path();
    $parent = dirname($base);
    
    $possibilities = [
        'public_path' => public_path($path),
        'parent_public_html' => $parent . '/public_html/' . $path,
        'doc_root' => (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/' . $path : "N/A"),
        'base_path_sibling' => base_path('../public_html/' . $path),
    ];
    
    $results = [];
    foreach ($possibilities as $name => $fullPath) {
        $exists = ($fullPath !== "N/A" && file_exists($fullPath)) ? "TRUE" : "FALSE";
        $results[] = "[$name]: $fullPath ($exists)";
    }
    
    return implode("\n", $results);
}

$schools = \App\Models\School::all();
foreach ($schools as $school) {
    echo "--- SCHOOL: " . $school->school_name . " (ID: " . $school->schoolID . ") ---\n";
    echo "LOGO PATH: " . $school->school_logo . "\n";
    echo debugSmartBase64($school->school_logo) . "\n";
    
    echo "STAMP PATH: " . $school->school_stamp . "\n";
    echo debugSmartBase64($school->school_stamp) . "\n";
    
    echo "SIGNATURE PATH: " . $school->school_signature . "\n";
    echo debugSmartBase64($school->school_signature) . "\n";
    echo "\n";
}
