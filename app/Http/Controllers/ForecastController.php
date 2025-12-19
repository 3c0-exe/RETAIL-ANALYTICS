<?php

namespace App\Http\Controllers;

use App\Models\Forecast;
use App\Models\Transaction;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get branches based on role
        if ($user->role === 'admin') {
            $branches = Branch::where('status', 'active')->get();
            $selectedBranchId = $request->get('branch_id', $branches->first()->id ?? null);
        } else {
            $branches = Branch::where('id', $user->branch_id)->get();
            $selectedBranchId = $user->branch_id;
        }

        $forecastPeriod = $request->get('forecast_period', '7');

        // Date range for forecast display
        $startDate = now();
        $endDate = now()->addDays((int)$forecastPeriod);

        Log::info('Forecast Page Request', [
            'branch_id' => $selectedBranchId,
            'period' => $forecastPeriod,
            'date_range' => [$startDate->toDateString(), $endDate->toDateString()]
        ]);

        // Fetch forecasts for the selected period
        $forecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('forecast_date')
            ->get();

        Log::info('Forecasts Retrieved', [
            'count' => $forecasts->count(),
            'first_date' => $forecasts->first()?->forecast_date,
            'last_date' => $forecasts->last()?->forecast_date
        ]);

        // Prepare forecast data for Chart.js
        $forecastDates = $forecasts->pluck('forecast_date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();

        $forecastValues = $forecasts->pluck('predicted_sales')->toArray();
        $confidenceLower = $forecasts->pluck('confidence_lower')->toArray();
        $confidenceUpper = $forecasts->pluck('confidence_upper')->toArray();

        // Fetch actual sales for comparison (last N days)
        $comparisonStartDate = now()->subDays((int)$forecastPeriod);
        $comparisonEndDate = now();

        $actualSales = Transaction::where('branch_id', $selectedBranchId)
            ->whereBetween('timestamp', [$comparisonStartDate, $comparisonEndDate])
            ->select(
                DB::raw('DATE(timestamp) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        Log::info('Actual Sales Retrieved', [
            'count' => $actualSales->count(),
            'date_range' => [$comparisonStartDate->toDateString(), $comparisonEndDate->toDateString()]
        ]);

        $comparisonDates = $actualSales->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();

        $comparisonActuals = $actualSales->pluck('total')->toArray();

        // Get past forecasts for accuracy calculation
        $pastForecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [$comparisonStartDate->toDateString(), $comparisonEndDate->toDateString()])
            ->orderBy('forecast_date')
            ->get();

        $comparisonForecasts = $pastForecasts->pluck('predicted_sales')->toArray();

        Log::info('Past Forecasts for Accuracy', [
            'count' => $pastForecasts->count()
        ]);

        // Calculate accuracy if we have both forecasts and actuals
        $accuracy = null;
        if (count($comparisonForecasts) > 0 && count($comparisonActuals) > 0) {
            $minLength = min(count($comparisonForecasts), count($comparisonActuals));
            $totalError = 0;
            $totalActual = 0;

            for ($i = 0; $i < $minLength; $i++) {
                $totalError += abs($comparisonForecasts[$i] - $comparisonActuals[$i]);
                $totalActual += $comparisonActuals[$i];
            }

            if ($totalActual > 0) {
                $mape = ($totalError / $totalActual) * 100;
                $accuracy = max(0, 100 - $mape);
            }
        }

        Log::info('Calculated Accuracy', ['accuracy' => $accuracy]);

        // Get top products forecast
        $topProductsForecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNotNull('product_id')
            ->whereBetween('forecast_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with('product')
            ->select('product_id', DB::raw('SUM(predicted_sales) as total_forecast'))
            ->groupBy('product_id')
            ->orderByDesc('total_forecast')
            ->limit(10)
            ->get();

        Log::info('Top Products Retrieved', ['count' => $topProductsForecasts->count()]);

        // Debug data
        $debugData = [
            'forecasts_count' => $forecasts->count(),
            'comparison_dates' => count($comparisonDates),
            'comparison_actuals' => count($comparisonActuals),
            'comparison_forecasts' => count($comparisonForecasts),
            'top_products' => $topProductsForecasts->count(),
            'selected_branch' => $selectedBranchId,
            'forecast_period' => $forecastPeriod,
            'date_range' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString()
            ]
        ];

        Log::info('Debug Data', $debugData);

        return view('forecasts.index', compact(
            'branches',
            'selectedBranchId',
            'forecastPeriod',
            'forecastDates',
            'forecastValues',
            'confidenceLower',
            'confidenceUpper',
            'comparisonDates',
            'comparisonActuals',
            'comparisonForecasts',
            'accuracy',
            'topProductsForecasts',
            'debugData'
        ));
    }
}
