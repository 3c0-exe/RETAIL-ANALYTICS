<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories first
        $categories = [
            'Beverages',
            'Snacks',
            'Household',
            'Personal Care',
            'Electronics',
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = Category::firstOrCreate(['name' => $cat]);
            $categoryIds[] = $category->id;
        }

        // Sample products
        $products = [
            ['name' => 'Coca Cola 1.5L', 'category' => 'Beverages', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Pepsi 1.5L', 'category' => 'Beverages', 'price' => 60.00, 'cost' => 42.00],
            ['name' => 'Piattos Cheese', 'category' => 'Snacks', 'price' => 25.00, 'cost' => 18.00],
            ['name' => 'Nova BBQ', 'category' => 'Snacks', 'price' => 20.00, 'cost' => 14.00],
            ['name' => 'Tide Powder 1kg', 'category' => 'Household', 'price' => 120.00, 'cost' => 85.00],
            ['name' => 'Surf Powder 1kg', 'category' => 'Household', 'price' => 110.00, 'cost' => 80.00],
            ['name' => 'Safeguard Soap', 'category' => 'Personal Care', 'price' => 35.00, 'cost' => 25.00],
            ['name' => 'Colgate 150g', 'category' => 'Personal Care', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'AA Batteries (4pcs)', 'category' => 'Electronics', 'price' => 95.00, 'cost' => 70.00],
            ['name' => 'USB Cable Type-C', 'category' => 'Electronics', 'price' => 150.00, 'cost' => 100.00],
            ['name' => 'Mountain Dew 1.5L', 'category' => 'Beverages', 'price' => 62.00, 'cost' => 43.00],
            ['name' => 'Sprite 1.5L', 'category' => 'Beverages', 'price' => 60.00, 'cost' => 42.00],
            ['name' => 'Chippy BBQ', 'category' => 'Snacks', 'price' => 22.00, 'cost' => 16.00],
            ['name' => 'Oishi Prawn Crackers', 'category' => 'Snacks', 'price' => 28.00, 'cost' => 20.00],
            ['name' => 'Joy Dishwashing Liquid 500ml', 'category' => 'Household', 'price' => 55.00, 'cost' => 40.00],
        ];

        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();

            Product::create([
                'name' => $prod['name'],
                'category_id' => $category->id,
                'price' => $prod['price'],
                'cost' => $prod['cost'],
                'is_active' => true,
            ]);
        }
    }
}
