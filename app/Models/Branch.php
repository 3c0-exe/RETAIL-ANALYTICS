<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    use LogsActivity;

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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ADD THIS - Branch to Products (many-to-many via branch_products pivot)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_products')
            ->withPivot('quantity', 'low_stock_threshold', 'branch_price', 'is_available')
            ->withTimestamps();
    }

    public function branchProducts()
    {
        return $this->hasMany(BranchProduct::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
