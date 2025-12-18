<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Forecast;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastService
{
    /**
     * Generate forecasts using Exponential Smoothing (Simple implementation)
     * This replaces Prophet as a Windows-friendly alternative
     */
    public function generateForecasts(Branch $branch, int $days = 30)
    {
        // Clear existing future forecasts for this branch
        Forecast::where('branch_id', $branch->id)
            ->where('forecast_date', '>=', Carbon::today())
            ->delete();

        // Get historical sales data (last 90 days)
        $historicalData = $this->getHistoricalSales($branch->id, 90);

        if ($historicalData->isEmpty()) {
            throw new \Exception('Not enough historical data to generate forecasts. Need at least 7 days of sales data.');
        }

        // Generate overall branch forecasts
        $this->generateBranchForecasts($branch, $historicalData, $days);

        // Generate product-level forecasts for top products
        $this->generateProductForecasts($branch, $days);

        return true;
    }

    /**
     * Get historical sales data
     */
    private function getHistoricalSales($branchId, $days)
    {
        return Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('timestamp', '>=', Carbon::now()->subDays($days))
            ->where('timestamp', '<', Carbon::today())
            ->selectRaw('DATE(timestamp) as date, SUM(total) as sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Generate branch-level forecasts using Exponential Smoothing
     */
    private function generateBranchForecasts(Branch $branch, $historicalData, $days)
    {
        $salesValues = $historicalData->pluck('sales')->toArray();

        // Calculate exponential smoothing forecast
        $alpha = 0.3; // Smoothing factor (0-1, lower = more smoothing)
        $forecasts = $this->exponentialSmoothing($salesValues, $days, $alpha);

        // Calculate confidence intervals (±20% as a simple estimate)
        foreach ($forecasts as $i => $forecast) {
            $forecastDate = Carbon::today()->addDays($i + 1);
            $margin = $forecast * 0.20; // 20% confidence margin

            Forecast::create([
                'branch_id' => $branch->id,
                'product_id' => null,
                'category' => null,
                'forecast_date' => $forecastDate,
                'predicted_sales' => round($forecast, 2),
                'confidence_lower' => round(max(0, $forecast - $margin), 2),
                'confidence_upper' => round($forecast + $margin, 2),
                'model_version' => 'exponential_smoothing_v1',
                'metadata' => json_encode(['alpha' => $alpha]),
            ]);
        }
    }

    /**
     * Generate product-level forecasts for top 10 products
     */
    private function generateProductForecasts(Branch $branch, $days)
    {
        // Get top 10 products by sales volume
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.branch_id', $branch->id)
            ->where('transactions.status', 'completed')
            ->where('transactions.timestamp', '>=', Carbon::now()->subDays(90))
            ->select('transaction_items.product_id', DB::raw('SUM(transaction_items.subtotal) as total_sales'))
            ->whereNotNull('transaction_items.product_id')
            ->groupBy('transaction_items.product_id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        foreach ($topProducts as $productData) {
            $productId = $productData->product_id;

            // Get product historical sales
            $productSales = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transactions.branch_id', $branch->id)
                ->where('transaction_items.product_id', $productId)
                ->where('transactions.status', 'completed')
                ->where('transactions.timestamp', '>=', Carbon::now()->subDays(90))
                ->where('transactions.timestamp', '<', Carbon::today())
                ->selectRaw('DATE(transactions.timestamp) as date, SUM(transaction_items.subtotal) as sales')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            if ($productSales->count() < 7) {
                continue; // Skip products without enough data
            }

            $salesValues = $productSales->pluck('sales')->toArray();
            $forecasts = $this->exponentialSmoothing($salesValues, $days, 0.3);

            foreach ($forecasts as $i => $forecast) {
                $forecastDate = Carbon::today()->addDays($i + 1);
                $margin = $forecast * 0.25;

                Forecast::create([
                    'branch_id' => $branch->id,
                    'product_id' => $productId,
                    'category' => null,
                    'forecast_date' => $forecastDate,
                    'predicted_sales' => round($forecast, 2),
                    'confidence_lower' => round(max(0, $forecast - $margin), 2),
                    'confidence_upper' => round($forecast + $margin, 2),
                    'model_version' => 'exponential_smoothing_v1',
                    'metadata' => null,
                ]);
            }
        }
    }

    /**
     * Exponential Smoothing Algorithm
     * Simple implementation suitable for daily sales forecasting
     */
    private function exponentialSmoothing(array $data, int $forecastPeriods, float $alpha = 0.3)
    {
        if (empty($data)) {
            return array_fill(0, $forecastPeriods, 0);
        }

        $forecasts = [];
        $smoothed = $data[0]; // Initialize with first value

        // Smooth historical data
        foreach ($data as $value) {
            $smoothed = $alpha * $value + (1 - $alpha) * $smoothed;
        }

        // Generate future forecasts (flat forecast from last smoothed value)
        for ($i = 0; $i < $forecastPeriods; $i++) {
            $forecasts[] = $smoothed;
        }

        return $forecasts;
    }

    /**
     * Get accuracy metrics for recent forecasts
     */
    public function getAccuracyMetrics(Branch $branch, int $days = 7)
    {
        $startDate = Carbon::now()->subDays($days);

        $forecasts = Forecast::where('branch_id', $branch->id)
            ->whereNull('product_id')
            ->whereBetween('forecast_date', [$startDate, Carbon::yesterday()])
            ->get();

        $accuracies = [];

        foreach ($forecasts as $forecast) {
            $accuracy = $forecast->getAccuracy();
            if ($accuracy !== null) {
                $accuracies[] = $accuracy;
            }
        }

        return [
            'average_accuracy' => count($accuracies) > 0 ? round(array_sum($accuracies) / count($accuracies), 2) : null,
            'forecasts_evaluated' => count($accuracies),
            'total_forecasts' => $forecasts->count(),
        ];
    }
}
