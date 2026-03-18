<?php

use Illuminate\Support\Facades\DB;

// Fix permission categories for multi-word categories
$multiWordCategories = [
    'printing_unit',
    'school_visitors',
    'scheme_of_work',
    'lesson_plans',
    'academic_years'
];

foreach ($multiWordCategories as $category) {
    $count = DB::table('permissions')
        ->where('name', 'like', $category . '_%')
        ->update(['permission_category' => $category]);
    
    echo "Updated $count permissions for category: $category\n";
}

// Also fix watchman just in case
$count = DB::table('permissions')
    ->where('name', 'like', 'watchman_%')
    ->update(['permission_category' => 'watchman']);
echo "Updated $count permissions for category: watchman\n";
