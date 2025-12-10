<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Log the dashboard visit
        ActivityLog::log('viewed_dashboard');

        // For now, return simple dashboard
        // We'll build the full dashboard in Phase 5
        return view('dashboard', [
            'user' => $user,
        ]);
    }
}
