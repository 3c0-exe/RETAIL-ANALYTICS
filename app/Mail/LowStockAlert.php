<?php

namespace App\Mail;

use App\Models\Alert;
use App\Models\BranchProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;
    public $branchProduct;
    public $stockPercentage;
    public $urgencyLevel;

    /**
     * Create a new message instance.
     */
    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
        $this->branchProduct = $alert->related;

        // Calculate stock percentage
        $this->stockPercentage = round(
            ($this->branchProduct->quantity / $this->branchProduct->low_stock_threshold) * 100
        );

        // Determine urgency
        $this->urgencyLevel = $this->stockPercentage <= 50 ? 'CRITICAL' : 'WARNING';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->urgencyLevel === 'CRITICAL'
            ? '🚨 CRITICAL: Low Stock Alert'
            : '⚠️ Low Stock Alert';

        return new Envelope(
            subject: $subject . ' - ' . $this->branchProduct->product->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.alerts.low-stock',
            with: [
                'productName' => $this->branchProduct->product->name,
                'branchName' => $this->branchProduct->branch->name,
                'currentStock' => $this->branchProduct->quantity,
                'threshold' => $this->branchProduct->low_stock_threshold,
                'stockPercentage' => $this->stockPercentage,
                'urgencyLevel' => $this->urgencyLevel,
                'sku' => $this->branchProduct->product->sku,
                'category' => $this->branchProduct->product->category,
                'userName' => $this->alert->user->name,
                'alertUrl' => route('products.index'), // Adjust to your route
            ],
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
