<?php

// app/Http/Controllers/Api/NotificationApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get recent notifications for current user
     */
    public function recent(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getRecent($user, 10);

        return response()->json([
            'notifications' => $notifications->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'title' => $alert->title,
                    'message' => $alert->message,
                    'type' => $alert->type,
                    'severity' => $alert->severity,
                    'is_read' => $alert->is_read,
                    'time_ago' => $alert->created_at->diffForHumans(),
                    'created_at' => $alert->created_at->toIso8601String(),
                ];
            }),
            'unread_count' => $user->unreadAlertsCount(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $alert = $request->user()->alerts()->findOrFail($id);
        $this->notificationService->markAsRead($alert);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $this->notificationService->markAllAsRead($request->user());

        return response()->json(['success' => true]);
    }
}
