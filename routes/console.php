<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule session time notifications - run every 5 minutes
Schedule::command('session:notify-teachers')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

Artisan::command('students:import-template {file : Path to TSV file} {--school=7 : schoolID} {--dry-run : Validate only; do not write to DB}', function () {
    $file = (string) $this->argument('file');
    $schoolID = (int) $this->option('school');
    $dryRun = (bool) $this->option('dry-run');

    if (!is_file($file)) {
        $this->error("File not found: {$file}");
        return 1;
    }

    $classNameToSubclassId = [
        'FORM ONE' => 93,
        'FORM TWO' => 94,
        'FORM THREE' => 95,
        'FORM FOUR' => 96,
    ];

    $normalizePhone = static function (?string $phone): ?string {
        if ($phone === null) return null;
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === '') return null;

        // Common TZ formats
        if (Str::startsWith($digits, '0') && strlen($digits) === 10) {
            $digits = '255' . substr($digits, 1);
        }
        if (Str::startsWith($digits, '2550') && strlen($digits) === 13) {
            $digits = '255' . substr($digits, 4);
        }
        return $digits;
    };

    $parseDate = static function (?string $value): ?string {
        $value = trim((string) $value);
        if ($value === '') return null;
        $value = str_replace(['\\', '.'], ['/', '/'], $value);

        $formats = [
            'Y-m-d',
            'm/d/Y',
            'n/j/Y',
            'm/d/y',
            'd/m/Y',
            'd-m-Y',
        ];
        foreach ($formats as $fmt) {
            try {
                return Carbon::createFromFormat($fmt, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // try next
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    };

    $content = file_get_contents($file);
    if ($content === false) {
        $this->error('Failed to read file');
        return 1;
    }

    $lines = preg_split("/\r\n|\n|\r/", $content);
    $lines = array_values(array_filter($lines, static fn ($l) => trim((string) $l) !== ''));
    if (count($lines) < 2) {
        $this->error('File must include a header row and at least one data row.');
        return 1;
    }

    $header = str_getcsv($lines[0], "\t");
    $header = array_map(static fn ($h) => trim((string) $h), $header);
    $colIndex = [];
    foreach ($header as $i => $h) {
        if ($h !== '') $colIndex[$h] = $i;
    }

    $get = static function (array $row, array $colIndex, string $colName): string {
        $idx = $colIndex[$colName] ?? null;
        if ($idx === null) return '';
        return isset($row[$idx]) ? trim((string) $row[$idx]) : '';
    };

    $requiredCols = [
        'Parent Phone (e.g. 255712345678)',
        'Student First Name*',
        'Student Last Name*',
        'Student Gender (Male/Female)*',
        'Class name',
    ];
    foreach ($requiredCols as $c) {
        if (!array_key_exists($c, $colIndex)) {
            $this->error("Missing required column: {$c}");
            return 1;
        }
    }

    $this->info('Import starting...');
    $this->line('SchoolID: ' . $schoolID);
    $this->line('Dry run: ' . ($dryRun ? 'YES' : 'NO'));

    $createdParents = 0;
    $createdStudents = 0;
    $createdUsers = 0;
    $skipped = 0;
    $errors = 0;

    $run = function () use (
        $lines,
        $colIndex,
        $get,
        $normalizePhone,
        $parseDate,
        $classNameToSubclassId,
        $schoolID,
        $dryRun,
        &$createdParents,
        &$createdStudents,
        &$createdUsers,
        &$skipped,
        &$errors
    ) {
        for ($lineNo = 2; $lineNo <= count($lines); $lineNo++) {
            $row = str_getcsv($lines[$lineNo - 1], "\t");

            $parentPhone = $normalizePhone($get($row, $colIndex, 'Parent Phone (e.g. 255712345678)'));
            $studentFirstName = $get($row, $colIndex, 'Student First Name*');
            $studentMiddleName = $get($row, $colIndex, 'Student Middle Name');
            $studentLastName = $get($row, $colIndex, 'Student Last Name*');
            $studentGenderRaw = strtoupper($get($row, $colIndex, 'Student Gender (Male/Female)*'));
            $studentGender = $studentGenderRaw === 'FEMALE' ? 'Female' : ($studentGenderRaw === 'MALE' ? 'Male' : null);

            $classNameRaw = strtoupper(trim($get($row, $colIndex, 'Class name')));
            $subclassID = null;

            $explicitSubclassId = $get($row, $colIndex, 'Class Subclass ID*');
            if ($explicitSubclassId !== '' && ctype_digit($explicitSubclassId)) {
                $subclassID = (int) $explicitSubclassId;
            } else {
                $subclassID = $classNameToSubclassId[$classNameRaw] ?? null;
            }

            if (!$parentPhone || !$studentFirstName || !$studentLastName || !$studentGender || !$subclassID) {
                $errors++;
                continue;
            }

            $admissionNumber = $get($row, $colIndex, 'Admission Number');
            $admissionDate = $parseDate($get($row, $colIndex, 'Admission Date (YYYY-MM-DD)'));
            $dob = $parseDate($get($row, $colIndex, 'Student DOB (YYYY-MM-DD)'));
            $studentAddress = $get($row, $colIndex, 'Student Address');

            $paymentType = strtoupper($get($row, $colIndex, 'Payment Type (Own/Sponsor)'));
            $sponsorId = $get($row, $colIndex, 'Sponsor ID');
            $sponsorPct = $get($row, $colIndex, 'Sponsorship Percentage');

            $parentGender = $get($row, $colIndex, 'Parent Gender (Male/Female)');
            $parentOccupation = $get($row, $colIndex, 'Parent Occupation');
            $parentEmail = $get($row, $colIndex, 'Parent Email');
            $parentAddress = $get($row, $colIndex, 'Parent Address');

            // Parents table requires first_name + last_name but template doesn't include names.
            $parentFirstName = 'Parent';
            $parentLastName = $parentPhone;

            $parent = ParentModel::where('phone', $parentPhone)->first();
            if (!$parent) {
                if (!$dryRun) {
                    $parent = ParentModel::create([
                        'schoolID' => $schoolID,
                        'first_name' => $parentFirstName,
                        'middle_name' => null,
                        'last_name' => $parentLastName,
                        'gender' => $parentGender !== '' ? $parentGender : null,
                        'occupation' => $parentOccupation !== '' ? $parentOccupation : null,
                        'national_id' => null,
                        'phone' => $parentPhone,
                        'email' => $parentEmail !== '' ? $parentEmail : null,
                        'address' => $parentAddress !== '' ? $parentAddress : null,
                    ]);
                }
                $createdParents++;
            }

            if ($admissionNumber === '') {
                // Minimal fallback: ensure unique-ish admission number
                $admissionNumber = 'SCH' . $schoolID . '/' . str_pad((string) $lineNo, 3, '0', STR_PAD_LEFT) . '/' . date('Y');
            }

            // Skip if already exists
            $existingStudent = Student::where('admission_number', $admissionNumber)->first();
            if ($existingStudent) {
                $skipped++;
                continue;
            }

            // Fingerprint/studentID: prefer admission number if numeric 4-digit and unused
            $fingerprintId = null;
            if (ctype_digit($admissionNumber) && strlen($admissionNumber) === 4) {
                $candidate = $admissionNumber;
                $fingerprintUsed = User::where('fingerprint_id', $candidate)->exists()
                    || Student::where('fingerprint_id', $candidate)->exists()
                    || Student::where('studentID', (int) $candidate)->exists();
                if (!$fingerprintUsed) {
                    $fingerprintId = $candidate;
                }
            }
            if ($fingerprintId === null) {
                do {
                    $fingerprintId = (string) rand(1000, 9999);
                } while (
                    User::where('fingerprint_id', $fingerprintId)->exists() ||
                    Student::where('fingerprint_id', $fingerprintId)->exists() ||
                    Student::where('studentID', (int) $fingerprintId)->exists()
                );
            }

            $studentPayload = [
                'studentID' => (int) $fingerprintId,
                'schoolID' => $schoolID,
                'subclassID' => $subclassID,
                'parentID' => $parent ? $parent->parentID : null,
                'first_name' => $studentFirstName,
                'middle_name' => $studentMiddleName !== '' ? $studentMiddleName : null,
                'last_name' => $studentLastName,
                'gender' => $studentGender,
                'date_of_birth' => $dob,
                'admission_number' => $admissionNumber,
                'fingerprint_id' => $fingerprintId,
                'admission_date' => $admissionDate,
                'address' => $studentAddress !== '' ? $studentAddress : null,
                'status' => 'Active',
            ];

            // Optional columns (only if DB has them)
            $setIfColumn = static function (array &$payload, string $col, string $value) {
                if (Schema::hasColumn('students', $col)) {
                    $payload[$col] = $value;
                }
            };

            $religion = $get($row, $colIndex, 'Religion');
            $nationality = $get($row, $colIndex, 'Nationality');
            $birthCert = $get($row, $colIndex, 'Birth Certificate No');
            $generalHealth = $get($row, $colIndex, 'General Health Condition');
            $isDisabled = strtoupper($get($row, $colIndex, 'Is Disabled (Yes/No)')) === 'YES';
            $disabilityDetails = $get($row, $colIndex, 'Disability Details');
            $hasChronic = strtoupper($get($row, $colIndex, 'Has Chronic Illness (Yes/No)')) === 'YES';
            $chronicDetails = $get($row, $colIndex, 'Chronic Illness Details');
            $hasEpilepsy = strtoupper($get($row, $colIndex, 'Has Epilepsy (Yes/No)')) === 'YES';
            $hasAllergies = strtoupper($get($row, $colIndex, 'Has Allergies (Yes/No)')) === 'YES';
            $allergiesDetails = $get($row, $colIndex, 'Allergies Details');
            $immunization = $get($row, $colIndex, 'Immunization Details');
            $emergencyName = $get($row, $colIndex, 'Emergency Name');
            $emergencyRel = $get($row, $colIndex, 'Emergency Relationship');
            $emergencyPhone = $normalizePhone($get($row, $colIndex, 'Emergency Phone'));
            $declarationDate = $parseDate($get($row, $colIndex, 'Declaration Date (YYYY-MM-DD)'));
            $officerName = $get($row, $colIndex, 'Registering Officer Name');
            $officerTitle = $get($row, $colIndex, 'Registering Officer Title');

            $setIfColumn($studentPayload, 'religion', $religion !== '' ? $religion : null);
            $setIfColumn($studentPayload, 'nationality', $nationality !== '' ? $nationality : null);
            $setIfColumn($studentPayload, 'birth_certificate_number', $birthCert !== '' ? $birthCert : null);
            $setIfColumn($studentPayload, 'general_health_condition', $generalHealth !== '' ? $generalHealth : null);
            $setIfColumn($studentPayload, 'is_disabled', $isDisabled ? 1 : 0);
            $setIfColumn($studentPayload, 'has_disability', $isDisabled ? 1 : 0);
            $setIfColumn($studentPayload, 'disability_details', $isDisabled ? ($disabilityDetails !== '' ? $disabilityDetails : null) : null);
            $setIfColumn($studentPayload, 'has_chronic_illness', $hasChronic ? 1 : 0);
            $setIfColumn($studentPayload, 'chronic_illness_details', $hasChronic ? ($chronicDetails !== '' ? $chronicDetails : null) : null);
            $setIfColumn($studentPayload, 'has_epilepsy', $hasEpilepsy ? 1 : 0);
            $setIfColumn($studentPayload, 'has_allergies', $hasAllergies ? 1 : 0);
            $setIfColumn($studentPayload, 'allergies_details', $hasAllergies ? ($allergiesDetails !== '' ? $allergiesDetails : null) : null);
            $setIfColumn($studentPayload, 'immunization_details', $immunization !== '' ? $immunization : null);
            $setIfColumn($studentPayload, 'emergency_contact_name', $emergencyName !== '' ? $emergencyName : null);
            $setIfColumn($studentPayload, 'emergency_contact_relationship', $emergencyRel !== '' ? $emergencyRel : null);
            $setIfColumn($studentPayload, 'emergency_contact_phone', $emergencyPhone);
            $setIfColumn($studentPayload, 'declaration_date', $declarationDate);
            $setIfColumn($studentPayload, 'registering_officer_name', $officerName !== '' ? $officerName : null);
            $setIfColumn($studentPayload, 'registering_officer_title', $officerTitle !== '' ? $officerTitle : null);

            if (Schema::hasColumn('students', 'sponsor_id')) {
                $studentPayload['sponsor_id'] = ($paymentType === 'SPONSOR' && ctype_digit($sponsorId)) ? (int) $sponsorId : null;
            }
            if (Schema::hasColumn('students', 'sponsorship_percentage')) {
                $pct = is_numeric($sponsorPct) ? (float) $sponsorPct : 0;
                $studentPayload['sponsorship_percentage'] = ($paymentType === 'SPONSOR') ? $pct : 0;
            }

            if (!$dryRun) {
                Student::create($studentPayload);
            }
            $createdStudents++;

            // Create user account
            $email = $admissionNumber . '@student.local';
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = $admissionNumber . '_' . $counter . '@student.local';
                $counter++;
            }

            if (!$dryRun) {
                User::create([
                    'name' => $admissionNumber,
                    'email' => $email,
                    'password' => Hash::make($studentLastName),
                    'user_type' => 'student',
                    'fingerprint_id' => $fingerprintId,
                ]);
            }
            $createdUsers++;
        }
    };

    if ($dryRun) {
        $run();
    } else {
        DB::beginTransaction();
        try {
            $run();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }

    $this->info('Import finished');
    $this->line('Parents created: ' . $createdParents);
    $this->line('Students created: ' . $createdStudents);
    $this->line('Users created: ' . $createdUsers);
    $this->line('Skipped (existing admission_number): ' . $skipped);
    $this->line('Row errors (missing/invalid required fields): ' . $errors);

    return $errors > 0 ? 2 : 0;
})->purpose('Bulk import students from the provided template TSV (dedup parents by phone)');
