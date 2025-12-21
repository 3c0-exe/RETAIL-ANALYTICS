<?php

namespace App\View\Components;

use App\Models\Alert;
use Illuminate\View\Component;
use Illuminate\View\View;

class NotificationBell extends Component
{
    public function render(): View
    {
        $unreadCount = Alert::where('user_id', auth()->id())
            ->unread()
            ->count();

        $recentAlerts = Alert::where('user_id', auth()->id())
            ->with('related')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('components.notification-bell', [
            'unreadCount' => $unreadCount,
            'recentAlerts' => $recentAlerts,
        ]);
    }
}
