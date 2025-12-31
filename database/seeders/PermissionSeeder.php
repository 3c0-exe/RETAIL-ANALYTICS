<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all modules and their permissions
        $modules = [
            'branches' => ['view', 'create', 'edit', 'delete'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'products' => ['view', 'create', 'edit', 'delete'],
            'imports' => ['view', 'create', 'edit', 'delete'],
            'sales_analytics' => ['view'],
            'customer_analytics' => ['view'],
            'forecasting' => ['view', 'create'],
            'reports' => ['view', 'create', 'edit', 'delete'],
            'settings' => ['view', 'edit'],
            'activity_logs' => ['view'],
            'error_logs' => ['view', 'delete'],
            'announcements' => ['view', 'create', 'edit', 'delete'],
            'backups' => ['view', 'create', 'delete'],
        ];

        // Create permissions
        foreach ($modules as $module => $actions) {
            $moduleName = Permission::getModules()[$module] ?? ucfirst(str_replace('_', ' ', $module));

            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    [
                        'display_name' => ucfirst($action) . ' ' . $moduleName,
                        'module' => $module,
                        'action' => $action,
                    ]
                );
            }
        }

        // Create default roles
        $this->createDefaultRoles();
    }

    private function createDefaultRoles(): void
    {
        // Admin Role (all permissions)
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_system' => true,
            ]
        );
        $admin->permissions()->sync(Permission::all()->pluck('id'));

        // Branch Manager Role
        $branchManager = Role::firstOrCreate(
            ['name' => 'branch_manager'],
            [
                'display_name' => 'Branch Manager',
                'description' => 'Manages branch operations and inventory',
                'is_system' => true,
            ]
        );
        $branchManagerPermissions = Permission::whereIn('name', [
            'products.view', 'products.edit', 'products.create',
            'categories.view',
            'sales_analytics.view',
            'customer_analytics.view',
            'forecasting.view',
            'reports.view', 'reports.create',
        ])->pluck('id');
        $branchManager->permissions()->sync($branchManagerPermissions);

        // Analyst Role
        $analyst = Role::firstOrCreate(
            ['name' => 'analyst'],
            [
                'display_name' => 'Analyst',
                'description' => 'View analytics and generate reports',
                'is_system' => true,
            ]
        );
        $analystPermissions = Permission::whereIn('name', [
            'branches.view',
            'products.view',
            'categories.view',
            'sales_analytics.view',
            'customer_analytics.view',
            'forecasting.view', 'forecasting.create',
            'reports.view', 'reports.create',
            'activity_logs.view',
        ])->pluck('id');
        $analyst->permissions()->sync($analystPermissions);

        // Viewer Role
        $viewer = Role::firstOrCreate(
            ['name' => 'viewer'],
            [
                'display_name' => 'Viewer',
                'description' => 'Read-only access to analytics',
                'is_system' => true,
            ]
        );
        $viewerPermissions = Permission::whereIn('name', [
            'sales_analytics.view',
            'customer_analytics.view',
            'forecasting.view',
            'reports.view',
        ])->pluck('id');
        $viewer->permissions()->sync($viewerPermissions);

        $this->command->info('✅ Created default roles and permissions!');
    }
}
