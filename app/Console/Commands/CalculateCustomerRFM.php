<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalculateCustomerRFM extends Command
{
    protected $signature = 'customers:calculate-rfm';
    protected $description = 'Calculate RFM scores for all customers';

    public function handle()
    {
        $this->info('Calculating RFM scores for customers...');

        $customers = Customer::all();
        $bar = $this->output->createProgressBar($customers->count());

        foreach ($customers as $customer) {
            // Get customer's transactions
            $transactions = Transaction::where('customer_id', $customer->id)->get();

            if ($transactions->isEmpty()) {
                $customer->update([
                    'rfm_score' => ['recency' => 1, 'frequency' => 1, 'monetary' => 1],
                    'segment' => 'dormant'
                ]);
                $bar->advance();
                continue;
            }

            // Calculate Recency (days since last purchase)
            $lastPurchase = $transactions->max('transaction_date');
            $daysSinceLastPurchase = Carbon::parse($lastPurchase)->diffInDays(now());

            // Calculate Frequency (number of purchases)
            $frequency = $transactions->count();

            // Calculate Monetary (total spend)
            $monetary = $transactions->sum('total');

            // Score Recency (1-5, lower days = higher score)
            if ($daysSinceLastPurchase <= 7) {
                $recencyScore = 5;
            } elseif ($daysSinceLastPurchase <= 30) {
                $recencyScore = 4;
            } elseif ($daysSinceLastPurchase <= 60) {
                $recencyScore = 3;
            } elseif ($daysSinceLastPurchase <= 90) {
                $recencyScore = 2;
            } else {
                $recencyScore = 1;
            }

            // Score Frequency (1-5)
            if ($frequency >= 20) {
                $frequencyScore = 5;
            } elseif ($frequency >= 10) {
                $frequencyScore = 4;
            } elseif ($frequency >= 5) {
                $frequencyScore = 3;
            } elseif ($frequency >= 2) {
                $frequencyScore = 2;
            } else {
                $frequencyScore = 1;
            }

            // Score Monetary (1-5)
            if ($monetary >= 50000) {
                $monetaryScore = 5;
            } elseif ($monetary >= 20000) {
                $monetaryScore = 4;
            } elseif ($monetary >= 10000) {
                $monetaryScore = 3;
            } elseif ($monetary >= 5000) {
                $monetaryScore = 2;
            } else {
                $monetaryScore = 1;
            }

            // Determine Segment
            $totalScore = $recencyScore + $frequencyScore + $monetaryScore;

            if ($totalScore >= 13 && $monetaryScore >= 4) {
                $segment = 'vip';
            } elseif ($totalScore >= 10 && $frequencyScore >= 4) {
                $segment = 'loyal';
            } elseif ($recencyScore <= 2 && $frequencyScore >= 3) {
                $segment = 'at_risk';
            } elseif ($frequencyScore == 1 && $recencyScore >= 4) {
                $segment = 'new';
            } elseif ($recencyScore == 1) {
                $segment = 'dormant';
            } else {
                $segment = 'regular';
            }

            // Update customer
            $customer->update([
                'total_spent' => $monetary,
                'visit_count' => $frequency,
                'last_visit_date' => $lastPurchase,
                'rfm_score' => [
                    'recency' => $recencyScore,
                    'frequency' => $frequencyScore,
                    'monetary' => $monetaryScore
                ],
                'segment' => $segment
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('RFM calculation completed!');

        // Show summary
        $this->table(
            ['Segment', 'Count'],
            Customer::select('segment', DB::raw('count(*) as count'))
                ->groupBy('segment')
                ->get()
                ->map(fn($s) => [$s->segment, $s->count])
        );
    }
}
