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
            // Determine actual status based on success/failure ratio
            $allFailed = $import->successful_rows === 0 && $import->failed_rows > 0;
            $actualStatus = $allFailed ? 'failed' : $import->status;

            $severity = $actualStatus === 'completed' ? 'info' : 'warning';
            $emoji = $actualStatus === 'completed' ? '✅' : '❌';

            if ($actualStatus === 'completed') {
                $message = "{$emoji} Import '{$import->file_name}' completed successfully. Processed {$import->successful_rows} rows.";
            } else {
                $message = "{$emoji} Import '{$import->file_name}' failed. All {$import->failed_rows} rows failed to process.";
            }

            $this->notificationService->notify(
                type: 'import_completion',
                title: ucfirst($actualStatus) . ' Import',
                message: $message,
                severity: $severity,
                related: $import,
                targetUser: $import->user,
                metadata: [
                    'file_name' => $import->file_name,
                    'status' => $actualStatus,
                    'total_rows' => $import->total_rows,
                    'successful_rows' => $import->successful_rows,
                    'failed_rows' => $import->failed_rows,
                ]
            );
        }
    }
}
