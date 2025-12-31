<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return view('admin.error-logs', [
                'logs' => [],
                'filter' => 'all'
            ]);
        }

        $logs = $this->parseLogFile($logFile);

        // Filter by level if requested
        $filter = $request->get('level', 'all');
        if ($filter !== 'all') {
            $logs = array_filter($logs, function($log) use ($filter) {
                return strtolower($log['level']) === strtolower($filter);
            });
        }

        // Search functionality
        if ($search = $request->get('search')) {
            $logs = array_filter($logs, function($log) use ($search) {
                return stripos($log['message'], $search) !== false ||
                       stripos($log['context'], $search) !== false;
            });
        }

        // Limit to last 100 entries
        $logs = array_slice($logs, -100);
        $logs = array_reverse($logs);

        return view('admin.error-logs', [
            'logs' => $logs,
            'filter' => $filter,
            'search' => $search ?? ''
        ]);
    }

    private function parseLogFile($file)
    {
        $content = File::get($file);
        $logs = [];

        // Split by log entries (matches Laravel's log format)
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2}|$)/s';

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $timestamp = $match[1];
            $level = strtoupper($match[2]);
            $fullMessage = trim($match[3]);

            // Split message and stack trace
            $parts = explode("\n", $fullMessage, 2);
            $message = $parts[0];
            $context = isset($parts[1]) ? $parts[1] : '';

            // Clean up message (remove redundant info)
            $message = preg_replace('/\{.*?\}/', '', $message);
            $message = trim($message);

            $logs[] = [
                'timestamp' => $timestamp,
                'level' => $level,
                'message' => $message,
                'context' => $context
            ];
        }

        return $logs;
    }

    public function download()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return back()->with('error', 'Log file not found.');
        }

        return response()->download($logFile, 'laravel-' . date('Y-m-d') . '.log');
    }

    public function clear()
    {
        $logFile = storage_path('logs/laravel.log');

        if (File::exists($logFile)) {
            File::put($logFile, '');
        }

        return back()->with('success', 'Logs cleared successfully!');
    }
}
