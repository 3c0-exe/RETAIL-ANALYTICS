<?php

// app/Models/NotificationPreference.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_type',
        'email_enabled',
        'in_app_enabled',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Available notification types
     */
    public static function types(): array
    {
        return [
            'low_stock' => 'Low Stock Alert',
            'out_of_stock' => 'Out of Stock Alert',
            'overstock' => 'Overstock Warning',
            'sales_drop' => 'Sales Drop Alert',
            'high_value_transaction' => 'High Value Transaction',
            'daily_summary' => 'Daily Sales Summary',
            'import_completion' => 'Import Completion',
            'forecast_deviation' => 'Forecast Deviation',
            'customer_segment_change' => 'Customer Segment Change',
            'failed_login' => 'Failed Login Attempts',
            'new_user' => 'New User Created',
        ];
    }

    /**
     * Get default preferences for a user
     */
    public static function defaults(): array
    {
        return [
            'low_stock' => ['email' => true, 'in_app' => true],
            'out_of_stock' => ['email' => true, 'in_app' => true],
            'overstock' => ['email' => false, 'in_app' => true],
            'sales_drop' => ['email' => true, 'in_app' => true],
            'high_value_transaction' => ['email' => false, 'in_app' => true],
            'daily_summary' => ['email' => true, 'in_app' => false],
            'import_completion' => ['email' => true, 'in_app' => true],
            'forecast_deviation' => ['email' => false, 'in_app' => true],
            'customer_segment_change' => ['email' => false, 'in_app' => true],
            'failed_login' => ['email' => true, 'in_app' => true],
            'new_user' => ['email' => false, 'in_app' => true],
        ];
    }
}
