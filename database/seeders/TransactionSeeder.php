<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();
        $cashiers = User::whereIn('role', ['admin', 'branch_manager'])->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please seed branches and products first!');
            return;
        }

        $this->command->info('Generating 500 transactions over 90 days...');

        // Payment methods distribution
        $paymentMethods = [
            'cash' => 50,
            'credit_card' => 30,
            'debit_card' => 15,
            'gcash' => 5,
        ];

        // Generate 500 transactions
        for ($i = 0; $i < 500; $i++) {
            // Random date in last 90 days
            $date = Carbon::now()->subDays(rand(0, 90))
                ->setHour(rand(8, 20))
                ->setMinute(rand(0, 59));

            // Pick random branch
            $branch = $branches->random();

            // Pick cashier from that branch or admin
            $cashier = $cashiers->where('branch_id', $branch->id)->first()
                ?? $cashiers->where('role', 'admin')->first();

            // Random payment method
            $paymentMethod = $this->weightedRandom($paymentMethods);

            // Create or get random customer (80% have customers)
            $customer = null;
            if (rand(1, 100) <= 80) {
                $customer = Customer::inRandomOrder()->first() ?? Customer::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone' => fake()->phoneNumber(),
                    'loyalty_id' => 'LOY' . str_pad(Customer::count() + 1, 6, '0', STR_PAD_LEFT),
                ]);
            }

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => 'TXN' . now()->format('Ymd') . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'branch_id' => $branch->id,
                'customer_id' => $customer?->id,
                'cashier_id' => $cashier?->id,
                'transaction_date' => $date,
                'timestamp' => $date,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'subtotal' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
            ]);

            // Add 1-5 random products
            $itemCount = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->price;
                $discount = rand(0, 1) ? rand(0, 50) : 0; // 50% chance of discount
                $itemSubtotal = ($quantity * $unitPrice) - $discount;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'subtotal' => $itemSubtotal,
                ]);

                $subtotal += $itemSubtotal;
            }

            // Calculate tax and total
            $tax = $subtotal * 0.12; // 12% VAT
            $total = $subtotal + $tax;

            // Update transaction totals
            $transaction->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            if ($i % 50 == 0) {
                $this->command->info("Generated {$i} transactions...");
            }
        }

        $this->command->info('✅ Successfully generated 500 transactions!');

        // Update customer stats
        $this->updateCustomerStats();
    }

    private function weightedRandom(array $weights): string
    {
        $rand = rand(1, array_sum($weights));
        foreach ($weights as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) return $key;
        }
        return array_key_first($weights);
    }

    private function updateCustomerStats(): void
    {
        $this->command->info('Updating customer statistics...');

        $customers = Customer::all();
        foreach ($customers as $customer) {
            $transactions = Transaction::where('customer_id', $customer->id)->get();

            $customer->update([
                'total_spend' => $transactions->sum('total'),
                'visit_count' => $transactions->count(),
                'last_purchase_at' => $transactions->max('transaction_date'),
            ]);
        }

        $this->command->info('✅ Customer stats updated!');
    }
}
