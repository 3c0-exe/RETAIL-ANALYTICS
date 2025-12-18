<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HistoricalTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please seed branches and products first!');
            return;
        }

        // Generate 60 days of historical data
        $startDate = Carbon::now()->subDays(60);
        $endDate = Carbon::now()->subDay(); // Up to yesterday

        $this->command->info('Generating 60 days of historical transactions...');

        $transactionCount = 0;

        foreach ($branches as $branch) {
            // Get cashiers for this branch (or use admin if none)
            $cashiers = User::where('branch_id', $branch->id)
                ->whereIn('role', ['branch_manager', 'cashier'])
                ->get();

            if ($cashiers->isEmpty()) {
                $cashiers = User::where('role', 'admin')->get();
            }

            // Get or create customers
            $customers = Customer::inRandomOrder()->limit(50)->get();
            if ($customers->isEmpty()) {
                // Create some customers
                for ($i = 0; $i < 50; $i++) {
                    $customers[] = Customer::create([
                        'name' => fake()->name(),
                        'email' => fake()->unique()->safeEmail(),
                        'phone' => fake()->phoneNumber(),
                        'loyalty_id' => 'CUST' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                        'total_spend' => 0,
                        'visit_count' => 0,
                        'segment' => 'new',
                    ]);
                }
                $customers = collect($customers);
            }

            // Generate transactions for each day
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                // Vary transactions by day of week
                $dayOfWeek = $currentDate->dayOfWeek;
                $isWeekend = in_array($dayOfWeek, [0, 6]); // Sunday = 0, Saturday = 6

                // More transactions on weekends
                $transactionsPerDay = $isWeekend
                    ? rand(30, 50)
                    : rand(15, 35);

                for ($i = 0; $i < $transactionsPerDay; $i++) {
                    // Random time during business hours (8 AM - 9 PM)
                    $hour = rand(8, 21);
                    $minute = rand(0, 59);
                    $timestamp = $currentDate->copy()
                        ->setHour($hour)
                        ->setMinute($minute)
                        ->setSecond(rand(0, 59));

                    // Create transaction
                    $cashier = $cashiers->random();
                    $customer = rand(0, 100) > 30 ? $customers->random() : null; // 70% have customer

                    $transaction = Transaction::create([
                        'transaction_code' => 'TXN' . $branch->code . $timestamp->format('YmdHis') . rand(100, 999),
                        'branch_id' => $branch->id,
                        'timestamp' => $timestamp,
                        'transaction_date' => $timestamp->format('Y-m-d'), // ✅ FIXED: Added this line
                        'cashier_id' => $cashier->id,
                        'customer_id' => $customer?->id,
                        'payment_method' => ['cash', 'card', 'gcash', 'paymaya'][rand(0, 3)],
                        'status' => rand(0, 100) > 5 ? 'completed' : 'refunded', // 95% completed
                        'subtotal' => 0, // Will calculate
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'total_amount' => 0, // ✅ FIXED: Changed 'total' to 'total_amount'
                    ]);

                    // Add 1-5 items per transaction
                    $itemCount = rand(1, 5);
                    $subtotal = 0;

                    for ($j = 0; $j < $itemCount; $j++) {
                        $product = $products->random();
                        $quantity = rand(1, 3);
                        $unitPrice = $product->price;
                        $discount = rand(0, 100) > 80 ? rand(5, 15) : 0; // 20% chance of discount
                        $itemSubtotal = ($unitPrice * $quantity) - $discount;

                        TransactionItem::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $product->id,
                            'sku' => $product->sku,
                            'product_name' => $product->name,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'discount' => $discount,
                            'subtotal' => $itemSubtotal,
                        ]);

                        $subtotal += $itemSubtotal;
                    }

                    // Calculate totals
                    $taxRate = $branch->tax_rate ?? 0.12; // Default 12% VAT
                    $taxAmount = $subtotal * $taxRate;
                    $total = $subtotal + $taxAmount;

                    $transaction->update([
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $total, // ✅ FIXED: Changed 'total' to 'total_amount'
                    ]);

                    // Update customer stats
                    if ($customer && $transaction->status === 'completed') {
                        $customer->increment('visit_count');
                        $customer->increment('total_spend', $total);
                        $customer->update(['last_purchase_at' => $timestamp]);
                    }

                    $transactionCount++;
                }

                $currentDate->addDay();
            }

            $this->command->info("✓ Generated transactions for {$branch->name}");
        }

        $this->command->info("✅ Total transactions generated: {$transactionCount}");
    }
}
