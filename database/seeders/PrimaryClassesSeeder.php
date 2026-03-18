<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrimaryClassesSeeder extends Seeder
{
    public function run(): void
    {
        $schoolID = 6;

        // All 25 teacher IDs for schoolID 6 (shuffled randomly)
        $teacherIDs = [1186, 1513, 1759, 2243, 2561, 3176, 3373, 3668, 4457, 4842,
                       5007, 5569, 5654, 6020, 6885, 7141, 7804, 7987, 8112, 8163,
                       8219, 8889, 8966, 9138, 9325];

        // Shuffle to assign randomly
        shuffle($teacherIDs);
        $teacherIndex = 0;

        // Primary school classes: Standard 1 - Standard 7
        // 3 classes will have 2 subclasses (A & B), remaining 4 will have 1 subclass
        $classes = [
            ['name' => 'Standard 1', 'subclasses' => 2],  // A, B
            ['name' => 'Standard 2', 'subclasses' => 2],  // A, B
            ['name' => 'Standard 3', 'subclasses' => 2],  // A, B
            ['name' => 'Standard 4', 'subclasses' => 1],  // Only A
            ['name' => 'Standard 5', 'subclasses' => 1],  // Only A
            ['name' => 'Standard 6', 'subclasses' => 1],  // Only A
            ['name' => 'Standard 7', 'subclasses' => 1],  // Only A
        ];

        foreach ($classes as $classData) {
            $hasSubclasses = $classData['subclasses'] > 1;

            // Insert class
            $classID = DB::table('classes')->insertGetId([
                'schoolID'      => $schoolID,
                'teacherID'     => null, // coordinator - leave null for main class
                'class_name'    => $classData['name'],
                'description'   => $classData['name'] . ' - Primary School',
                'status'        => 'Active',
                'has_subclasses' => $hasSubclasses ? 1 : 1, // always 1 (has at least 1 subclass)
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Create subclasses
            $subclassLabels = $classData['subclasses'] > 1 ? ['A', 'B'] : ['A'];

            foreach ($subclassLabels as $label) {
                $teacherID = $teacherIDs[$teacherIndex % count($teacherIDs)];
                $teacherIndex++;

                // stream_code e.g. "Std1A", "Std2B"
                $stdNum = filter_var($classData['name'], FILTER_SANITIZE_NUMBER_INT);
                $streamCode = 'Std' . $stdNum . $label;

                DB::table('subclasses')->insert([
                    'classID'       => $classID,
                    'teacherID'     => $teacherID,
                    'subclass_name' => $label,
                    'stream_code'   => $streamCode,
                    'status'        => 'Active',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $this->command->info("  ✅ Subclass: {$classData['name']} {$label} (stream: $streamCode, teacherID: $teacherID)");
            }

            $this->command->info("📚 Created: {$classData['name']} with {$classData['subclasses']} subclass(es)");
        }

        $this->command->info("\n🎉 Done! 7 primary classes with subclasses created for schoolID $schoolID.");
        $this->command->info("   - 3 classes with 2 subclasses (Std1–Std3): 6 subclasses");
        $this->command->info("   - 4 classes with 1 subclass (Std4–Std7): 4 subclasses");
        $this->command->info("   Total subclasses: 10");
    }
}
