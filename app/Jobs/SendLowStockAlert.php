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
    public $tries = 3; // Retry 3 times if failed
    public $backoff = [10, 30, 60]; // Wait 10s, 30s, 60s between retries

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

            // Add delay between emails to respect Mailtrap rate limits
            sleep(2); // Wait 2 seconds before sending

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

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendLowStockAlert job failed permanently', [
            'alert_id' => $this->alert->id,
            'user_email' => $this->alert->user->email ?? 'no email',
            'error' => $exception->getMessage(),
        ]);
    }
}
