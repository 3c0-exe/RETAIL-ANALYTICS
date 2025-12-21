<?php

namespace App\View\Components;

use App\Models\Alert;
use Illuminate\View\Component;
use Illuminate\View\View;

class NotificationBell extends Component
{
    public $unreadCount;
    public $recentAlerts;

    public function __construct()
    {
        $this->unreadCount = Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $this->recentAlerts = Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'message', 'type', 'severity', 'created_at']);
    }

    public function render(): View
    {
        return view('components.notification-bell');
    }
}
