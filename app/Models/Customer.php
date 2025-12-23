<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'email',
        'phone',
        'address',
        'loyalty_id',
        'total_spent',
        'visit_count',
        'last_visit_date',
        'segment',
        'rfm_score',
        'age',
        'gender'
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'last_visit_date' => 'date',
        'rfm_score' => 'array'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeSegment($query, $segment)
    {
        return $query->where('segment', $segment);
    }

    // Helper methods
    public function updateStats()
    {
        $this->total_spent = $this->transactions()->sum('total_amount');
        $this->visit_count = $this->transactions()->count();
        $this->last_visit_date = $this->transactions()->latest('timestamp')->first()?->timestamp;
        $this->save();
    }

    public function isVIP()
    {
        return $this->segment === 'vip';
    }

    public function isAtRisk()
    {
        return $this->segment === 'at_risk';
    }

    // RFM Getters
    public function getRecencyScore()
    {
        return $this->rfm_score['recency'] ?? 0;
    }

    public function getFrequencyScore()
    {
        return $this->rfm_score['frequency'] ?? 0;
    }

    public function getMonetaryScore()
    {
        return $this->rfm_score['monetary'] ?? 0;
    }

    public function getTotalRfmScore()
    {
        return $this->getRecencyScore() + $this->getFrequencyScore() + $this->getMonetaryScore();
    }

    // Calculate days since last purchase
    public function getDaysSinceLastPurchase()
    {
        if (!$this->last_visit_date) return null;
        return Carbon::parse($this->last_visit_date)->diffInDays(now());
    }

    // Auto-generate customer code
    public static function generateCustomerCode()
    {
        do {
            $code = 'CUST-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('customer_code', $code)->exists());

        return $code;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = self::generateCustomerCode();
            }
        });
    }
}
