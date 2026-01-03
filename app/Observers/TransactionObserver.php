<?php

// app/Observers/TransactionObserver.php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\NotificationService;

class TransactionObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // Check for high value transaction
        if ($transaction->total_amount >= NotificationService::THRESHOLDS['high_value_transaction']) {
            $this->notificationService->notify(
                type: 'high_value_transaction',
                title: 'High Value Transaction',
                message: "A high-value transaction of ₱" . number_format($transaction->total_amount, 2) .
                         " was recorded at {$transaction->branch->name}",
                severity: 'info',
                related: $transaction,
                targetUser: $transaction->branch->manager,
                metadata: [
                    'transaction_code' => $transaction->transaction_code,
                    'amount' => number_format($transaction->total_amount, 2),
                    'branch' => $transaction->branch->name,
                    'cashier' => $transaction->cashier?->name ?? 'N/A',
                    'payment_method' => $transaction->payment_method,
                ]
            );
        }
    }
}
