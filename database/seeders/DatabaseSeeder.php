<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ========================================================================
        // ✅ ESSENTIAL SEEDERS - Required for system functionality
        // ========================================================================
        $this->call([
            BranchSeeder::class,        // Creates branches (Manila, Cebu, Davao)
            PermissionSeeder::class,     // Creates permissions & roles (Admin, Analyst, etc.)
            UserSeeder::class,           // Creates test users (admin@test.com, etc.)
        ]);

        // ========================================================================
        // ❌ DEMO DATA SEEDERS - Commented out (use CSV imports instead)
        // ========================================================================
        // $this->call([
        //     ProductSeeder::class,
        //     RealisticTransactionSeeder::class,
        //     PopulateCustomerDemographicsSeeder::class,
        //     CohortRetentionSeeder::class,
        //     HistoricalTransactionSeeder::class,
        //     LinkTransactionItemsToProductsSeeder::class,
        //     AssignProductCategoriesSeeder::class,
        // ]);

        $this->command->info('');
        $this->command->info('✅ Essential data seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Next steps:');
        $this->command->info('   1. Login with: admin@test.com / password');
        $this->command->info('   2. Go to Admin > Imports');
        $this->command->info('   3. Upload your CSV files to populate real data');
        $this->command->info('');
        $this->command->info('🔧 Use the Role Editor to customize permissions as needed.');
        $this->command->info('');
    }
}
