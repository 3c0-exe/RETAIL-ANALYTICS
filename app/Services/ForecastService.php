<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Forecast;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastService
{
    /**
     * Generate forecasts for a branch (uses simple moving average)
     */
    public function generateForecasts(Branch $branch, int $days = 30): void
    {
        // Get historical sales data (last 60 days)
        $historicalData = $this->getHistoricalSales($branch, 60);

        if (count($historicalData) < 7) {
            throw new \Exception("Insufficient historical data. Need at least 7 days of sales.");
        }

        // Delete old forecasts for this branch
        Forecast::where('branch_id', $branch->id)
            ->where('forecast_date', '>=', Carbon::today())
            ->delete();

        // Generate overall branch forecasts
        $this->generateBranchForecasts($branch, $historicalData, $days);

        // Generate top products forecasts
        $this->generateProductForecasts($branch, $days);
    }

    /**
     * Get historical daily sales for a branch
     */
    private function getHistoricalSales(Branch $branch, int $days): array
    {
        $startDate = Carbon::now()->subDays($days);

        $sales = Transaction::where('branch_id', $branch->id)
            ->where('timestamp', '>=', $startDate)
            ->where('timestamp', '<', Carbon::today())
            ->where('status', 'completed')
            ->selectRaw('DATE(timestamp) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        return $sales;
    }

    /**
     * Generate branch-level forecasts using moving average
     */
    private function generateBranchForecasts(Branch $branch, array $historicalData, int $days): void
    {
        // Calculate 7-day moving average
        $values = array_values($historicalData);
        $recentValues = array_slice($values, -7);
        $average = array_sum($recentValues) / count($recentValues);

        // Calculate standard deviation for confidence intervals
        $variance = 0;
        foreach ($recentValues as $value) {
            $variance += pow($value - $average, 2);
        }
        $stdDev = sqrt($variance / count($recentValues));

        // Generate forecasts
        for ($i = 0; $i < $days; $i++) {
            $forecastDate = Carbon::today()->addDays($i);

            // Add slight trend and day-of-week variation
            $dayOfWeek = $forecastDate->dayOfWeek;
            $weekendMultiplier = in_array($dayOfWeek, [0, 6]) ? 1.3 : 1.0; // 30% higher on weekends

            $predicted = $average * $weekendMultiplier;
            $confidenceLower = $predicted - (1.96 * $stdDev); // 95% confidence
            $confidenceUpper = $predicted + (1.96 * $stdDev);

            Forecast::create([
                'branch_id' => $branch->id,
                'product_id' => null,
                'category' => null,
                'forecast_date' => $forecastDate,
                'predicted_sales' => round($predicted, 2),
                'confidence_lower' => round(max(0, $confidenceLower), 2),
                'confidence_upper' => round($confidenceUpper, 2),
                'model_version' => 'moving_average_v1',
            ]);
        }
    }

    /**
     * Generate product-level forecasts for top products
     */
    private function generateProductForecasts(Branch $branch, int $days): void
    {
        // Get top 10 products by sales volume (last 30 days)
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.branch_id', $branch->id)
            ->where('transactions.status', 'completed')
            ->where('transactions.timestamp', '>=', Carbon::now()->subDays(30))
            ->select('transaction_items.product_id', DB::raw('SUM(transaction_items.quantity) as total_quantity'))
            ->groupBy('transaction_items.product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        foreach ($topProducts as $productData) {
            $product = Product::find($productData->product_id);
            if (!$product) continue;

            // Get historical sales for this product (last 30 days)
            $productSales = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transactions.branch_id', $branch->id)
                ->where('transaction_items.product_id', $product->id)
                ->where('transactions.status', 'completed')
                ->where('transactions.timestamp', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(transactions.timestamp) as date, SUM(transaction_items.subtotal) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date')
                ->toArray();

            if (count($productSales) < 7) continue;

            $values = array_values($productSales);
            $average = array_sum($values) / count($values);

            // Generate forecasts for this product
            for ($i = 0; $i < $days; $i++) {
                $forecastDate = Carbon::today()->addDays($i);

                $dayOfWeek = $forecastDate->dayOfWeek;
                $weekendMultiplier = in_array($dayOfWeek, [0, 6]) ? 1.2 : 1.0;

                $predicted = $average * $weekendMultiplier;

                Forecast::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'category' => null,
                    'forecast_date' => $forecastDate,
                    'predicted_sales' => round($predicted, 2),
                    'confidence_lower' => round($predicted * 0.8, 2),
                    'confidence_upper' => round($predicted * 1.2, 2),
                    'model_version' => 'moving_average_v1',
                ]);
            }
        }
    }

    /**
     * Get accuracy metrics by comparing past forecasts with actuals
     */
    public function getAccuracyMetrics(Branch $branch, int $days = 7): array
    {
        $startDate = Carbon::now()->subDays($days);

        // Get past forecasts
        $forecasts = Forecast::where('branch_id', $branch->id)
            ->whereNull('product_id')
            ->whereBetween('forecast_date', [$startDate, Carbon::yesterday()])
            ->get()
            ->keyBy('forecast_date');

        // Get actual sales
        $actuals = Transaction::where('branch_id', $branch->id)
            ->where('timestamp', '>=', $startDate)
            ->where('timestamp', '<', Carbon::today())
            ->where('status', 'completed')
            ->selectRaw('DATE(timestamp) as date, SUM(total) as total')
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $errors = [];
        foreach ($forecasts as $date => $forecast) {
            $actual = $actuals[$date] ?? 0;
            if ($actual > 0) {
                $error = abs($forecast->predicted_sales - $actual) / $actual * 100;
                $errors[] = $error;
            }
        }

        if (empty($errors)) {
            return [
                'mape' => 0, // Mean Absolute Percentage Error
                'accuracy' => 0,
                'sample_size' => 0,
            ];
        }

        $mape = array_sum($errors) / count($errors);
        $accuracy = max(0, 100 - $mape);

        return [
            'mape' => round($mape, 2),
            'accuracy' => round($accuracy, 2),
            'sample_size' => count($errors),
        ];
    }
}
