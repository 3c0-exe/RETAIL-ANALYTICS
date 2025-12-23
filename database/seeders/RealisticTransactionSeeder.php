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

class RealisticTransactionSeeder extends Seeder
{
    private $popularProducts = [];
    private $mediumProducts = [];
    private $slowProducts = [];

    public function run(): void
    {
        $branches = Branch::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please seed branches and products first!');
            return;
        }

        // Categorize products by popularity (creates consistent patterns)
        $this->categorizeProducts($products);

        // Generate 90 days of realistic data
        $startDate = Carbon::now()->subDays(90);
        $endDate = Carbon::now();

        $this->command->info('Generating 90 days of REALISTIC transaction data with patterns...');

        $transactionCount = 0;

        foreach ($branches as $branch) {
            $this->command->info("Processing {$branch->name}...");

            // Get cashiers
            $cashiers = User::where('branch_id', $branch->id)
                ->whereIn('role', ['branch_manager', 'cashier'])
                ->get();

            if ($cashiers->isEmpty()) {
                $cashiers = User::where('role', 'admin')->get();
            }

            // Create loyal customers for this branch (they shop regularly)
            $customers = $this->createLoyalCustomers($branch, 50);

            // Generate transactions day by day
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dailyTransactions = $this->generateDailyTransactions(
                    $branch,
                    $currentDate,
                    $cashiers,
                    $customers
                );

                $transactionCount += $dailyTransactions;
                $currentDate->addDay();
            }

