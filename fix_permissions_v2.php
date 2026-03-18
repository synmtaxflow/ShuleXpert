<?php

use Illuminate\Support\Facades\DB;

// Fix permission categories for ALL roles
$categories = [
    'printing_unit',
    'school_visitors',
    'scheme_of_work',
    'lesson_plans',
    'academic_years',
    'watchman',
    'subject_analysis'
];

foreach ($categories as $category) {
    // Both category_action and action_category formats
    $count = DB::table('permissions')
        ->where(function($query) use ($category) {
            $query->where('name', 'like', $category . '_%')
                  ->orWhere('name', 'like', '%_' . $category);
        })
        ->update(['permission_category' => $category]);
    
    echo "Updated $count permissions for category: $category\n";
}
