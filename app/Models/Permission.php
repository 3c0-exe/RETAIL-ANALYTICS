<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'module',
        'action',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    // Helper methods
    public static function getModules(): array
    {
        return [
            'branches' => 'Branch Management',
            'users' => 'User Management',
            'categories' => 'Categories',
            'products' => 'Products',
            'imports' => 'Data Imports',
            'sales_analytics' => 'Sales Analytics',
            'customer_analytics' => 'Customer Analytics',
            'forecasting' => 'Forecasting',
            'reports' => 'Custom Reports',
            'settings' => 'System Settings',
            'activity_logs' => 'Activity Logs',
            'error_logs' => 'Error Logs',
            'announcements' => 'Announcements',
            'backups' => 'Backups',
        ];
    }

    public static function getActions(): array
    {
        return [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ];
    }
}
