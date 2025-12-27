<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // Only use created_at

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Log an activity
     *
     * @param string $action (e.g., 'viewed_dashboard', 'created', 'updated', 'deleted')
     * @param string|null $modelType
     * @param int|null $modelId
     * @param array $changes
     * @return static
     */
    public static function log(string $action, ?string $modelType = null, ?int $modelId = null, array $changes = []): self
    {
        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $changes,
        ]);
    }

    /**
     * Relationship: Activity belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that this activity log is for (polymorphic)
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filter by action type
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by IP address
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowserAttribute()
    {
        $agent = $this->user_agent ?? '';

        if (str_contains($agent, 'Chrome')) return 'Chrome';
        if (str_contains($agent, 'Firefox')) return 'Firefox';
        if (str_contains($agent, 'Safari')) return 'Safari';
        if (str_contains($agent, 'Edge')) return 'Edge';
        if (str_contains($agent, 'Opera')) return 'Opera';

        return 'Unknown';
    }

    /**
     * Get device type from user agent
     */
    public function getDeviceAttribute()
    {
        $agent = $this->user_agent ?? '';

        if (str_contains($agent, 'Mobile')) return 'Mobile';
        if (str_contains($agent, 'Tablet')) return 'Tablet';

        return 'Desktop';
    }

    /**
     * Check if activity is suspicious
     */
    public function isSuspicious()
    {
        // Check for unusual activities
        $suspiciousActions = ['deleted', 'force_deleted', 'restored'];

        if (in_array($this->action, $suspiciousActions)) {
            return true;
        }

        // Check for multiple logins from different IPs in short time
        if ($this->action === 'login') {
            $recentLogins = static::where('user_id', $this->user_id)
                ->where('action', 'login')
                ->where('created_at', '>=', now()->subHours(1))
                ->where('ip_address', '!=', $this->ip_address)
                ->count();

            if ($recentLogins > 2) {
                return true;
            }
        }

        return false;
    }
}
