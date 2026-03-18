<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeAndPaymentSeeder extends Seeder
{
    public function run()
    {
        $schoolID = 6;
        
        // Cleanup existing for schoolID 6 to ensure "balanced" stats
        DB::table('payments')->where('schoolID', $schoolID)->delete();
        DB::table('student_fee_payments')->where('schoolID', $schoolID)->delete();
        DB::table('fees')->where('schoolID', $schoolID)->delete();

        $classes = [44, 45, 46, 47, 48, 49, 50]; // Standard 1 to 7

        // 1. Create Fees for each class
        $feeTypes = [
            ['name' => 'Tuition Fee', 'amount' => 500000, 'installments' => 4, 'type' => 'Quarter'],
            ['name' => 'Transport Fee', 'amount' => 200000, 'installments' => 2, 'type' => 'Semester'],
            ['name' => 'Uniform & Books', 'amount' => 150000, 'installments' => 1, 'type' => 'One-time'],
        ];

        foreach ($classes as $classID) {
            foreach ($feeTypes as $ft) {
                DB::table('fees')->insert([
                    'schoolID' => $schoolID,
                    'classID' => $classID,
                    'fee_name' => $ft['name'],
                    'amount' => $ft['amount'],
                    'must_start_pay' => 1,
                    'duration' => $ft['installments'] > 1 ? 'Year' : 'One-time',
                    'allow_installments' => $ft['installments'] > 1 ? 1 : 0,
                    'default_installment_type' => $ft['type'],
                    'number_of_installments' => $ft['installments'],
                    'status' => 'Active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // 2. Assign Fees and Payments to Students
        $students = DB::table('students')
            ->join('subclasses', 'students.subclassID', '=', 'subclasses.subclassID')
            ->where('students.schoolID', $schoolID)
            ->select('students.studentID', 'subclasses.classID')
            ->get();

        $count = count($students);
        if ($count == 0) return;

        // Prepare status distribution to be exactly balanced
        $statuses = ['Paid', 'Partial', 'Overpaid', 'Pending', 'Incomplete Payment'];
        
        foreach ($students as $index => $student) {
            $classFees = DB::table('fees')
                ->where('schoolID', $schoolID)
                ->where('classID', $student->classID)
                ->get();

            $totalRequired = 0;
            foreach ($classFees as $fee) {
                $totalRequired += $fee->amount;
                DB::table('student_fee_payments')->insert([
                    'schoolID' => $schoolID,
                    'studentID' => $student->studentID,
                    'feeID' => $fee->feeID,
                    'fee_name' => $fee->fee_name,
                    'fee_total_amount' => $fee->amount,
                    'amount_paid' => 0,
                    'balance' => $fee->amount,
                    'is_required' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $controlNumber = '99' . str_pad($student->studentID, 5, '0', STR_PAD_LEFT) . rand(100, 999);
            
            // Balanced Distribution: use modulo to cycle through statuses
            $status = $statuses[$index % count($statuses)];
            $amountPaid = 0;

            switch ($status) {
                case 'Paid':
                    $amountPaid = $totalRequired;
                    break;
                case 'Partial':
                    $amountPaid = $totalRequired * 0.5;
                    break;
                case 'Overpaid':
                    $amountPaid = $totalRequired + 20000;
                    break;
                case 'Pending':
                    $amountPaid = 0;
                    break;
                case 'Incomplete Payment':
                    $amountPaid = $totalRequired * 0.15;
                    break;
            }

            $paymentID = DB::table('payments')->insertGetId([
                'schoolID' => $schoolID,
                'studentID' => $student->studentID,
                'control_number' => $controlNumber,
                'amount_required' => $totalRequired,
                'required_fees_amount' => $totalRequired,
                'amount_paid' => $amountPaid,
                'balance' => max(0, $totalRequired - $amountPaid),
                'debt' => $amountPaid < $totalRequired ? ($totalRequired - $amountPaid) : 0,
                'payment_status' => $status,
                'payment_date' => $amountPaid > 0 ? Carbon::now() : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Distribute paid amount to individual fees
            if ($amountPaid > 0) {
                $remaining = $amountPaid;
                $sfps = DB::table('student_fee_payments')
                    ->where('studentID', $student->studentID)
                    ->get();
                
                foreach ($sfps as $sfp) {
                    $pay = min($remaining, $sfp->fee_total_amount);
                    DB::table('student_fee_payments')
                        ->where('payment_detail_id', $sfp->payment_detail_id)
                        ->update([
                            'paymentID' => $paymentID,
                            'amount_paid' => $pay,
                            'balance' => $sfp->fee_total_amount - $pay,
                            'last_payment_date' => Carbon::now()
                        ]);
                    $remaining -= $pay;
                    if ($remaining <= 0) break;
                }
            }
        }
    }
}
