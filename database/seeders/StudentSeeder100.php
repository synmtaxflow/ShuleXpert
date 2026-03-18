<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentSeeder100 extends Seeder
{
    public function run(): void
    {
        $schoolID = 6;
        $subclasses = DB::table('subclasses')
            ->join('classes', 'subclasses.classID', '=', 'classes.classID')
            ->where('classes.schoolID', $schoolID)
            ->where('subclasses.status', 'Active')
            ->pluck('subclassID')
            ->toArray();

        $firstNames = ['Abasi', 'Bakari', 'Chidi', 'Dakarai', 'Ekwueme', 'Faraji', 'Gamba', 'Hakim', 'Idris', 'Juma', 
                      'Kato', 'Lutalo', 'Mosi', 'Nnamdi', 'Obi', 'Paki', 'Rafiki', 'Sefu', 'Tayo', 'Uche',
                      'Vuyo', 'Wekesa', 'Xola', 'Yusef', 'Zola', 'Asha', 'Binti', 'Chausiku', 'Dalila', 'Eshe',
                      'Fatuma', 'Ghalia', 'Habiba', 'Ifunanya', 'Jendayi', 'Kamaria', 'Lulu', 'Malaika', 'Nia', 'Olufemi'];
        
        $lastNames = ['Mwangi', 'Okonkwo', 'Kamau', 'Traore', 'Keita', 'Diallo', 'Mensah', 'Bekele', 'Tadesse', 'Ndiaye',
                     'Sow', 'Banda', 'Phiri', 'Moyo', 'Dube', 'Ochieng', 'Otieno', 'Akinyi', 'Maina', 'Karanja'];

        $currentMaxId = DB::table('students')->max('studentID') ?? 0;

        foreach ($subclasses as $subclassID) {
            $this->command->info("Seeding 10 students for subclass ID: $subclassID");
            
            for ($i = 1; $i <= 10; $i++) {
                $currentMaxId++;
                
                // Create Parent first
                $parentFirstName = $firstNames[array_rand($firstNames)];
                $parentLastName = $lastNames[array_rand($lastNames)];
                $parentPhone = '2557' . rand(10000000, 99999999);
                
                $parentID = DB::table('parents')->insertGetId([
                    'schoolID' => $schoolID,
                    'first_name' => $parentFirstName,
                    'last_name' => $parentLastName,
                    'gender' => (rand(0, 1) ? 'Male' : 'Female'),
                    'phone' => $parentPhone,
                    'relationship_to_student' => 'Parent',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create Student
                $firstName = $firstNames[array_rand($firstNames)];
                $middleName = $firstNames[array_rand($firstNames)];
                $lastName = $parentLastName;
                $gender = (rand(0, 1) ? 'Male' : 'Female');
                $admissionNumber = 'ADM/' . $schoolID . '/' . $subclassID . '/' . str_pad($i, 3, '0', STR_PAD_LEFT) . '/' . date('Y');
                
                // Fingerprint ID: unique 4-digit
                do {
                    $fingerprintId = (string)rand(1000, 9999);
                } while (
                    DB::table('users')->where('fingerprint_id', $fingerprintId)->exists() ||
                    DB::table('students')->where('fingerprint_id', $fingerprintId)->exists()
                );

                DB::table('students')->insert([
                    'studentID' => $currentMaxId,
                    'schoolID' => $schoolID,
                    'subclassID' => $subclassID,
                    'parentID' => $parentID,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'gender' => $gender,
                    'admission_number' => $admissionNumber,
                    'fingerprint_id' => $fingerprintId,
                    'status' => 'Active',
                    'date_of_birth' => date('Y-m-d', strtotime('-' . rand(6, 15) . ' years')),
                    'admission_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info("🎉 Done! Successfully registered 10 students per subclass for schoolID $schoolID.");
    }
}
