<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Manila Branch',
                'code' => 'MNL',
                'address' => '123 Rizal Avenue, Manila',
                'city' => 'Manila',
                'phone' => '+63 2 1234 5678',
                'email' => 'manila@retail.com',
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12,
                'currency' => 'PHP',
                'is_active' => true,
            ],
            [
                'name' => 'Cebu Branch',
                'code' => 'CEB',
                'address' => '456 Osmena Boulevard, Cebu City',
                'city' => 'Cebu',
                'phone' => '+63 32 987 6543',
                'email' => 'cebu@retail.com',
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12,
                'currency' => 'PHP',
                'is_active' => true,
            ],
            [
                'name' => 'Davao Branch',
                'code' => 'DVO',
                'address' => '789 San Pedro Street, Davao City',
                'city' => 'Davao',
                'phone' => '+63 82 555 1234',
                'email' => 'davao@retail.com',
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12,
                'currency' => 'PHP',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
