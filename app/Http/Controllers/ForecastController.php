<?php

namespace App\Http\Controllers;

use App\Models\Forecast;
use App\Models\Transaction;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. BRANCH SELECTION
        if ($user->role === 'admin') {
            $branches = Branch::where('status', 'active')->get();
            $selectedBranchId = $request->get('branch_id', $branches->first()->id ?? null);
        } else {
            $branches = Branch::where('id', $user->branch_id)->get();
            $selectedBranchId = $user->branch_id;
        }

        // 2. PERIOD DEFINITIONS
        $forecastPeriod = (int) $request->get('forecast_period', 7);
        $historyPeriod = (int) $request->get('history_period', 30);

        $today = Carbon::now();

        $forecastStartDate = $today->copy();
        $forecastEndDate   = $today->copy()->addDays($forecastPeriod);

        $historyStartDate  = $today->copy()->subDays($historyPeriod);
        $historyEndDate    = $today->copy()->subDay();

        // 3. FETCH FUTURE FORECASTS (with confidence intervals)
        $forecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNull('product_id')
            ->whereNull('category')
            ->whereBetween('forecast_date', [$forecastStartDate->toDateString(), $forecastEndDate->toDateString()])
            ->orderBy('forecast_date')
            ->get();

        $forecastDates = $forecasts->pluck('forecast_date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();
        $forecastValues = $forecasts->pluck('predicted_sales')->toArray();
        $forecastLower = $forecasts->pluck('confidence_lower')->toArray();
        $forecastUpper = $forecasts->pluck('confidence_upper')->toArray();

        // 4. FETCH HISTORICAL ACTUAL SALES
        $historicalTransactions = Transaction::where('branch_id', $selectedBranchId)
            ->whereBetween('timestamp', [$historyStartDate, $today])
            ->select(
                DB::raw('DATE(timestamp) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $historyDates = $historicalTransactions->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();
        $historyValues = $historicalTransactions->pluck('total')->toArray();

// 5. FETCH PAST FORECASTS (For Accuracy & Comparison Chart)
$pastForecasts = Forecast::where('branch_id', $selectedBranchId)
    ->whereBetween('forecast_date', [$historyStartDate->toDateString(), $today->toDateString()])
    ->select(
        'forecast_date',
        DB::raw('SUM(predicted_sales) as total_predicted')
    )
    ->groupBy('forecast_date')
    ->get();

// AFTER:
$pastForecastMap = $pastForecasts->mapWithKeys(function ($forecast) {
    return [Carbon::parse($forecast->forecast_date)->toDateString() => $forecast->total_predicted];
})->toArray();

$actualsMap = $historicalTransactions->pluck('total', 'date')->toArray();

        // Calculate Accuracy
        $totalError = 0;
        $totalActual = 0;
        $matchCount = 0;

        foreach ($actualsMap as $dateStr => $actualVal) {
            $dateKey = Carbon::parse($dateStr)->toDateString();
            if (isset($pastForecastMap[$dateKey]) && $actualVal > 0) {
                $totalError += abs($pastForecastMap[$dateKey] - $actualVal);
                $totalActual += $actualVal;
                $matchCount++;
            }
        }

        $accuracy = ($matchCount > 0 && $totalActual > 0)
            ? max(0, 100 - (($totalError / $totalActual) * 100))
            : null;

        // Prepare Past Forecast values aligned with History Dates for the Chart
        $historyForecastValues = [];
        foreach ($historicalTransactions as $txn) {
            $dateKey = Carbon::parse($txn->date)->toDateString();
            $historyForecastValues[] = $pastForecastMap[$dateKey] ?? null;
        }

        // DEBUG - Add this
Log::info('Date Matching Debug', [
    'sample_txn_date' => $historicalTransactions->first()->date ?? 'none',
    'sample_dateKey' => isset($historicalTransactions->first()->date) ? Carbon::parse($historicalTransactions->first()->date)->toDateString() : 'none',
    'pastForecastMap_keys_sample' => array_slice(array_keys($pastForecastMap), 0, 5),
    'pastForecastMap_sample' => array_slice($pastForecastMap, 0, 3, true),
]);

        // 6. TOP PRODUCTS
        $topProductsForecasts = Forecast::where('branch_id', $selectedBranchId)
            ->whereNotNull('product_id')
            ->whereBetween('forecast_date', [$forecastStartDate->toDateString(), $forecastEndDate->toDateString()])
            ->with('product')
            ->select('product_id', DB::raw('SUM(predicted_sales) as total_forecast'))
            ->groupBy('product_id')
            ->orderByDesc('total_forecast')
            ->limit(10)
            ->get();

        return view('forecasts.index', compact(
            'branches', 'selectedBranchId', 'forecastPeriod', 'historyPeriod',
            'forecastDates', 'forecastValues', 'forecastLower', 'forecastUpper',
            'historyDates', 'historyValues', 'historyForecastValues',
            'accuracy', 'topProductsForecasts'
        ));
    }
}
