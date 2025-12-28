<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            RealisticTransactionSeeder::class, // Use this one!
            // PopulateCustomerDemographicsSeeder::class, // Not needed - demographics already set in RealisticTransactionSeeder
            $this->call(CohortRetentionSeeder::class),
        ]);
    }
}
