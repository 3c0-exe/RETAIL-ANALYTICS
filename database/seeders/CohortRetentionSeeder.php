<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CohortRetentionSeeder extends Seeder
{
    /**
     * Add cohort dates to existing customers and align transaction timestamps
     * DOES NOT delete any data - only updates dates for cohort analysis
     */
    public function run(): void
    {
        $this->command->info('🎯 Adding cohort retention data to existing customers...');
        $this->command->info('⚠️  This preserves all transactions and forecasts');

        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->error('❌ No customers found. Run RealisticTransactionSeeder first.');
            return;
        }

        $totalCustomers = $customers->count();
        $this->command->info("📊 Found {$totalCustomers} customers");

        // Generate cohort distribution (18 months)
        $cohorts = $this->generateCohortDistribution($totalCustomers);
        $customerIndex = 0;

        foreach ($cohorts as $cohortData) {
            $cohortSize = $cohortData['size'];
            $cohortMonth = $cohortData['month'];

            $this->command->info("📅 Cohort {$cohortMonth}: {$cohortSize} customers");

            for ($i = 0; $i < $cohortSize && $customerIndex < $totalCustomers; $i++) {
                $customer = $customers[$customerIndex];

                // Random date within the cohort month
                $createdAt = Carbon::parse($cohortMonth . '-01')
                    ->addDays(rand(0, 27))
                    ->addHours(rand(8, 21))
                    ->addMinutes(rand(0, 59));

                // Update customer created_at
                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update(['created_at' => $createdAt]);

                $customerIndex++;
            }
        }

        $this->command->info("✅ Updated {$customerIndex} customers with cohort dates");

        // Now align transaction timestamps with customer cohorts
        $this->alignTransactionTimestamps();
    }

    /**
     * Align transaction timestamps so they occur AFTER customer acquisition
     */
    private function alignTransactionTimestamps(): void
    {
        $this->command->info('📝 Aligning transaction timestamps with customer cohorts...');

        $customers = Customer::with('transactions')->get();
        $updatedCount = 0;

        foreach ($customers as $customer) {
            if ($customer->transactions->isEmpty()) {
                continue;
            }

            $customerCreatedAt = Carbon::parse($customer->created_at);
            $now = Carbon::now();

            // Get all customer transactions ordered by current timestamp
            $transactions = $customer->transactions->sortBy('timestamp')->values();

            foreach ($transactions as $index => $transaction) {
                $currentTimestamp = Carbon::parse($transaction->timestamp);

                // If transaction is BEFORE customer acquisition, move it forward
                if ($currentTimestamp->lt($customerCreatedAt)) {
                    // First transaction = acquisition date + random hours
                    if ($index === 0) {
                        $newTimestamp = $customerCreatedAt->copy()
                            ->addHours(rand(1, 8))
                            ->addMinutes(rand(0, 59));
                    } else {
                        // Subsequent transactions = days/weeks after acquisition
                        $daysAfter = min($index * rand(3, 14), $customerCreatedAt->diffInDays($now));
                        $newTimestamp = $customerCreatedAt->copy()
                            ->addDays($daysAfter)
                            ->addHours(rand(8, 21))
                            ->addMinutes(rand(0, 59));
                    }

                    // Don't set future dates
                    if ($newTimestamp->lte($now)) {
                        DB::table('transactions')
                            ->where('id', $transaction->id)
                            ->update(['timestamp' => $newTimestamp]);
                        $updatedCount++;
                    }
                }
            }
        }

        $this->command->info("✅ Updated {$updatedCount} transaction timestamps");
        $this->command->info('✅ Cohort retention data ready! Forecasts preserved.');
    }

    /**
     * Generate cohort distribution (18 months back from now)
     */
    private function generateCohortDistribution(int $totalCustomers): array
    {
        $cohorts = [];
        $now = Carbon::now();

        $baseSize = floor($totalCustomers / 18);
        $remainder = $totalCustomers % 18;

        for ($i = 17; $i >= 0; $i--) {
            $cohortMonth = $now->copy()->subMonths($i)->format('Y-m');

            // Add remainder to recent months
            $extraCustomers = ($i < $remainder) ? 1 : 0;

            // Growth pattern (older = smaller, newer = larger)
            $growthMultiplier = 0.7 + ($i * 0.017);

            $size = max(1, round($baseSize * $growthMultiplier) + $extraCustomers);

            $cohorts[] = [
                'month' => $cohortMonth,
                'size' => $size,
                'months_ago' => $i
            ];
        }

        // Adjust to match exact total
        $totalAssigned = array_sum(array_column($cohorts, 'size'));
        $difference = $totalCustomers - $totalAssigned;

        if ($difference != 0) {
            $cohorts[count($cohorts) - 1]['size'] += $difference;
        }

        return $cohorts;
    }
}
