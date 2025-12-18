<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('alerts.index', compact('alerts'));
    }

    public function unread()
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json($alerts);
    }

    public function markAsRead($id)
    {
        $alert = Alert::where('user_id', auth()->id())
            ->findOrFail($id);

        $alert->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All alerts marked as read');
    }
}
