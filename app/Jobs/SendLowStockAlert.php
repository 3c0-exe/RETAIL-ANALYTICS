<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLowStockAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alert;

    public function __construct(Alert $alert)
    {
        // Load relationships before serialization
        $alert->load(['user', 'related.product', 'related.branch']);
        $this->alert = $alert;
    }

    public function handle(): void
    {
        try {
            // Verify user has email
            if (empty($this->alert->user->email)) {
                Log::warning("User {$this->alert->user->id} has no email");
                return;
            }

            // Verify related data exists
            if (!$this->alert->related) {
                Log::error("Alert {$this->alert->id} has no related data");
                return;
            }

            // Send email
            Mail::to($this->alert->user->email)
                ->send(new LowStockAlert($this->alert));

            Log::info("✓ Low stock alert sent to {$this->alert->user->email}");

        } catch (\Exception $e) {
            Log::error('Failed to send alert email', [
                'alert_id' => $this->alert->id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendLowStockAlert job failed permanently', [
            'alert_id' => $this->alert->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
