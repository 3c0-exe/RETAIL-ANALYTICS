<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_report_id',
        'sent_at',
        'status',
        'error_message',
        'recipient_count'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function scheduledReport()
    {
        return $this->belongsTo(ScheduledReport::class);
    }
}
