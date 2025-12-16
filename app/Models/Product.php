<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'price',
        'cost',
        'barcode',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_products')
            ->withPivot('quantity', 'low_stock_threshold', 'branch_price', 'is_available')
            ->withTimestamps();
    }

    public function branchProducts()
    {
        return $this->hasMany(BranchProduct::class);
    }

    // Helper methods
    public function getTotalStockAttribute()
    {
        return $this->branchProducts->sum('quantity');
    }

    public function getStockForBranch($branchId)
    {
        return $this->branchProducts()
            ->where('branch_id', $branchId)
            ->first();
    }

    public function isLowStockInBranch($branchId)
    {
        $branchProduct = $this->getStockForBranch($branchId);
        if (!$branchProduct) return false;

        return $branchProduct->quantity <= $branchProduct->low_stock_threshold;
    }

    // Auto-generate SKU
    public static function generateSKU()
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        } while (self::where('sku', $sku)->exists());

        return $sku;
    }

    // Auto-generate barcode (EAN-13 format simulation)
    public static function generateBarcode()
    {
        return '200' . str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    }

    // Boot method to auto-generate SKU and barcode
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = self::generateSKU();
            }
            if (empty($product->barcode)) {
                $product->barcode = self::generateBarcode();
            }
        });
    }
}
