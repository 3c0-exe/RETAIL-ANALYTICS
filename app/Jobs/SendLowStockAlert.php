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

class SendLowStockAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alert;

    /**
     * Create a new job instance.
     */
    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send email to the user
        Mail::to($this->alert->user->email)
            ->send(new LowStockAlert($this->alert));
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Log the failure
        \Log::error('Failed to send low stock alert email', [
            'alert_id' => $this->alert->id,
            'user_email' => $this->alert->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
