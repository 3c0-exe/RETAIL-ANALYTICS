<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    /**
     * Get recent notifications for the bell dropdown
     */
    public function recent(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->alerts()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'title' => $alert->title,
                    'message' => $alert->message,
                    'severity' => $alert->severity,
                    'is_read' => $alert->is_read,
                    'time_ago' => $alert->created_at->diffForHumans(),
                    'created_at' => $alert->created_at->toIso8601String(),
                ];
            });

        $unreadCount = $user->alerts()->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $alert = $user->alerts()->findOrFail($id);

        $alert->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->alerts()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
