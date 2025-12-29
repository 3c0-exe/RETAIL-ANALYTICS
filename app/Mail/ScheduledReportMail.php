<?php

// CREATE THIS FILE: app/Mail/ScheduledReportMail.php
// Run: php artisan make:mail ScheduledReportMail

namespace App\Mail;

use App\Models\ScheduledReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ScheduledReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $scheduledReport;
    public $reportData;
    public $attachmentPath;

    public function __construct(ScheduledReport $scheduledReport, $reportData, $attachmentPath = null)
    {
        $this->scheduledReport = $scheduledReport;
        $this->reportData = $reportData;
        $this->attachmentPath = $attachmentPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->scheduledReport->name . ' - ' . now()->format('M d, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.scheduled-report',
            with: [
                'reportName' => $this->scheduledReport->name,
                'reportType' => $this->scheduledReport->report_type,
                'frequency' => $this->scheduledReport->frequency,
                'summary' => $this->reportData['summary'] ?? [],
                'generatedAt' => now()->format('F d, Y \a\t g:i A')
            ]
        );
    }

    public function attachments(): array
    {
        if ($this->attachmentPath && file_exists($this->attachmentPath)) {
            return [
                Attachment::fromPath($this->attachmentPath)
                    ->as('report.' . $this->scheduledReport->format)
                    ->withMime($this->getMimeType())
            ];
        }

        return [];
    }

    private function getMimeType()
    {
        return match($this->scheduledReport->format) {
            'pdf' => 'application/pdf',
            'excel' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            default => 'application/octet-stream'
        };
    }
}
