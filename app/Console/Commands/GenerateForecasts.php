<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Services\ForecastService;
use Illuminate\Console\Command;

class GenerateForecasts extends Command
{
    protected $signature = 'forecast:generate {--branch= : Branch ID to forecast} {--days=30 : Number of days to forecast}';
    protected $description = 'Generate sales forecasts for branches';

    public function handle(ForecastService $forecastService): int
    {
        $branchId = $this->option('branch');
        $days = (int) $this->option('days');

        $branches = $branchId
            ? Branch::where('id', $branchId)->get()
            : Branch::where('status', 'active')->get();

        if ($branches->isEmpty()) {
            $this->error('No branches found.');
            return self::FAILURE;
        }

        $this->info("Generating {$days}-day forecasts for " . $branches->count() . " branch(es)...");

        foreach ($branches as $branch) {
            $this->line("Processing: {$branch->name}");

            try {
                $forecastService->generateForecasts($branch, $days);
                $this->info("✓ {$branch->name} - Forecasts generated");
            } catch (\Exception $e) {
                $this->error("✗ {$branch->name} - Error: " . $e->getMessage());
            }
        }

        $this->info('Forecast generation complete!');
        return self::SUCCESS;
    }
}
