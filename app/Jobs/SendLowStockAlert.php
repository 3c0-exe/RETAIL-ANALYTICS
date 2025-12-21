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
use Symfony\Component\Mailer\Exception\TransportException;

class SendLowStockAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alert;

    // Retry configuration
    public $tries = 5; // Try up to 5 times
    public $maxExceptions = 3; // Allow 3 exceptions before giving up

    // Timeout
    public $timeout = 120; // 2 minutes max

    public function __construct(Alert $alert)
    {
        // Load relationships before serialization
        $alert->load(['user', 'related.product', 'related.branch']);
        $this->alert = $alert;
    }

    /**
     * Calculate exponential backoff delay based on attempt number
     */
    public function backoff(): array
    {
        return [30, 60, 120, 300]; // 30s, 1m, 2m, 5m
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

            // Add delay between emails to avoid rate limits
            // Increase delay based on attempt number
            $baseDelay = 5; // 5 seconds base
            $attemptDelay = ($this->attempts() - 1) * 2; // Add 2s per retry
            $totalDelay = $baseDelay + $attemptDelay;

            sleep($totalDelay);

            // Send email
            Mail::to($this->alert->user->email)
                ->send(new LowStockAlert($this->alert));

            Log::info("✓ Low stock alert sent successfully", [
                'alert_id' => $this->alert->id,
                'email' => $this->alert->user->email,
                'product' => $this->alert->related->product->name ?? 'Unknown',
                'attempt' => $this->attempts(),
                'delay_used' => $totalDelay . 's',
            ]);

        } catch (TransportException $e) {
            // Handle Mailtrap rate limit errors specifically
            if (str_contains($e->getMessage(), 'Too many emails') ||
                str_contains($e->getMessage(), '550 5.7.0')) {

                Log::warning('⚠️ Mailtrap rate limit hit - Will retry with longer delay', [
                    'alert_id' => $this->alert->id,
                    'email' => $this->alert->user->email,
                    'attempt' => $this->attempts(),
                    'max_tries' => $this->tries,
                    'next_retry_in' => '60 seconds',
                ]);

                // Release back to queue with 60 second delay
                // This gives Mailtrap time to reset rate limits
                $this->release(60);
                return;
            }

            // Other transport errors - log and retry
            Log::error('Email transport error', [
                'alert_id' => $this->alert->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            throw $e;

        } catch (\Exception $e) {
            Log::error('Failed to send alert email', [
                'alert_id' => $this->alert->id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger automatic retry mechanism
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('❌ SendLowStockAlert job failed permanently after all retries', [
            'alert_id' => $this->alert->id,
            'user_email' => $this->alert->user->email ?? 'no email',
            'product' => $this->alert->related->product->name ?? 'Unknown',
            'branch' => $this->alert->related->branch->name ?? 'Unknown',
            'total_attempts' => $this->attempts(),
            'error' => $exception->getMessage(),
        ]);

        // Optionally: Mark alert as failed in database for tracking
        try {
            $this->alert->update([
                'metadata' => array_merge($this->alert->metadata ?? [], [
                    'email_failed' => true,
                    'failed_at' => now()->toDateTimeString(),
                    'error' => substr($exception->getMessage(), 0, 500),
                    'attempts' => $this->attempts(),
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('Could not update alert metadata', [
                'alert_id' => $this->alert->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
