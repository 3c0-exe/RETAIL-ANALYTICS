<?php

// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Alert;
use App\Models\User;
use App\Mail\GenericAlert;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Notification thresholds
     */
    const THRESHOLDS = [
        'high_value_transaction' => 10000,
        'sales_drop_percentage' => 30,
        'failed_login_attempts' => 5,
        'forecast_deviation' => 20,
        'overstock_multiplier' => 3,
    ];

    /**
     * Create and send notification
     */
    public function notify(
        string $type,
        string $title,
        string $message,
        string $severity = 'info',
        $related = null,
        ?User $targetUser = null,
        array $metadata = []
    ): void {
        // Determine recipients
        $recipients = $targetUser ? collect([$targetUser]) : $this->getRecipientsForType($type);

        foreach ($recipients as $user) {
            $preferences = $user->shouldReceiveNotification($type);

            // Create in-app alert if enabled
            if ($preferences['in_app']) {
                $alert = Alert::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'severity' => $severity,
                    'is_read' => false,
                    'related_type' => $related ? get_class($related) : null,
                    'related_id' => $related?->id,
                    'metadata' => $metadata,
                ]);

                // Send email if enabled
                if ($preferences['email']) {
                    $this->sendEmail($alert, $user);
                }
            }
        }
    }

    /**
     * Send email notification
     */
    private function sendEmail(Alert $alert, User $user): void
    {
        // Use queue to prevent blocking
        Mail::to($user->email)->queue(new GenericAlert($alert));
    }

    /**
     * Get users who should receive this notification type
     */
    private function getRecipientsForType(string $type): \Illuminate\Support\Collection
    {
        return User::whereHas('notificationPreferences', function ($query) use ($type) {
            $query->where('notification_type', $type)
                  ->where(function ($q) {
                      $q->where('email_enabled', true)
                        ->orWhere('in_app_enabled', true);
                  });
        })->orWhere(function ($query) use ($type) {
            // Include users without preferences (use defaults)
            $query->whereDoesntHave('notificationPreferences', function ($q) use ($type) {
                $q->where('notification_type', $type);
            });
        })->get()->filter(function ($user) use ($type) {
            // Filter by role permissions
            return in_array($type, $user->getAllowedNotificationTypes());
        });
    }

    /**
     * Helper: Check if threshold is met
     */
    public function thresholdMet(string $key, $value): bool
    {
        return isset(self::THRESHOLDS[$key]) && $value >= self::THRESHOLDS[$key];
    }

    /**
     * Helper: Calculate percentage deviation
     */
    public function percentageDeviation($actual, $expected): float
    {
        if ($expected == 0) return 100;
        return abs(($actual - $expected) / $expected) * 100;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Alert $alert): void
    {
        $alert->markAsRead();
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(User $user): void
    {
        $user->alerts()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get recent alerts for user
     */
    public function getRecent(User $user, int $limit = 5): \Illuminate\Support\Collection
    {
        return $user->alerts()->latest()->limit($limit)->get();
    }
}
