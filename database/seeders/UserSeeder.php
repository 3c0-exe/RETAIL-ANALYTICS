<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => null,
            'theme' => 'dark',
            'email_verified_at' => now(),
        ]);

        // Get branches
        $manila = Branch::where('code', 'MNL')->first();
        $cebu = Branch::where('code', 'CEB')->first();
        $davao = Branch::where('code', 'DVO')->first();

        // Branch Managers
        $manilaMgr = User::create([
            'name' => 'Manila Manager',
            'email' => 'manila@test.com',
            'password' => Hash::make('password'),
            'role' => 'branch_manager',
            'branch_id' => $manila->id,
            'theme' => 'light',
            'email_verified_at' => now(),
        ]);

        $cebuMgr = User::create([
            'name' => 'Cebu Manager',
            'email' => 'cebu@test.com',
            'password' => Hash::make('password'),
            'role' => 'branch_manager',
            'branch_id' => $cebu->id,
            'theme' => 'light',
            'email_verified_at' => now(),
        ]);

        $davaoMgr = User::create([
            'name' => 'Davao Manager',
            'email' => 'davao@test.com',
            'password' => Hash::make('password'),
            'role' => 'branch_manager',
            'branch_id' => $davao->id,
            'theme' => 'light',
            'email_verified_at' => now(),
        ]);

        // Update branches with managers
        $manila->update(['manager_id' => $manilaMgr->id]);
        $cebu->update(['manager_id' => $cebuMgr->id]);
        $davao->update(['manager_id' => $davaoMgr->id]);

        // Analyst User
        User::create([
            'name' => 'Analyst User',
            'email' => 'analyst@test.com',
            'password' => Hash::make('password'),
            'role' => 'analyst',
            'branch_id' => null,
            'theme' => 'light',
            'email_verified_at' => now(),
        ]);

        // Viewer User
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@test.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'branch_id' => $manila->id,
            'theme' => 'light',
            'email_verified_at' => now(),
        ]);
    }
}
