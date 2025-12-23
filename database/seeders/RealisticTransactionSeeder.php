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
use Illuminate\Support\Collection;

class RealisticTransactionSeeder extends Seeder
{
    private $popularProducts = [];
    private $mediumProducts = [];
    private $slowProducts = [];

    // Demographic-based product preferences
    private $demographicProducts = [];

    public function run(): void
    {
        $branches = Branch::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please seed branches and products first!');
            return;
        }

        // Categorize products by popularity AND demographics
        $this->categorizeProducts($products);
        $this->categorizeDemographicProducts($products);

        // Generate 90 days of realistic data
        $startDate = Carbon::now()->subDays(90);
        $endDate = Carbon::now();

        $this->command->info('🎯 Generating 90 days of REALISTIC demographic-based transaction data...');

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
        $this->command->info("🎯 Females buy cosmetics, males buy shaving products, young adults buy snacks!");
    }

    /**
     * Categorize products by demographics (age & gender)
     */
    private function categorizeDemographicProducts($products)
    {
        // Age-based categories
        $this->demographicProducts['age'] = [
            '18-25' => $products->filter(function($p) {
                return str_contains(strtolower($p->name), 'energy') ||
                       str_contains(strtolower($p->name), 'snack') ||
                       str_contains(strtolower($p->name), 'instant noodles') ||
                       str_contains(strtolower($p->name), 'chips') ||
                       str_contains(strtolower($p->name), 'candy') ||
                       str_contains(strtolower($p->name), 'chocolate');
            }),

            '26-35' => $products->filter(function($p) {
                return str_contains(strtolower($p->name), 'coffee') ||
                       str_contains(strtolower($p->name), 'bread') ||
                       str_contains(strtolower($p->name), 'eggs') ||
                       str_contains(strtolower($p->name), 'shampoo') ||
                       str_contains(strtolower($p->name), 'baby');
            }),

            '36-45' => $products->filter(function($p) {
                return str_contains(strtolower($p->name), 'rice') ||
                       str_contains(strtolower($p->name), 'cooking oil') ||
                       str_contains(strtolower($p->name), 'household') ||
                       str_contains(strtolower($p->name), 'groceries') ||
                       str_contains(strtolower($p->name), 'baby') ||
                       str_contains(strtolower($p->name), 'canned');
            }),

            '46-55' => $products->filter(function($p) {
                return str_contains(strtolower($p->name), 'rice') ||
                       str_contains(strtolower($p->name), 'vitamin') ||
                       str_contains(strtolower($p->name), 'medicine') ||
                       str_contains(strtolower($p->name), 'health');
            }),

            '56+' => $products->filter(function($p) {
                return str_contains(strtolower($p->name), 'vitamin') ||
                       str_contains(strtolower($p->name), 'medicine') ||
                       str_contains(strtolower($p->name), 'health') ||
                       str_contains(strtolower($p->name), 'pain') ||
                       str_contains(strtolower($p->name), 'rice');
            }),
        ];

        // Gender-based categories
        $this->demographicProducts['gender'] = [
            'female' => $products->filter(function($p) {
                $name = strtolower($p->name);
                return str_contains($name, 'lipstick') ||
                       str_contains($name, 'makeup') ||
                       str_contains($name, 'cosmetic') ||
                       str_contains($name, 'sanitary') ||
                       str_contains($name, 'pantyliner') ||
                       str_contains($name, 'feminine') ||
                       str_contains($name, 'face cream') ||
                       str_contains($name, 'moisturizer') ||
                       str_contains($name, 'sunscreen') ||
                       str_contains($name, 'mascara') ||
                       str_contains($name, 'eyeliner');
            }),

            'male' => $products->filter(function($p) {
                $name = strtolower($p->name);
                return str_contains($name, 'shaving') ||
                       str_contains($name, 'razor') ||
                       str_contains($name, 'aftershave') ||
                       str_contains($name, 'hair wax') ||
                       str_contains($name, 'hair gel');
            }),

            'other' => $products->filter(function($p) {
                $name = strtolower($p->name);
                return str_contains($name, 'shampoo') ||
                       str_contains($name, 'soap') ||
                       str_contains($name, 'toothpaste');
            }),
        ];
    }

    /**
     * Categorize products into popularity tiers
     */
    private function categorizeProducts($products)
    {
        $total = $products->count();
        $popularCount = max(1, (int) ($total * 0.2));
        $mediumCount = max(1, (int) ($total * 0.5));

        $shuffled = $products->shuffle();
        $this->popularProducts = $shuffled->take($popularCount);
        $this->mediumProducts = $shuffled->slice($popularCount, $mediumCount);
        $this->slowProducts = $shuffled->slice($popularCount + $mediumCount);
    }

    /**
     * Create loyal customers with demographics
     */
    private function createLoyalCustomers($branch, $count)
    {
        $customers = collect();

        for ($i = 0; $i < $count; $i++) {
            // Check if customer exists
            $email = "customer{$i}_{$branch->id}@example.com";
            $customer = Customer::where('email', $email)->first();

            if (!$customer) {
                // Create new customer with demographics
                $customer = Customer::create([
                    'email' => $email,
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'loyalty_id' => 'CUST' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'total_spent' => 0,
                    'visit_count' => 0,
                    'segment' => 'regular',
                    'age' => $this->getRealisticAge(),
                    'gender' => $this->getRealisticGender(),
                ]);
            } elseif (!$customer->age || !$customer->gender) {
                // Update existing customer with demographics if missing
                $customer->update([
                    'age' => $this->getRealisticAge(),
                    'gender' => $this->getRealisticGender(),
                ]);
            }

            $customers->push($customer);
        }

        return $customers;
    }

    /**
     * Generate realistic age distribution
     */
    private function getRealisticAge()
    {
        $rand = rand(1, 100);

        if ($rand <= 5) return rand(18, 25);   // 5% young adults
        if ($rand <= 30) return rand(26, 35);  // 25% adults
        if ($rand <= 60) return rand(36, 45);  // 30% middle-aged
        if ($rand <= 85) return rand(46, 55);  // 25% mature
        return rand(56, 75);                   // 15% seniors
    }

    /**
     * Generate realistic gender distribution
     */
    private function getRealisticGender()
    {
        $rand = rand(1, 100);

        if ($rand <= 48) return 'male';
        if ($rand <= 96) return 'female';
        return 'other';
    }

    /**
     * Generate transactions for a single day
     */
    private function generateDailyTransactions($branch, $date, $cashiers, $customers)
    {
        $dayOfWeek = $date->dayOfWeek;
        $isWeekend = in_array($dayOfWeek, [0, 6]);
        $isMonday = $dayOfWeek === 1;

        if ($isWeekend) {
            $transactionsToday = rand(35, 55);
        } elseif ($isMonday) {
            $transactionsToday = rand(15, 25);
        } else {
            $transactionsToday = rand(25, 40);
        }

        $maxHour = $date->isToday() ? min(Carbon::now()->hour, 21) : 21;
        if ($maxHour < 8) return 0;

        $count = 0;

        for ($i = 0; $i < $transactionsToday; $i++) {
            $hour = $this->getRealisticShoppingHour($maxHour);
            $minute = rand(0, 59);

            $timestamp = $date->copy()
                ->setHour($hour)
                ->setMinute($minute)
                ->setSecond(rand(0, 59));

            if ($timestamp->isFuture()) continue;

            // 70% loyal customers, 30% walk-ins
            $customer = rand(0, 100) > 30 ? $customers->random() : null;
            $cashier = $cashiers->random();

            $transaction = Transaction::create([
                'transaction_code' => 'TXN' . $branch->code . $timestamp->format('YmdHis') . rand(100, 999),
                'branch_id' => $branch->id,
                'timestamp' => $timestamp,
                'cashier_id' => $cashier->id,
                'customer_id' => $customer?->id,
                'payment_method' => $this->getRealisticPaymentMethod(),
                'status' => rand(0, 100) > 3 ? 'completed' : 'refunded',
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
            ]);

            // Add items based on customer demographics
            $itemCount = $this->getRealisticItemCount();
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $this->selectDemographicProduct($customer);
                $quantity = $this->getRealisticQuantity($product);
                $unitPrice = $product->price;

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

            $taxRate = $branch->tax_rate ?? 0.12;
            $taxAmount = $subtotal * $taxRate;
            $total = $subtotal + $taxAmount;

            $transaction->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
            ]);

            if ($customer && $transaction->status === 'completed') {
                $customer->increment('visit_count');
                $customer->increment('total_spent', $total);
                $customer->update(['last_visit_date' => $timestamp]);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Select product based on customer demographics
     */
    private function selectDemographicProduct($customer)
    {
        if (!$customer || !$customer->age || !$customer->gender) {
            return $this->selectRealisticProduct(); // Fallback to popularity
        }

        // Determine age group
        $ageGroup = $this->getAgeGroup($customer->age);

        // 50% chance to buy demographic-specific products
        if (rand(0, 100) > 50) {
            // Age-based preference
            $ageProducts = $this->demographicProducts['age'][$ageGroup] ?? collect();
            if ($ageProducts->isNotEmpty()) {
                return $ageProducts->random();
            }
        }

        // 30% chance to buy gender-specific products
        if (rand(0, 100) > 70) {
            $genderProducts = $this->demographicProducts['gender'][$customer->gender] ?? collect();
            if ($genderProducts->isNotEmpty()) {
                return $genderProducts->random();
            }
        }

        // Fallback to popularity-based selection
        return $this->selectRealisticProduct();
    }

    /**
     * Get age group from age
     */
    private function getAgeGroup($age)
    {
        if ($age >= 18 && $age <= 25) return '18-25';
        if ($age >= 26 && $age <= 35) return '26-35';
        if ($age >= 36 && $age <= 45) return '36-45';
        if ($age >= 46 && $age <= 55) return '46-55';
        return '56+';
    }

    // Keep all your existing helper methods below
    private function getRealisticShoppingHour($maxHour)
    {
        $rushHours = [12, 13, 18, 19, 20];
        if (rand(0, 100) > 60 && $maxHour >= 12) {
            $validRushHours = array_filter($rushHours, fn($h) => $h <= $maxHour);
            if (!empty($validRushHours)) {
                return $validRushHours[array_rand($validRushHours)];
            }
        }
        return rand(8, $maxHour);
    }

    private function getRealisticPaymentMethod()
    {
        $rand = rand(1, 100);
        if ($rand <= 45) return 'cash';
        if ($rand <= 75) return 'card';
        if ($rand <= 90) return 'gcash';
        return 'paymaya';
    }

    private function getRealisticItemCount()
    {
        $rand = rand(1, 100);
        if ($rand <= 40) return 1;
        if ($rand <= 75) return 2;
        if ($rand <= 90) return 3;
        return 4;
    }

    private function selectRealisticProduct()
    {
        $rand = rand(1, 100);
        if ($rand <= 60 && $this->popularProducts->isNotEmpty()) {
            return $this->popularProducts->random();
        }
        if ($rand <= 90 && $this->mediumProducts->isNotEmpty()) {
            return $this->mediumProducts->random();
        }
        if ($this->slowProducts->isNotEmpty()) {
            return $this->slowProducts->random();
        }
        return $this->popularProducts->random();
    }

    private function getRealisticQuantity($product)
    {
        if ($this->popularProducts->contains($product)) {
            return rand(0, 100) > 70 ? 2 : 1;
        }
        if ($this->mediumProducts->contains($product)) {
            $rand = rand(1, 100);
            if ($rand <= 60) return 1;
            if ($rand <= 90) return 2;
            return 3;
        }
        return 1;
    }
}
