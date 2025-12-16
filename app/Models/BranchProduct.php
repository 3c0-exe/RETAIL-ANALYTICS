<?php

namespace App\Models;

use App\Models\Traits\HasBranchScope;
use Illuminate\Database\Eloquent\Model;

class BranchProduct extends Model
{
    use HasBranchScope; // Apply branch isolation

    protected $fillable = [
        'branch_id',
        'product_id',
        'quantity',
        'low_stock_threshold',
        'branch_price',
        'is_available',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'branch_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function isLowStock()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function getEffectivePrice()
    {
        return $this->branch_price ?? $this->product->price;
    }
}
