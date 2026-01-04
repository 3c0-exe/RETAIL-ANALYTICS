<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForecastDeviationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $branchName;
    public $date;
    public $forecastedAmount;
    public $actualAmount;
    public $deviationPercent;
    public $deviationType;
    public $severity;
    public $alertUrl;

    public function __construct(
        string $userName,
        string $branchName,
        string $date,
        float $forecastedAmount,
        float $actualAmount,
        float $deviationPercent,
        string $deviationType,
        string $severity,
        string $alertUrl
    ) {
        $this->userName = $userName;
        $this->branchName = $branchName;
        $this->date = $date;
        $this->forecastedAmount = $forecastedAmount;
        $this->actualAmount = $actualAmount;
        $this->deviationPercent = $deviationPercent;
        $this->deviationType = $deviationType;
        $this->severity = $severity;
        $this->alertUrl = $alertUrl;
    }

    public function envelope(): Envelope
    {
        $emoji = $this->severity === 'critical' ? '🚨' : '⚠️';
        $subject = "{$emoji} Forecast Deviation Alert - {$this->branchName}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.forecast-deviation',
            with: [
                // Map property names to what the view expects
                'userName' => $this->userName,
                'branchName' => $this->branchName,
                'date' => $this->date,
                'forecastedSales' => $this->forecastedAmount,  // ✅ Fixed
                'actualSales' => $this->actualAmount,          // ✅ Fixed
                'deviationPercentage' => $this->deviationPercent, // ✅ Fixed
                'deviationType' => $this->deviationType,
                'severity' => $this->severity,
                'dashboardUrl' => $this->alertUrl,  // ✅ Fixed
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
