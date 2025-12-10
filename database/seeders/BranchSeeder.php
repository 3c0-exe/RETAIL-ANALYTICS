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
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12.00,
                'currency' => 'PHP',
                'status' => 'active',
            ],
            [
                'name' => 'Cebu Branch',
                'code' => 'CEB',
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12.00,
                'currency' => 'PHP',
                'status' => 'active',
            ],
            [
                'name' => 'Davao Branch',
                'code' => 'DVO',
                'timezone' => 'Asia/Manila',
                'tax_rate' => 12.00,
                'currency' => 'PHP',
                'status' => 'active',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
