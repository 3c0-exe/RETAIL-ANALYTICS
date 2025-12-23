<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class PopulateCustomerDemographicsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Adding age and gender to existing customers...');

        $customers = Customer::all();
        $count = 0;

        foreach ($customers as $customer) {
            // Realistic age distribution
            $ageDistribution = [
                18 => 5,   // 5% under 18-25
                26 => 25,  // 25% 26-35
                36 => 30,  // 30% 36-45
                46 => 25,  // 25% 46-55
                56 => 15,  // 15% 56+
            ];

            $rand = rand(1, 100);
            $cumulative = 0;
            $age = 30; // default

            foreach ($ageDistribution as $minAge => $percentage) {
                $cumulative += $percentage;
                if ($rand <= $cumulative) {
                    // Generate age within range
                    if ($minAge === 18) {
                        $age = rand(18, 25);
                    } elseif ($minAge === 26) {
                        $age = rand(26, 35);
                    } elseif ($minAge === 36) {
                        $age = rand(36, 45);
                    } elseif ($minAge === 46) {
                        $age = rand(46, 55);
                    } else {
                        $age = rand(56, 75);
                    }
                    break;
                }
            }

            // Gender distribution: 48% male, 48% female, 4% other
            $genderRand = rand(1, 100);
            if ($genderRand <= 48) {
                $gender = 'male';
            } elseif ($genderRand <= 96) {
                $gender = 'female';
            } else {
                $gender = 'other';
            }

            $customer->update([
                'age' => $age,
                'gender' => $gender,
            ]);

            $count++;
        }

        $this->command->info("✅ Updated {$count} customers with demographics!");
    }
}
