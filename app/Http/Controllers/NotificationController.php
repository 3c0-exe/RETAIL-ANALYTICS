<?php

// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationPreference;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $alerts = $request->user()
            ->alerts()
            ->with('related')
            ->when($request->filter, function ($query) use ($request) {
                if ($request->filter === 'unread') {
                    $query->unread();
                } elseif ($request->filter === 'read') {
                    $query->where('is_read', true);
                }
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->paginate(20);

        $types = NotificationPreference::types();

        return view('notifications.index', compact('alerts', 'types'));
    }

    /**
     * Show notification preferences
     */
    public function preferences(Request $request)
    {
        $user = $request->user();
        $allowedTypes = $user->getAllowedNotificationTypes();
        $allTypes = NotificationPreference::types();

        // Filter types by user's role
        $types = array_intersect_key($allTypes, array_flip($allowedTypes));

        // Get existing preferences
        $preferences = $user->notificationPreferences()->pluck('email_enabled', 'notification_type');
        $defaults = NotificationPreference::defaults();

        return view('notifications.preferences', compact('types', 'preferences', 'defaults'));
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        $allowedTypes = $user->getAllowedNotificationTypes();

        foreach ($allowedTypes as $type) {
            $emailEnabled = $request->has("email_{$type}");

            $user->notificationPreferences()->updateOrCreate(
                ['notification_type' => $type],
                [
                    'email_enabled' => $emailEnabled,
                    'in_app_enabled' => true, // Always keep in-app enabled
                ]
            );
        }

        return redirect()
            ->route('notifications.preferences')
            ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Delete all read notifications
     */
    public function clearRead(Request $request)
    {
        $request->user()->alerts()->where('is_read', true)->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Read notifications cleared!');
    }
}
