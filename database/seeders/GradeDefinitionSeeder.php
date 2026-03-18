<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GradeDefinitionSeeder extends Seeder
{
    public function run()
    {
        $classIDs = [44, 45, 46, 47, 48, 49, 50]; // Standard 1 to 7

        $grades = [
            ['grade' => 'A', 'first' => 81, 'last' => 100],
            ['grade' => 'B', 'first' => 61, 'last' => 80],
            ['grade' => 'C', 'first' => 41, 'last' => 60],
            ['grade' => 'D', 'first' => 31, 'last' => 40],
            ['grade' => 'F', 'first' => 0, 'last' => 30],
        ];

        foreach ($classIDs as $classID) {
            // Delete existing definitions for these classes to avoid duplicates
            DB::table('grade_definitions')->where('classID', $classID)->delete();

            foreach ($grades as $g) {
                DB::table('grade_definitions')->insert([
                    'classID' => $classID,
                    'grade' => $g['grade'],
                    'first' => $g['first'],
                    'last' => $g['last'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
