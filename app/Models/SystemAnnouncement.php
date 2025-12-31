<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class SystemAnnouncement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'is_active',
        'is_dismissible',
        'expires_at',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get active announcements for current user
     */
    public static function getActiveForUser($userId = null)
    {
        $userId = $userId ?? Auth::id();

        return self::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->whereNotExists(function ($query) use ($userId) {
                $query->select('id')
                    ->from('announcement_dismissals')
                    ->whereColumn('announcement_id', 'system_announcements.id')
                    ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Dismiss announcement for a user
     */
    public function dismissFor($userId)
    {
        if (!$this->is_dismissible) {
            return false;
        }

        return AnnouncementDismissal::firstOrCreate([
            'user_id' => $userId,
            'announcement_id' => $this->id,
        ], [
            'dismissed_at' => now()
        ]);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

// AnnouncementDismissal Model (same file or separate)
class AnnouncementDismissal extends Model
{
    protected $fillable = ['user_id', 'announcement_id', 'dismissed_at'];

    protected $casts = [
        'dismissed_at' => 'datetime',
    ];

    public $timestamps = false;
}
