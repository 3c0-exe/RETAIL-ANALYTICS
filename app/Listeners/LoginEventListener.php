<?php

// app/Listeners/LoginEventListener.php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Cache;
use App\Services\NotificationService;
use App\Models\User;

class LoginEventListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle failed login attempts
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;

        if (!$email) {
            return;
        }

        $cacheKey = 'failed_login_' . md5($email);

        // Increment failed login count
        $attempts = Cache::get($cacheKey, 0) + 1;
        Cache::put($cacheKey, $attempts, now()->addMinutes(15)); // Reset after 15 minutes

        // Alert on threshold breach
        if ($attempts >= NotificationService::THRESHOLDS['failed_login_attempts']) {
            // Find the user (if exists)
            $user = User::where('email', $email)->first();

            // Notify all admins
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                // Check if alert already sent in last hour
                $existsRecently = \App\Models\Alert::where('type', 'failed_login')
                    ->where('user_id', $admin->id)
                    ->where('created_at', '>=', now()->subHour())
                    ->exists();

                if (!$existsRecently) {
                    $this->notificationService->notify(
                        type: 'failed_login',
                        title: 'Security Alert: Multiple Failed Login Attempts',
                        message: "Detected {$attempts} failed login attempts for email: {$email}",
                        severity: 'critical',
                        related: $user,
                        targetUser: $admin,
                        metadata: [
                            'email' => $email,
                            'attempts' => $attempts,
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                            'timestamp' => now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            // Reset counter after notification
            Cache::forget($cacheKey);
        }
    }
}
