<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait DateFilterTrait
{
    /**
     * Get the date range for filtering transactions
     * Defaults to actual data range if no filters provided
     */
    protected function getDateRange($request)
    {
        // If user provided specific dates, use those
        if ($request->has('start_date') && $request->has('end_date')) {
            return [
                'start' => Carbon::parse($request->start_date)->startOfDay(),
                'end' => Carbon::parse($request->end_date)->endOfDay(),
            ];
        }

        // Get actual min/max dates from transactions table
        $dateRange = DB::table('transactions')
            ->selectRaw('MIN(timestamp) as earliest, MAX(timestamp) as latest')
            ->first();

        // If we have data, use actual range
        if ($dateRange && $dateRange->earliest && $dateRange->latest) {
            return [
                'start' => Carbon::parse($dateRange->earliest)->startOfDay(),
                'end' => Carbon::parse($dateRange->latest)->endOfDay(),
            ];
        }

        // Fallback: last 30 days if no data exists yet
        return [
            'start' => now()->subDays(30)->startOfDay(),
            'end' => now()->endOfDay(),
        ];
    }

    /**
     * Get date range display text for views
     */
    protected function getDateRangeDisplay($start, $end)
    {
        return [
            'start_display' => $start->format('M d, Y'),
            'end_display' => $end->format('M d, Y'),
            'start_value' => $start->format('Y-m-d'),
            'end_value' => $end->format('Y-m-d'),
        ];
    }

    /**
     * Apply date filter to query builder
     */
    protected function applyDateFilter($query, $start, $end, $dateColumn = 'timestamp')
    {
        return $query->whereBetween($dateColumn, [$start, $end]);
    }

    /**
     * Get available date presets for dropdowns
     */
    protected function getDatePresets()
    {
        // Get actual data range
        $dataRange = DB::table('transactions')
            ->selectRaw('MIN(timestamp) as earliest, MAX(timestamp) as latest')
            ->first();

        $presets = [
            'today' => [
                'label' => 'Today',
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay(),
            ],
            'yesterday' => [
                'label' => 'Yesterday',
                'start' => now()->subDay()->startOfDay(),
                'end' => now()->subDay()->endOfDay(),
            ],
            'last_7_days' => [
                'label' => 'Last 7 Days',
                'start' => now()->subDays(7)->startOfDay(),
                'end' => now()->endOfDay(),
            ],
            'last_30_days' => [
                'label' => 'Last 30 Days',
                'start' => now()->subDays(30)->startOfDay(),
                'end' => now()->endOfDay(),
            ],
            'this_month' => [
                'label' => 'This Month',
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
            ],
            'last_month' => [
                'label' => 'Last Month',
                'start' => now()->subMonth()->startOfMonth(),
                'end' => now()->subMonth()->endOfMonth(),
            ],
        ];

        // Add "All Time" preset if we have data
        if ($dataRange && $dataRange->earliest && $dataRange->latest) {
            $presets['all_time'] = [
                'label' => 'All Time',
                'start' => Carbon::parse($dataRange->earliest)->startOfDay(),
                'end' => Carbon::parse($dataRange->latest)->endOfDay(),
            ];
        }

        return $presets;
    }

    /**
     * Get branch filter
     */
    protected function getBranchFilter($request)
    {
        if ($request->has('branch_id') && $request->branch_id !== 'all') {
            return $request->branch_id;
        }
        return null;
    }

    /**
     * Apply branch filter to query
     */
    protected function applyBranchFilter($query, $branchId)
    {
        if ($branchId) {
            return $query->where('branch_id', $branchId);
        }
        return $query;
    }
}
