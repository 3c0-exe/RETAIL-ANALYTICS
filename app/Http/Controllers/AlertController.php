<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display all alerts (index page)
     */
    public function index()
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->with('related')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('alerts.index', compact('alerts'));
    }

    /**
     * Get unread alerts (API for notification bell)
     */
public function unread()
{
    $alerts = Alert::where('user_id', auth()->id())
        ->where('is_read', false)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get(['id', 'title', 'message', 'type', 'severity', 'created_at']) // Only select needed columns
        ->map(function ($alert) {
            return [
                'id' => $alert->id,
                'title' => $alert->title,
                'message' => $alert->message,
                'icon' => $alert->icon,
                'severity' => $alert->severity,
                'created_at' => $alert->created_at->toIso8601String(),
            ];
        });

    return response()->json($alerts);
}
    /**
     * Mark single alert as read
     */
    public function markAsRead(Alert $alert)
{
    // Ensure user can only mark their own alerts
    if ($alert->user_id !== auth()->id()) {
        abort(403);
    }

    $alert->markAsRead();

    // Return back to previous page
    return redirect()->back()->with('success', 'Notification marked as read');
}
    /**
     * Mark all alerts as read
     */
    public function markAllAsRead()
    {
        Alert::where('user_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Show single alert and mark as read
     */
    public function show(Alert $alert)
    {
        // Ensure user can only view their own alerts
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }

        // Mark as read if not already
        if (!$alert->is_read) {
            $alert->markAsRead();
        }

        // Redirect to related resource if exists
        if ($alert->related_type && $alert->related_id) {
            // For BranchProduct alerts, go to products page
            if ($alert->related_type === 'App\Models\BranchProduct') {
                return redirect()->route('admin.products.index')
                    ->with('success', 'Viewing product related to alert');
            }
        }

        // Otherwise go back to alerts index
        return redirect()->route('alerts.index');
    }
}
