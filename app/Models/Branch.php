<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    use LogsActivity; // ← Add this trait

    protected $fillable = [
        'name',
        'code',
        'timezone',
        'tax_rate',
        'currency',
        'status',
        'manager_id',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'settings' => 'array',
        ];
    }

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
