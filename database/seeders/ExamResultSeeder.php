<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamResultSeeder extends Seeder
{
    public function run()
    {
        $schoolID = 6;
        $examIDs = [51, 52]; // Midterm and Terminal

        // 1. Ensure these exams exist and are in "results_available" or "awaiting_results" state
        foreach ($examIDs as $id) {
            DB::table('examinations')->where('examID', $id)->update([
                'schoolID' => $schoolID,
                'status' => 'results_available',
                'enter_result' => 1,
                'publish_result' => 1
            ]);
        }

        // 2. Get students and their subjects
        $students = DB::table('students')
            ->where('schoolID', $schoolID)
            ->select('studentID', 'subclassID')
            ->get();

        if ($students->count() == 0) return;

        foreach ($examIDs as $examID) {
            foreach ($students as $student) {
                // Get subjects for this student's subclass
                $subjects = DB::table('class_subjects')
                    ->where('subclassID', $student->subclassID)
                    ->where('status', 'Active')
                    ->get();

                foreach ($subjects as $subject) {
                    $marks = rand(40, 95); // Random marks
                    $grade = $this->calculateGrade($marks);

                    DB::table('results')->updateOrInsert(
                        [
                            'studentID' => $student->studentID,
                            'examID' => $examID,
                            'class_subjectID' => $subject->class_subjectID,
                        ],
                        [
                            'subclassID' => $student->subclassID,
                            'marks' => $marks,
                            'grade' => $grade,
                            'remark' => $this->getRemark($grade),
                            'status' => 'approved',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    );
                }
            }
        }
    }

    private function calculateGrade($marks) {
        if ($marks >= 81) return 'A';
        if ($marks >= 61) return 'B';
        if ($marks >= 41) return 'C';
        if ($marks >= 31) return 'D';
        return 'F';
    }

    private function getRemark($grade) {
        $remarks = [
            'A' => 'Excellent',
            'B' => 'Very Good',
            'C' => 'Good',
            'D' => 'Satisfactory',
            'F' => 'Fail'
        ];
        return $remarks[$grade] ?? 'N/A';
    }
}
