<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class AssignProductCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Create default categories
        $electronics = Category::firstOrCreate(['name' => 'Electronics']);
        $beverages = Category::firstOrCreate(['name' => 'Beverages']);
        $snacks = Category::firstOrCreate(['name' => 'Snacks']);
        $groceries = Category::firstOrCreate(['name' => 'Groceries']);
        $homeAppliances = Category::firstOrCreate(['name' => 'Home Appliances']);
        $clothing = Category::firstOrCreate(['name' => 'Clothing']);

        // Get all products without category
        $productsWithoutCategory = Product::whereNull('category_id')->get();

        echo "Found {$productsWithoutCategory->count()} products without categories\n";

        // Randomly assign categories (for demo purposes)
        // In real scenario, you'd have logic based on product names
        $categories = [$electronics->id, $beverages->id, $snacks->id, $groceries->id, $homeAppliances->id, $clothing->id];

        foreach ($productsWithoutCategory as $product) {
            // Assign random category
            $randomCategoryId = $categories[array_rand($categories)];
            $product->update(['category_id' => $randomCategoryId]);
        }

        echo "All products assigned to categories!\n";
    }
}
