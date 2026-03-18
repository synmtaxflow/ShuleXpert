<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrimarySubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $schoolID = 6;

        // ============================================================
        // STEP 1: Create School Subjects (Tanzania Primary Curriculum)
        // ============================================================
        $subjects = [
            ['name' => 'Kiswahili',               'code' => 'KSW'],
            ['name' => 'English',                  'code' => 'ENG'],
            ['name' => 'Hisabati',                 'code' => 'HSB'],  // Mathematics
            ['name' => 'Sayansi na Teknolojia',    'code' => 'SNT'],  // Science & Technology
            ['name' => 'Maarifa ya Jamii',         'code' => 'MJA'],  // Social Studies
            ['name' => 'Uraia na Maadili',         'code' => 'URM'],  // Civics & Ethics
            ['name' => 'Stadi za Kazi',            'code' => 'SDK'],  // Vocational Skills
            ['name' => 'TEHAMA',                   'code' => 'ICT'],  // ICT
            ['name' => 'French',                   'code' => 'FRE'],  // French (elective)
            ['name' => 'Arabic',                   'code' => 'ARB'],  // Arabic (elective)
            ['name' => 'Dini ya Kiislamu',         'code' => 'ISL'],  // Islamic Religion
            ['name' => 'Dini ya Kikristo',         'code' => 'CRE'],  // Christian Religion
            ['name' => 'Elimu ya Michezo',         'code' => 'SPT'],  // Physical Education / Sports
        ];

        $subjectIDs = [];
        foreach ($subjects as $s) {
            // Check if subject already exists
            $existing = DB::table('school_subjects')
                ->where('schoolID', $schoolID)
                ->where('subject_name', $s['name'])
                ->first();

            if ($existing) {
                $subjectIDs[$s['name']] = $existing->subjectID;
                $this->command->info("  ⏭ Already exists: {$s['name']}");
            } else {
                $id = DB::table('school_subjects')->insertGetId([
                    'schoolID'     => $schoolID,
                    'subject_name' => $s['name'],
                    'subject_code' => $s['code'],
                    'status'       => 'Active',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $subjectIDs[$s['name']] = $id;
                $this->command->info("  ✅ Created: {$s['name']} ({$s['code']}) → ID: $id");
            }
        }

        $this->command->info("\n📚 School Subjects created: " . count($subjectIDs));

        // ============================================================
        // STEP 2: Define which subjects each class level gets
        // Tanzania primary curriculum mapping
        // ============================================================

        // Core subjects for ALL standards (Std 1-7)
        $coreSubjects = [
            'Kiswahili', 'English', 'Hisabati', 'Sayansi na Teknolojia',
            'Maarifa ya Jamii', 'Uraia na Maadili', 'Stadi za Kazi',
            'Elimu ya Michezo',
        ];

        // Extra subjects for upper primary (Std 3-7)
        $upperSubjects = ['TEHAMA'];

        // Elective subjects (Std 5-7)
        $electiveSubjects = ['French', 'Arabic'];

        // Religion (all classes - Required but choose one)
        $religionSubjects = ['Dini ya Kiislamu', 'Dini ya Kikristo'];

        // Class-to-subjects mapping
        $classSubjectsMap = [
            'Standard 1' => array_merge($coreSubjects, $religionSubjects),
            'Standard 2' => array_merge($coreSubjects, $religionSubjects),
            'Standard 3' => array_merge($coreSubjects, $upperSubjects, $religionSubjects),
            'Standard 4' => array_merge($coreSubjects, $upperSubjects, $religionSubjects),
            'Standard 5' => array_merge($coreSubjects, $upperSubjects, $electiveSubjects, $religionSubjects),
            'Standard 6' => array_merge($coreSubjects, $upperSubjects, $electiveSubjects, $religionSubjects),
            'Standard 7' => array_merge($coreSubjects, $upperSubjects, $electiveSubjects, $religionSubjects),
        ];

        // ============================================================
        // STEP 3: Subclass data from DB
        // ============================================================
        $subclasses = DB::select("
            SELECT s.subclassID, s.classID, s.subclass_name, s.stream_code, s.teacherID, c.class_name
            FROM subclasses s
            JOIN classes c ON s.classID = c.classID
            WHERE c.schoolID = ?
            ORDER BY c.class_name, s.subclass_name
        ", [$schoolID]);

        // Teacher IDs for schoolID 6
        $teachers = [1186, 1513, 1759, 2243, 2561, 3176, 3373, 3668, 4457, 4842,
                     5007, 5569, 5654, 6020, 6885, 7141, 7804, 7987, 8112, 8163,
                     8219, 8889, 8966, 9138, 9325];
        shuffle($teachers);
        $teacherIdx = 0;

        // ============================================================
        // STEP 4: Assign subjects to each subclass
        // ============================================================
        $totalInserted = 0;

        foreach ($subclasses as $sub) {
            $className = $sub->class_name;
            if (!isset($classSubjectsMap[$className])) {
                $this->command->warn("  ⚠ No subject mapping for: $className");
                continue;
            }

            $subjectsList = $classSubjectsMap[$className];
            $this->command->info("\n📖 {$className} {$sub->subclass_name} (subclassID: {$sub->subclassID}):");

            foreach ($subjectsList as $subjectName) {
                $subjectID = $subjectIDs[$subjectName] ?? null;
                if (!$subjectID) {
                    $this->command->warn("    ⚠ Subject not found: $subjectName");
                    continue;
                }

                // Check if already assigned
                $exists = DB::table('class_subjects')
                    ->where('subclassID', $sub->subclassID)
                    ->where('subjectID', $subjectID)
                    ->exists();

                if ($exists) {
                    $this->command->info("    ⏭ Already assigned: $subjectName");
                    continue;
                }

                // Rotate teachers
                $teacherID = $teachers[$teacherIdx % count($teachers)];
                $teacherIdx++;

                // Determine student_status
                $studentStatus = 'Required';
                if (in_array($subjectName, $electiveSubjects)) {
                    $studentStatus = 'Optional';
                }
                if (in_array($subjectName, $religionSubjects)) {
                    $studentStatus = 'Optional'; // Students pick one religion
                }

                DB::table('class_subjects')->insert([
                    'classID'        => $sub->classID,
                    'subclassID'     => $sub->subclassID,
                    'subjectID'      => $subjectID,
                    'teacherID'      => $teacherID,
                    'status'         => 'Active',
                    'student_status' => $studentStatus,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $totalInserted++;
                $statusLabel = $studentStatus === 'Optional' ? '🔵 Optional' : '🟢 Required';
                $this->command->info("    ✅ $subjectName → Teacher ID: $teacherID ($statusLabel)");
            }
        }

        $this->command->info("\n🎉 Done!");
        $this->command->info("   School Subjects: " . count($subjectIDs));
        $this->command->info("   Class Subject assignments: $totalInserted");
        $this->command->info("   Across " . count($subclasses) . " subclasses");
    }
}
