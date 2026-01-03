<?php

// app/Observers/UserObserver.php

namespace App\Observers;

use App\Models\User;
use App\Services\NotificationService;

class UserObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Don't notify for the first user (setup)
        if (User::count() === 1) {
            return;
        }

        // Notify all admins about new user
        $admins = User::where('role', 'admin')
            ->where('id', '!=', $user->id) // Don't notify if admin created themselves
            ->get();

        foreach ($admins as $admin) {
            $this->notificationService->notify(
                type: 'new_user',
                title: 'New User Created',
                message: "A new user '{$user->name}' has been added to the system with role: " . ucfirst(str_replace('_', ' ', $user->role)),
                severity: 'info',
                related: $user,
                targetUser: $admin,
                metadata: [
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'branch' => $user->branch?->name ?? 'N/A',
                    'created_by' => auth()->user()?->name ?? 'System',
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
