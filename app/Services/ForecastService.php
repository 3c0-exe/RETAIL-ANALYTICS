<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Forecast;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForecastService
{
    /**
     * Generate forecasts using Holt-Winters Triple Exponential Smoothing
     */
    public function generateForecasts(Branch $branch, int $days = 30)
    {
        // 1. Clear future forecasts to prevent duplicates
        Forecast::where('branch_id', $branch->id)
            ->where('forecast_date', '>=', Carbon::today())
            ->delete();

        // 2. Get historical data (Need at least 14 days for weekly patterns)
        $historicalData = $this->getHistoricalSales($branch->id, 90);

        if ($historicalData->count() < 14) {
             Log::warning("Not enough data for Branch {$branch->id}");
             return false;
        }

        // 3. Generate Branch Forecasts
        $this->generateBranchForecasts($branch, $historicalData, $days);

        // 4. Generate Product Forecasts (Top 10)
        $this->generateProductForecasts($branch, $days);

        return true;
    }

    private function getHistoricalSales($branchId, $days)
    {
        return Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('timestamp', '>=', Carbon::now()->subDays($days))
            ->where('timestamp', '<', Carbon::today())
            ->selectRaw('DATE(timestamp) as date, SUM(total_amount) as sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function generateBranchForecasts(Branch $branch, $historicalData, $days)
    {
        $salesValues = $historicalData->pluck('sales')->toArray();

        // Holt-Winters: Season=7 (Weekly), Alpha=0.2 (Level), Gamma=0.4 (Seasonality)
        $forecasts = $this->holtWinters($salesValues, $days, 7, 0.2, 0.01, 0.4);

        // SAVE TO DB LOOP
        foreach ($forecasts as $i => $forecast) {
            $forecastDate = Carbon::today()->addDays($i + 1);
            $margin = $forecast * 0.20; // 20% Confidence Interval

            Forecast::create([
                'branch_id'      => $branch->id,
                'forecast_date'  => $forecastDate,
                'predicted_sales'=> round($forecast, 2),
                'confidence_lower' => round(max(0, $forecast - $margin), 2),
                'confidence_upper' => round($forecast + $margin, 2),
                'model_version'  => 'holt_winters_v1',
                'metadata'       => json_encode(['method' => 'Holt-Winters', 'season' => 7]),
            ]);
        }
    }

    private function generateProductForecasts(Branch $branch, $days)
    {
        // Get top 10 products
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.branch_id', $branch->id)
            ->where('transactions.status', 'completed')
            ->where('transactions.timestamp', '>=', Carbon::now()->subDays(90))
            ->select('transaction_items.product_id', DB::raw('SUM(transaction_items.subtotal) as total_sales'))
            ->groupBy('transaction_items.product_id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        foreach ($topProducts as $productData) {
            $productId = $productData->product_id;

            // Get product history
            $productSales = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transactions.branch_id', $branch->id)
                ->where('transaction_items.product_id', $productId)
                ->where('transactions.timestamp', '>=', Carbon::now()->subDays(90))
                ->where('transactions.timestamp', '<', Carbon::today())
                ->selectRaw('DATE(transactions.timestamp) as date, SUM(transaction_items.subtotal) as sales')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            if ($productSales->count() < 14) continue;

            $salesValues = $productSales->pluck('sales')->toArray();
            $forecasts = $this->holtWinters($salesValues, $days, 7, 0.2, 0.0, 0.3);

            foreach ($forecasts as $i => $forecast) {
                Forecast::create([
                    'branch_id'      => $branch->id,
                    'product_id'     => $productId,
                    'forecast_date'  => Carbon::today()->addDays($i + 1),
                    'predicted_sales'=> round($forecast, 2),
                    'confidence_lower' => round(max(0, $forecast * 0.75), 2),
                    'confidence_upper' => round($forecast * 1.25, 2),
                    'model_version'  => 'holt_winters_v1',
                ]);
            }
        }
    }

    private function holtWinters(array $data, int $forecastDays, int $seasonLength = 7, float $alpha = 0.2, float $beta = 0.01, float $gamma = 0.3)
    {
        $n = count($data);
        if ($n < $seasonLength * 2) return array_fill(0, $forecastDays, array_sum($data)/$n);

        // Init
        $level = 0;
        for ($i = 0; $i < $seasonLength; $i++) $level += $data[$i];
        $level /= $seasonLength;
        $trend = 0;
        $seasonals = [];
        for ($i = 0; $i < $seasonLength; $i++) $seasonals[$i] = $data[$i] - $level;

        // Learn
        for ($i = $seasonLength; $i < $n; $i++) {
            $val = $data[$i];
            $prevLevel = $level;
            $seasonIndex = $i % $seasonLength;
            $prevSeasonal = $seasonals[$seasonIndex];

            $level = $alpha * ($val - $prevSeasonal) + (1 - $alpha) * ($prevLevel + $trend);
            $trend = $beta * ($level - $prevLevel) + (1 - $beta) * $trend;
            $seasonals[$seasonIndex] = $gamma * ($val - $level) + (1 - $gamma) * $prevSeasonal;
        }

        // Forecast
        $predictions = [];
        for ($i = 1; $i <= $forecastDays; $i++) {
            $seasonIndex = ($n + $i - 1) % $seasonLength;
            $predictions[] = max(0, $level + ($i * $trend) + $seasonals[$seasonIndex]);
        }
        return $predictions;
    }
}
