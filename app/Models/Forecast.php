<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'product_id',
        'category',
        'forecast_date',
        'predicted_sales',
        'confidence_lower',
        'confidence_upper',
        'model_version',
        'metadata',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'predicted_sales' => 'decimal:2',
        'confidence_lower' => 'decimal:2',
        'confidence_upper' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get forecast accuracy by comparing with actual sales
     */
    public function getAccuracy(): ?float
{
    $transaction = Transaction::where('branch_id', $this->branch_id)
        ->whereDate('timestamp', $this->forecast_date)
        ->when($this->product_id, function ($q) {
            $q->whereHas('items', function ($query) {
                $query->where('product_id', $this->product_id);
            });
        })
        ->sum('total'); // Changed from total_amount

    if (!$transaction) {
        return null;
    }

    $error = abs($this->predicted_sales - $transaction);
    return max(0, 100 - ($error / max($transaction, 1) * 100));
}
}
