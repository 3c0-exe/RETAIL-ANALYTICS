<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // ✅ FIXED: Search filter (matches blade file)
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model_type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // ✅ FIXED: IP address filter (matches blade file)
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', "%{$request->ip_address}%");
        }

        // ✅ KEPT: Date filters (useful for future)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ✅ NEW: Per page with default of 15
        $perPage = $request->input('per_page', 15);
        $logs = $query->paginate($perPage)->appends($request->except('page'));

        // Get unique model_types and actions for filters
        $modelTypes = ActivityLog::distinct()->whereNotNull('model_type')->pluck('model_type');
        $actions = ActivityLog::distinct()->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'modelTypes', 'actions'));
    }
}
