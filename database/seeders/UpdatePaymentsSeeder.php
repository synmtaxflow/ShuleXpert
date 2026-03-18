<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdatePaymentsSeeder extends Seeder
{
    public function run()
    {
        $schoolID = 6;
        
        // 1. Get all payments for school 6
        $payments = DB::table('payments')
            ->where('schoolID', $schoolID)
            ->get();
            
        $count = $payments->count();
        if ($count == 0) return;

        $statuses = ['Paid', 'Partial', 'Overpaid', 'Pending', 'Incomplete Payment'];
        $batchSize = ceil($count / 5);
        $shuffledPayments = $payments->shuffle();

        foreach ($shuffledPayments as $index => $payment) {
            $statusIndex = floor($index / $batchSize);
            if ($statusIndex >= 5) $statusIndex = 4;
            
            $status = $statuses[$statusIndex];
            $totalRequired = $payment->amount_required;
            $amountPaid = 0;

            switch ($status) {
                case 'Paid':
                    $amountPaid = $totalRequired;
                    break;
                case 'Partial':
                    $amountPaid = $totalRequired * 0.6; // 60% paid
                    break;
                case 'Overpaid':
                    $amountPaid = $totalRequired + 25000; // Extra 25k
                    break;
                case 'Pending':
                    $amountPaid = 0;
                    break;
                case 'Incomplete Payment':
                    $amountPaid = $totalRequired * 0.2; // 20% paid
                    break;
            }

            // Update payment record
            DB::table('payments')
                ->where('paymentID', $payment->paymentID)
                ->update([
                    'amount_paid' => $amountPaid,
                    'balance' => max(0, $totalRequired - $amountPaid),
                    'debt' => ($amountPaid < $totalRequired) ? ($totalRequired - $amountPaid) : 0,
                    'payment_status' => $status,
                    'payment_date' => $amountPaid > 0 ? Carbon::now() : null,
                    'updated_at' => Carbon::now(),
                ]);

            // Now update individual fee records (student_fee_payments)
            // We need to distribute $amountPaid across fees for this student
            if ($amountPaid > 0) {
                $sfps = DB::table('student_fee_payments')
                    ->where('studentID', $payment->studentID)
                    ->where('schoolID', $schoolID)
                    ->get();
                
                $remaining = $amountPaid;
                foreach ($sfps as $sfp) {
                    $pay = min($remaining, $sfp->fee_total_amount);
                    DB::table('student_fee_payments')
                        ->where('payment_detail_id', $sfp->payment_detail_id)
                        ->update([
                            'paymentID' => $payment->paymentID,
                            'amount_paid' => $pay,
                            'balance' => $sfp->fee_total_amount - $pay,
                            'last_payment_date' => Carbon::now()
                        ]);
                    $remaining -= $pay;
                    if ($remaining <= 0) break;
                }
            } else {
                // Reset to 0 for Pending
                DB::table('student_fee_payments')
                    ->where('studentID', $payment->studentID)
                    ->where('schoolID', $schoolID)
                    ->update([
                        'amount_paid' => 0,
                        'balance' => DB::raw('fee_total_amount'),
                        'last_payment_date' => null
                    ]);
            }
        }
    }
}
