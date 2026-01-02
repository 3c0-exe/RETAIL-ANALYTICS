<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Relationships
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Get users with this role (based on string 'role' column in users table)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role', 'name');
        // This tells Laravel:
        // - Foreign key in users table is 'role' (the string column)
        // - Local key in roles table is 'name' (e.g., 'admin', 'analyst')
    }

    // Helper methods
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function grantPermission(Permission $permission): void
    {
        if (!$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission->id);
        }
    }

    public function revokePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }

    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }
}