            $this->command->info("✓ Generated transactions for {$branch->name}");
        }

        $this->command->info("✅ Total transactions generated: {$transactionCount}");
        $this->command->info("🎯 Data has realistic patterns: weekends busier, popular products sold more, loyal customers return regularly");
    }

    /**
     * Categorize products into popularity tiers (creates learning patterns)
     */
    private function categorizeProducts($products)
    {
        $total = $products->count();
        $popularCount = max(1, (int) ($total * 0.2));  // Top 20% - Always sold
        $mediumCount = max(1, (int) ($total * 0.5));   // Middle 50% - Often sold

        $shuffled = $products->shuffle();
        $this->popularProducts = $shuffled->take($popularCount);
        $this->mediumProducts = $shuffled->slice($popularCount, $mediumCount);
        $this->slowProducts = $shuffled->slice($popularCount + $mediumCount);
    }

    /**
     * Create loyal customers who shop regularly (realistic behavior)
     */
    private function createLoyalCustomers($branch, $count)
    {
        $customers = collect();

        for ($i = 0; $i < $count; $i++) {
            $customer = Customer::firstOrCreate(
                ['email' => "customer{$i}_{$branch->id}@example.com"],
                [
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'loyalty_id' => 'CUST' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'total_spend' => 0,
                    'visit_count' => 0,
                    'segment' => 'regular',
                ]
            );
            $customers->push($customer);
        }

        return $customers;
    }

    /**
     * Generate transactions for a single day with realistic patterns
     */
    private function generateDailyTransactions($branch, $date, $cashiers, $customers)
    {
        $dayOfWeek = $date->dayOfWeek;
        $isWeekend = in_array($dayOfWeek, [0, 6]); // Sunday = 0, Saturday = 6
        $isMonday = $dayOfWeek === 1;

        // Realistic transaction counts:
        // Weekends: 35-55 transactions (busy)
        // Monday: 15-25 transactions (slow start)
        // Other weekdays: 25-40 transactions (normal)
        if ($isWeekend) {
            $transactionsToday = rand(35, 55);
        } elseif ($isMonday) {
            $transactionsToday = rand(15, 25);
        } else {
            $transactionsToday = rand(25, 40);
        }

        // Only generate up to current hour if today
        $maxHour = $date->isToday() ? min(Carbon::now()->hour, 21) : 21;
        if ($maxHour < 8) return 0; // Store not open yet

        $count = 0;

        for ($i = 0; $i < $transactionsToday; $i++) {
            // Realistic shopping hours with rush periods
            $hour = $this->getRealisticShoppingHour($maxHour);
            $minute = rand(0, 59);

            $timestamp = $date->copy()
                ->setHour($hour)
                ->setMinute($minute)
                ->setSecond(rand(0, 59));

            // Skip if future
            if ($timestamp->isFuture()) continue;

            // 70% loyal customers, 30% walk-ins
            $customer = rand(0, 100) > 30 ? $customers->random() : null;

            // Create transaction
            $cashier = $cashiers->random();

            $transaction = Transaction::create([
                'transaction_code' => 'TXN' . $branch->code . $timestamp->format('YmdHis') . rand(100, 999),
                'branch_id' => $branch->id,
                'timestamp' => $timestamp,
                'cashier_id' => $cashier->id,
                'customer_id' => $customer?->id,
                'payment_method' => $this->getRealisticPaymentMethod(),
                'status' => rand(0, 100) > 3 ? 'completed' : 'refunded', // 97% success rate
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
            ]);

            // Add 1-4 items with realistic product selection
            $itemCount = $this->getRealisticItemCount();
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $this->selectRealisticProduct();
                $quantity = $this->getRealisticQuantity($product);
                $unitPrice = $product->price;

                // 15% chance of discount on medium/slow products
                $discount = 0;
                if (rand(0, 100) > 85 && !$this->popularProducts->contains($product)) {
                    $discount = rand(50, 200);
                }

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
            $taxRate = $branch->tax_rate ?? 0.12;
            $taxAmount = $subtotal * $taxRate;
            $total = $subtotal + $taxAmount;

            $transaction->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
            ]);

            // Update customer stats
            if ($customer && $transaction->status === 'completed') {
                $customer->increment('visit_count');
                $customer->increment('total_spend', $total);
                $customer->update(['last_purchase_at' => $timestamp]);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Realistic shopping hours with rush periods
     */
    private function getRealisticShoppingHour($maxHour)
    {
        // Rush hours: 12-1pm (lunch), 6-8pm (after work)
        $rushHours = [12, 13, 18, 19, 20];

        // 40% chance of rush hour, 60% normal distribution
        if (rand(0, 100) > 60 && $maxHour >= 12) {
            $validRushHours = array_filter($rushHours, fn($h) => $h <= $maxHour);
            if (!empty($validRushHours)) {
                return $validRushHours[array_rand($validRushHours)];
            }
        }

        // Normal hours: 8am - 9pm
        return rand(8, $maxHour);
    }

    /**
     * Realistic payment method distribution
     */
    private function getRealisticPaymentMethod()
    {
        $rand = rand(1, 100);

        if ($rand <= 45) return 'cash';           // 45%
        if ($rand <= 75) return 'card';           // 30%
        if ($rand <= 90) return 'gcash';          // 15%
        return 'paymaya';                          // 10%
    }

    /**
     * Realistic item count per transaction
     */
    private function getRealisticItemCount()
    {
        $rand = rand(1, 100);

        if ($rand <= 40) return 1;  // 40% - single item
        if ($rand <= 75) return 2;  // 35% - two items
        if ($rand <= 90) return 3;  // 15% - three items
        return 4;                    // 10% - four items
    }

    /**
     * Select product based on realistic popularity
     */
    private function selectRealisticProduct()
    {
        $rand = rand(1, 100);

        // 60% chance of popular products (bestsellers)
        if ($rand <= 60 && $this->popularProducts->isNotEmpty()) {
            return $this->popularProducts->random();
        }

        // 30% chance of medium products
        if ($rand <= 90 && $this->mediumProducts->isNotEmpty()) {
            return $this->mediumProducts->random();
        }

        // 10% chance of slow-moving products
        if ($this->slowProducts->isNotEmpty()) {
            return $this->slowProducts->random();
        }

        // Fallback
        return $this->popularProducts->random();
    }

    /**
     * Realistic quantity based on product type
     */
    private function getRealisticQuantity($product)
    {
        // Popular products: usually buy 1-2
        if ($this->popularProducts->contains($product)) {
            return rand(0, 100) > 70 ? 2 : 1;
        }

        // Medium products: usually buy 1-3
        if ($this->mediumProducts->contains($product)) {
            $rand = rand(1, 100);
            if ($rand <= 60) return 1;
            if ($rand <= 90) return 2;
            return 3;
        }

        // Slow products: usually buy 1
        return 1;
    }
}
