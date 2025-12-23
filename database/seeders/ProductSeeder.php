<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🛒 Creating realistic demographic-based products...');

        // Create categories
        $categories = [
            'Beverages',
            'Snacks',
            'Household',
            'Personal Care',
            'Electronics',
            'Health & Medicine',
            'Cosmetics',
            'Feminine Care',
            'Men\'s Care',
            'Groceries',
            'Baby Products',
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat] = Category::firstOrCreate(['name' => $cat]);
        }

        // Realistic products with demographic targeting
        $products = [
            // ========== BEVERAGES (All Ages) ==========
            ['name' => 'Coca Cola 1.5L', 'category' => 'Beverages', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Pepsi 1.5L', 'category' => 'Beverages', 'price' => 60.00, 'cost' => 42.00],
            ['name' => 'Mountain Dew 1.5L', 'category' => 'Beverages', 'price' => 62.00, 'cost' => 43.00],
            ['name' => 'Sprite 1.5L', 'category' => 'Beverages', 'price' => 60.00, 'cost' => 42.00],
            ['name' => 'Red Bull Energy Drink', 'category' => 'Beverages', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Coffee 3-in-1 (10 sachets)', 'category' => 'Beverages', 'price' => 95.00, 'cost' => 65.00],
            ['name' => 'Bottled Water 1L', 'category' => 'Beverages', 'price' => 25.00, 'cost' => 15.00],
            ['name' => 'Orange Juice 1L', 'category' => 'Beverages', 'price' => 85.00, 'cost' => 60.00],

            // ========== SNACKS (Young Adults 18-35) ==========
            ['name' => 'Piattos Cheese', 'category' => 'Snacks', 'price' => 25.00, 'cost' => 18.00],
            ['name' => 'Nova BBQ', 'category' => 'Snacks', 'price' => 20.00, 'cost' => 14.00],
            ['name' => 'Chippy BBQ', 'category' => 'Snacks', 'price' => 22.00, 'cost' => 16.00],
            ['name' => 'Oishi Prawn Crackers', 'category' => 'Snacks', 'price' => 28.00, 'cost' => 20.00],
            ['name' => 'Clover Chips', 'category' => 'Snacks', 'price' => 24.00, 'cost' => 17.00],
            ['name' => 'Instant Noodles - Beef', 'category' => 'Snacks', 'price' => 15.00, 'cost' => 10.00],
            ['name' => 'Instant Noodles - Chicken', 'category' => 'Snacks', 'price' => 15.00, 'cost' => 10.00],
            ['name' => 'Chocolate Bar', 'category' => 'Snacks', 'price' => 35.00, 'cost' => 25.00],
            ['name' => 'Candy - Assorted', 'category' => 'Snacks', 'price' => 18.00, 'cost' => 12.00],

            // ========== GROCERIES (Adults 26-55+) ==========
            ['name' => 'Rice 5kg', 'category' => 'Groceries', 'price' => 250.00, 'cost' => 180.00],
            ['name' => 'Cooking Oil 1L', 'category' => 'Groceries', 'price' => 120.00, 'cost' => 85.00],
            ['name' => 'Sugar 1kg', 'category' => 'Groceries', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Salt 1kg', 'category' => 'Groceries', 'price' => 25.00, 'cost' => 18.00],
            ['name' => 'Eggs (12pcs)', 'category' => 'Groceries', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Bread Loaf', 'category' => 'Groceries', 'price' => 55.00, 'cost' => 38.00],
            ['name' => 'Canned Tuna', 'category' => 'Groceries', 'price' => 45.00, 'cost' => 32.00],
            ['name' => 'Canned Corned Beef', 'category' => 'Groceries', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Soy Sauce 1L', 'category' => 'Groceries', 'price' => 75.00, 'cost' => 52.00],
            ['name' => 'Vinegar 1L', 'category' => 'Groceries', 'price' => 45.00, 'cost' => 32.00],

            // ========== HOUSEHOLD (Adults 26-55) ==========
            ['name' => 'Tide Powder 1kg', 'category' => 'Household', 'price' => 120.00, 'cost' => 85.00],
            ['name' => 'Surf Powder 1kg', 'category' => 'Household', 'price' => 110.00, 'cost' => 80.00],
            ['name' => 'Joy Dishwashing Liquid 500ml', 'category' => 'Household', 'price' => 55.00, 'cost' => 40.00],
            ['name' => 'Fabric Conditioner 1L', 'category' => 'Household', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Toilet Tissue (4 rolls)', 'category' => 'Household', 'price' => 75.00, 'cost' => 52.00],
            ['name' => 'Garbage Bags (20pcs)', 'category' => 'Household', 'price' => 95.00, 'cost' => 65.00],
            ['name' => 'Multi-Purpose Cleaner', 'category' => 'Household', 'price' => 85.00, 'cost' => 60.00],

            // ========== PERSONAL CARE (All Ages) ==========
            ['name' => 'Safeguard Soap (3 bars)', 'category' => 'Personal Care', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Colgate 150g', 'category' => 'Personal Care', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Shampoo 200ml', 'category' => 'Personal Care', 'price' => 125.00, 'cost' => 88.00],
            ['name' => 'Conditioner 200ml', 'category' => 'Personal Care', 'price' => 125.00, 'cost' => 88.00],
            ['name' => 'Toothbrush', 'category' => 'Personal Care', 'price' => 35.00, 'cost' => 25.00],
            ['name' => 'Deodorant Roll-on', 'category' => 'Personal Care', 'price' => 95.00, 'cost' => 65.00],
            ['name' => 'Body Wash 250ml', 'category' => 'Personal Care', 'price' => 155.00, 'cost' => 110.00],
            ['name' => 'Face Wash 100ml', 'category' => 'Personal Care', 'price' => 145.00, 'cost' => 100.00],

            // ========== COSMETICS & BEAUTY (Female 18-45) ==========
            ['name' => 'Lipstick - Red', 'category' => 'Cosmetics', 'price' => 185.00, 'cost' => 130.00],
            ['name' => 'Lipstick - Pink', 'category' => 'Cosmetics', 'price' => 185.00, 'cost' => 130.00],
            ['name' => 'Face Powder', 'category' => 'Cosmetics', 'price' => 195.00, 'cost' => 135.00],
            ['name' => 'Makeup Remover', 'category' => 'Cosmetics', 'price' => 125.00, 'cost' => 88.00],
            ['name' => 'Face Cream - Day', 'category' => 'Cosmetics', 'price' => 245.00, 'cost' => 170.00],
            ['name' => 'Face Cream - Night', 'category' => 'Cosmetics', 'price' => 265.00, 'cost' => 185.00],
            ['name' => 'Moisturizer 100ml', 'category' => 'Cosmetics', 'price' => 195.00, 'cost' => 135.00],
            ['name' => 'Sunscreen SPF50', 'category' => 'Cosmetics', 'price' => 285.00, 'cost' => 200.00],
            ['name' => 'Eyeliner', 'category' => 'Cosmetics', 'price' => 165.00, 'cost' => 115.00],
            ['name' => 'Mascara', 'category' => 'Cosmetics', 'price' => 195.00, 'cost' => 135.00],

            // ========== FEMININE CARE (Female All Ages) ==========
            ['name' => 'Sanitary Pads - Day (8pcs)', 'category' => 'Feminine Care', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Sanitary Pads - Night (6pcs)', 'category' => 'Feminine Care', 'price' => 75.00, 'cost' => 52.00],
            ['name' => 'Pantyliners (20pcs)', 'category' => 'Feminine Care', 'price' => 55.00, 'cost' => 38.00],

            // ========== MEN'S CARE (Male 18-55) ==========
            ['name' => 'Shaving Cream 200ml', 'category' => 'Men\'s Care', 'price' => 125.00, 'cost' => 88.00],
            ['name' => 'Razor - Disposable (5pcs)', 'category' => 'Men\'s Care', 'price' => 85.00, 'cost' => 60.00],
            ['name' => 'Aftershave Lotion', 'category' => 'Men\'s Care', 'price' => 145.00, 'cost' => 100.00],
            ['name' => 'Hair Wax - Strong Hold', 'category' => 'Men\'s Care', 'price' => 165.00, 'cost' => 115.00],
            ['name' => 'Hair Gel 100ml', 'category' => 'Men\'s Care', 'price' => 85.00, 'cost' => 60.00],

            // ========== HEALTH & MEDICINE (46-55+) ==========
            ['name' => 'Multivitamins (30 tablets)', 'category' => 'Health & Medicine', 'price' => 285.00, 'cost' => 200.00],
            ['name' => 'Vitamin C (30 tablets)', 'category' => 'Health & Medicine', 'price' => 195.00, 'cost' => 135.00],
            ['name' => 'Pain Reliever (10 tablets)', 'category' => 'Health & Medicine', 'price' => 65.00, 'cost' => 45.00],
            ['name' => 'Cold & Flu Medicine (10 tablets)', 'category' => 'Health & Medicine', 'price' => 125.00, 'cost' => 88.00],
            ['name' => 'Bandages (20pcs)', 'category' => 'Health & Medicine', 'price' => 45.00, 'cost' => 32.00],
            ['name' => 'First Aid Kit', 'category' => 'Health & Medicine', 'price' => 345.00, 'cost' => 240.00],

            // ========== BABY PRODUCTS (Adults 26-45) ==========
            ['name' => 'Baby Diapers (Small - 10pcs)', 'category' => 'Baby Products', 'price' => 185.00, 'cost' => 130.00],
            ['name' => 'Baby Wipes (80pcs)', 'category' => 'Baby Products', 'price' => 95.00, 'cost' => 65.00],
            ['name' => 'Baby Powder 200g', 'category' => 'Baby Products', 'price' => 125.00, 'cost' => 88.00],

            // ========== ELECTRONICS ==========
            ['name' => 'AA Batteries (4pcs)', 'category' => 'Electronics', 'price' => 95.00, 'cost' => 70.00],
            ['name' => 'USB Cable Type-C', 'category' => 'Electronics', 'price' => 150.00, 'cost' => 100.00],
            ['name' => 'Phone Charger', 'category' => 'Electronics', 'price' => 195.00, 'cost' => 135.00],
        ];

        $createdCount = 0;
        foreach ($products as $prod) {
            $category = $categoryModels[$prod['category']];

            Product::create([
                'name' => $prod['name'],
                'category_id' => $category->id,
                'price' => $prod['price'],
                'cost' => $prod['cost'],
                'status' => 'active',
            ]);
            $createdCount++;
        }

        $this->command->info("✅ Created {$createdCount} realistic demographic-based products!");
    }
}
