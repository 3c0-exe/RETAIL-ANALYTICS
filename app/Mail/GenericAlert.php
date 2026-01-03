<?php

// app/Mail/GenericAlert.php

namespace App\Mail;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;

    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    public function envelope(): Envelope
    {
        $emoji = match($this->alert->severity) {
            'critical' => '🚨',
            'warning' => '⚠️',
            'info' => 'ℹ️',
            default => '🔔',
        };

        return new Envelope(
            subject: $emoji . ' ' . $this->alert->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.alerts.generic',
            with: [
                'alertTitle' => $this->alert->title,
                'alertMessage' => $this->alert->message,
                'alertType' => $this->alert->type,
                'severity' => $this->alert->severity,
                'userName' => $this->alert->user->name,
                'metadata' => $this->alert->metadata ?? [],
                'alertUrl' => url('/notifications'),
                'createdAt' => $this->alert->created_at->format('M d, Y g:i A'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
