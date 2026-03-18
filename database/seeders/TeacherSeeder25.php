<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder25 extends Seeder
{
    public function run(): void
    {
        $schoolID = 6;

        $teachers = [
            ['first_name' => 'Amani',   'middle_name' => 'Juma',    'last_name' => 'Mwangi',  'gender' => 'Female'],
            ['first_name' => 'Baraka',  'middle_name' => 'Hassan',  'last_name' => 'Ochieng', 'gender' => 'Male'],
            ['first_name' => 'Zawadi',  'middle_name' => 'Aisha',   'last_name' => 'Kamau',   'gender' => 'Female'],
            ['first_name' => 'Faraji',  'middle_name' => 'Said',    'last_name' => 'Ndugu',   'gender' => 'Male'],
            ['first_name' => 'Neema',   'middle_name' => 'Grace',   'last_name' => 'Mutua',   'gender' => 'Female'],
            ['first_name' => 'Juma',    'middle_name' => 'Omar',    'last_name' => 'Kimani',  'gender' => 'Male'],
            ['first_name' => 'Salama',  'middle_name' => 'Fatuma',  'last_name' => 'Otieno',  'gender' => 'Female'],
            ['first_name' => 'Tumaini', 'middle_name' => 'Moses',   'last_name' => 'Wambua',  'gender' => 'Male'],
            ['first_name' => 'Rehema',  'middle_name' => 'Zuhura',  'last_name' => 'Njoroge', 'gender' => 'Female'],
            ['first_name' => 'Chidi',   'middle_name' => 'Emeka',   'last_name' => 'Okonkwo', 'gender' => 'Male'],
            ['first_name' => 'Imani',   'middle_name' => 'Rose',    'last_name' => 'Akinyi',  'gender' => 'Female'],
            ['first_name' => 'Seif',    'middle_name' => 'Rashid',  'last_name' => 'Bakari',  'gender' => 'Male'],
            ['first_name' => 'Pendo',   'middle_name' => 'Amina',   'last_name' => 'Githuku', 'gender' => 'Female'],
            ['first_name' => 'Luseko',  'middle_name' => 'David',   'last_name' => 'Mwamba',  'gender' => 'Male'],
            ['first_name' => 'Upendo',  'middle_name' => 'Joyce',   'last_name' => 'Wanjiku', 'gender' => 'Female'],
            ['first_name' => 'Hamisi',  'middle_name' => 'Ali',     'last_name' => 'Mshamba', 'gender' => 'Male'],
            ['first_name' => 'Wema',    'middle_name' => 'Esther',  'last_name' => 'Auma',    'gender' => 'Female'],
            ['first_name' => 'Kipchoge','middle_name' => 'Elijah',  'last_name' => 'Koech',   'gender' => 'Male'],
            ['first_name' => 'Furaha',  'middle_name' => 'Mama',    'last_name' => 'Makori',  'gender' => 'Female'],
            ['first_name' => 'Mwenda',  'middle_name' => 'George',  'last_name' => 'Kariuki', 'gender' => 'Male'],
            ['first_name' => 'Ahadi',   'middle_name' => 'Peace',   'last_name' => 'Ndiaye',  'gender' => 'Female'],
            ['first_name' => 'Subira',  'middle_name' => 'Lucas',   'last_name' => 'Mwita',   'gender' => 'Male'],
            ['first_name' => 'Kilima',  'middle_name' => 'Michael', 'last_name' => 'Moto',    'gender' => 'Male'],
            ['first_name' => 'Asili',   'middle_name' => 'Halima',  'last_name' => 'Nakato',  'gender' => 'Female'],
            ['first_name' => 'Zaki',    'middle_name' => 'Ibrahim', 'last_name' => 'Kibwana', 'gender' => 'Male'],
        ];

        // Collect already-used fingerprint IDs to avoid duplicates during seeding
        $usedIds = DB::table('teachers')->pluck('id')->toArray();

        foreach ($teachers as $index => $t) {
            // Generate unique 4-digit fingerprint ID
            do {
                $fingerprintId = (string) rand(1000, 9999);
            } while (
                in_array((int)$fingerprintId, $usedIds) ||
                DB::table('users')->where('fingerprint_id', $fingerprintId)->exists()
            );
            $usedIds[] = (int)$fingerprintId;

            $num     = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $emp_num = 'TCH' . $schoolID . $num;
            $nat_id  = '19800000' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $phone   = '25561' . str_pad(4000000 + $schoolID * 100 + $index + 1, 7, '0', STR_PAD_LEFT);
            $email   = strtolower($t['first_name'] . '.' . $t['last_name']) . $index . '@school' . $schoolID . '.ac.tz';

            DB::table('teachers')->insert([
                'id'             => (int)$fingerprintId,
                'schoolID'       => $schoolID,
                'first_name'     => $t['first_name'],
                'middle_name'    => $t['middle_name'],
                'last_name'      => $t['last_name'],
                'gender'         => $t['gender'],
                'national_id'    => $nat_id,
                'employee_number' => $emp_num,
                'email'          => $email,
                'phone_number'   => $phone,
                'status'         => 'Active',
                'fingerprint_id' => $fingerprintId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $this->command->info("✅ Inserted: {$t['first_name']} {$t['last_name']} (ID: $fingerprintId, Emp: $emp_num)");
        }

        $this->command->info("\n🎉 Done! 25 teachers inserted for schoolID $schoolID.");
    }
}
