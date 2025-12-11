<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\LogsActivity; // ← Add this
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use LogsActivity; // ← Add this trait

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
        'avatar',
        'theme',
        'two_factor_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function managedBranch()
    {
        return $this->hasOne(Branch::class, 'manager_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBranchManager(): bool
    {
        return $this->role === 'branch_manager';
    }

    public function isAnalyst(): bool
    {
        return $this->role === 'analyst';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    public function canManageBranches(): bool
    {
        return $this->isAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canViewAllBranches(): bool
    {
        return $this->isAdmin() || $this->isAnalyst();
    }
}
