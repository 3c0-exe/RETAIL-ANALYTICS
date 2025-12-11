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
}
