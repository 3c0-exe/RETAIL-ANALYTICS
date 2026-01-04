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
    public $deviationType; // 'over' or 'under'
    public $severity; // 'warning' or 'critical'
    public $alertUrl;

    /**
     * Create a new message instance.
     */
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

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $emoji = $this->severity === 'critical' ? '🚨' : '⚠️';
        $subject = "{$emoji} Forecast Deviation Alert - {$this->branchName}";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.forecast-deviation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
