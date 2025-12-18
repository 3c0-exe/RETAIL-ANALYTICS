<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Forecast;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\ForecastService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    public function index(Request $request, ForecastService $forecastService)
    {
        $user = auth()->user();

        // Branch filter
        $branches = $user->role === 'admin'
            ? Branch::where('status', 'active')->get()
            : Branch::where('id', $user->branch_id)->get();

        $selectedBranchId = $request->get('branch_id', $branches->first()?->id);
        $selectedBranch = Branch::find($selectedBranchId);

        // Period filter
        $period = $request->get('period', 7); // 7, 30, or 90 days

        // Get forecasts
        $forecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [
                Carbon::today(),
                Carbon::today()->addDays($period)
            ])
            ->orderBy('forecast_date')
            ->get();

        // Get accuracy metrics
        $accuracy = $forecastService->getAccuracyMetrics($selectedBranch, 7);

        // Get top products forecast
        $topProductsForecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNotNull('product_id')
            ->whereBetween('forecast_date', [
                Carbon::today(),
                Carbon::today()->addDays($period)
            ])
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($forecasts) {
                return [
                    'product' => $forecasts->first()->product,
                    'total_forecast' => $forecasts->sum('predicted_sales'),
                    'forecasts' => $forecasts,
                ];
            })
            ->sortByDesc('total_forecast')
            ->take(10);

        // Get actual vs forecast for past 7 days (for comparison chart)
        $comparison = $this->getComparisonData($selectedBranchId, 7);

        return view('forecasts.index', compact(
            'branches',
            'selectedBranch',
            'forecasts',
            'period',
            'accuracy',
            'topProductsForecasts',
            'comparison'
        ));
    }

    private function getComparisonData($branchId, $days)
    {
        $startDate = Carbon::now()->subDays($days);

    $actuals = Transaction::where('branch_id', $branchId)
        ->where('timestamp', '>=', $startDate)
        ->where('timestamp', '<', Carbon::today())
        ->where('status', 'completed')
        ->selectRaw('DATE(timestamp) as date, SUM(total) as total') // Changed from total_amount
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('total', 'date');

        $forecasts = Forecast::where('branch_id', $branchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [
                $startDate,
                Carbon::yesterday()
            ])
            ->orderBy('forecast_date')
            ->get()
            ->pluck('predicted_sales', 'forecast_date');

        $dates = [];
        $actualValues = [];
        $forecastValues = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - $i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('M d');
            $actualValues[] = $actuals[$date] ?? 0;
            $forecastValues[] = $forecasts[$date] ?? null;
        }

        return [
            'dates' => $dates,
            'actuals' => $actualValues,
            'forecasts' => $forecastValues,
        ];
    }

    public function regenerate(Request $request, ForecastService $forecastService)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return back()->with('error', 'Only admins can regenerate forecasts.');
        }

        $branchId = $request->get('branch_id');
        $branch = Branch::findOrFail($branchId);

        try {
            $forecastService->generateForecasts($branch, 30);
            return back()->with('success', 'Forecasts regenerated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
