<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $branches = Branch::all();

        foreach ($branches as $branch) {
            foreach ($products as $product) {
                // Attach product to branch with random stock
                $branch->products()->attach($product->id, [
                    'quantity' => rand(50, 200),
                    'low_stock_threshold' => 20,
                    'branch_price' => $product->price, // or null to use default
                    'is_available' => true,
                ]);
            }
        }
    }
}
