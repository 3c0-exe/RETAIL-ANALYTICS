<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Services\ForecastService;
use Illuminate\Console\Command;

class GenerateForecasts extends Command
{
    protected $signature = 'forecast:generate {--branch_id=}';
    protected $description = 'Generate sales forecasts for all active branches';

    public function handle(ForecastService $forecastService)
    {
        $this->info('Starting forecast generation...');

        // Get branches to process
        $branches = $this->option('branch_id')
            ? Branch::where('id', $this->option('branch_id'))->where('status', 'active')->get()
            : Branch::where('status', 'active')->get();

        if ($branches->isEmpty()) {
            $this->error('No active branches found!');
            return 1;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($branches as $branch) {
            $this->info("Processing {$branch->name}...");

            try {
                $forecastService->generateForecasts($branch, 30);
                $this->info("✓ Forecasts generated for {$branch->name}");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("✗ Error for {$branch->name}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->newLine();
        $this->info("Forecast generation complete!");
        $this->info("Success: {$successCount} branches");

        if ($errorCount > 0) {
            $this->warn("Errors: {$errorCount} branches");
        }

        return 0;
    }
}
