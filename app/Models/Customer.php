<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
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
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'visit_count' => 'integer',
        'last_visit_date' => 'date',
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function updateStats()
    {
        $this->total_spent = $this->transactions()->sum('total');
        $this->visit_count = $this->transactions()->count();
        $this->last_visit_date = $this->transactions()->latest('transaction_date')->first()?->transaction_date;
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
