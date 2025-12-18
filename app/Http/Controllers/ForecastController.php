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
use Illuminate\Support\Facades\Log;

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

        if (!$selectedBranch) {
            return back()->with('error', 'Branch not found');
        }

        // Period filter
        $period = (int) $request->get('period', 7); // 7, 30, or 90 days

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

        // DEBUG: Log forecast count
        Log::info("Forecasts found: " . $forecasts->count() . " for branch " . $selectedBranchId);

        // If no forecasts exist, generate them
        if ($forecasts->isEmpty()) {
            try {
                Log::info("No forecasts found, generating...");
                $forecastService->generateForecasts($selectedBranch, 30);

                // Refetch forecasts
                $forecasts = Forecast::where('branch_id', $selectedBranchId)
                    ->whereNull('product_id')
                    ->whereNull('category')
                    ->whereBetween('forecast_date', [
                        Carbon::today(),
                        Carbon::today()->addDays($period)
                    ])
                    ->orderBy('forecast_date')
                    ->get();

                Log::info("Generated forecasts: " . $forecasts->count());
            } catch (\Exception $e) {
                Log::error("Forecast generation error: " . $e->getMessage());
                return back()->with('error', 'Unable to generate forecasts: ' . $e->getMessage());
            }
        }

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

        // DEBUG: Check comparison data
        Log::info("Comparison dates: " . json_encode($comparison['dates']));
        Log::info("Comparison actuals: " . json_encode($comparison['actuals']));
        Log::info("Comparison forecasts: " . json_encode($comparison['forecasts']));

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

        // Get actual sales
        $actuals = Transaction::where('branch_id', $branchId)
            ->where('timestamp', '>=', $startDate)
            ->where('timestamp', '<', Carbon::today())
            ->where('status', 'completed')
            ->selectRaw('DATE(timestamp) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // Get forecasts (for past dates)
        $forecasts = Forecast::where('branch_id', $branchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [
                $startDate->format('Y-m-d'),
                Carbon::yesterday()->format('Y-m-d')
            ])
            ->orderBy('forecast_date')
            ->get()
            ->pluck('predicted_sales', 'forecast_date');

        $dates = [];
        $actualValues = [];
        $forecastValues = [];

        // Build arrays for chart
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - $i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('M d');
            $actualValues[] = (float) ($actuals[$date] ?? 0);
            $forecastValues[] = isset($forecasts[$date]) ? (float) $forecasts[$date] : null;
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
            Log::error("Regenerate error: " . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
