<?php

// app/Observers/ImportObserver.php

namespace App\Observers;

use App\Models\Import;
use App\Services\NotificationService;

class ImportObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Import "updated" event.
     */
    public function updated(Import $import): void
    {
        // Only notify when status changes to completed or failed
        if ($import->wasChanged('status') && in_array($import->status, ['completed', 'failed'])) {
            $severity = $import->status === 'completed' ? 'info' : 'warning';
            $emoji = $import->status === 'completed' ? '✅' : '❌';

            $message = $import->status === 'completed'
                ? "{$emoji} Import '{$import->file_name}' completed successfully. Processed {$import->successful_rows} rows."
                : "{$emoji} Import '{$import->file_name}' failed. {$import->failed_rows} rows failed.";

            $this->notificationService->notify(
                type: 'import_completion',
                title: ucfirst($import->status) . ' Import',
                message: $message,
                severity: $severity,
                related: $import,
                targetUser: $import->user,
                metadata: [
                    'file_name' => $import->file_name,
                    'status' => $import->status,
                    'total_rows' => $import->total_rows,
                    'successful_rows' => $import->successful_rows,
                    'failed_rows' => $import->failed_rows,
                ]
            );
        }
    }
}
