<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index(Request $request)
    {
        $query = Alert::where('user_id', auth()->id());

        // Apply filters
        if ($request->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->filter === 'read') {
            $query->where('is_read', true);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);

        // Available notification types
        $types = [
            'low_stock' => 'Low Stock',
            'forecast_deviation' => 'Forecast Deviation',
            'high_value_transaction' => 'High Value Transaction',
            'import_completion' => 'Import Completion',
            'system' => 'System',
        ];

        return view('notifications.index', compact('alerts', 'types'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $alert = Alert::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $alert->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Clear all read notifications
     */
    public function clearRead()
    {
        Alert::where('user_id', auth()->id())
            ->where('is_read', true)
            ->delete();

        return back()->with('success', 'Read notifications cleared');
    }

    /**
     * Show notification preferences
     */
    public function preferences()
    {
        return view('notifications.preferences');
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_low_stock' => 'boolean',
            'email_forecast_deviation' => 'boolean',
            'email_high_value_transaction' => 'boolean',
        ]);

        auth()->user()->update([
            'notification_preferences' => $request->only([
                'email_low_stock',
                'email_forecast_deviation',
                'email_high_value_transaction',
            ]),
        ]);

        return back()->with('success', 'Notification preferences updated');
    }

    /**
     * API: Get recent notifications for bell
     */
    public function apiRecent()
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'title' => $alert->title,
                    'message' => $alert->message,
                    'severity' => $alert->severity,
                    'is_read' => $alert->is_read,
                    'time_ago' => $alert->created_at->diffForHumans(),
                ];
            });

        $unreadCount = Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $alerts,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * API: Mark notification as read
     */
    public function apiMarkAsRead($id)
    {
        $alert = Alert::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $alert->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * API: Mark all as read
     */
    public function apiMarkAllAsRead()
    {
        Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
