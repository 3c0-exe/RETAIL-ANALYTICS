<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'report_type',
        'custom_report_id',
        'frequency',
        'day_of_week',
        'day_of_month',
        'time',
        'recipients',
        'format',
        'is_active',
        'last_run_at',
        'next_run_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'time' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customReport()
    {
        return $this->belongsTo(CustomReport::class);
    }

    public function logs()
    {
        return $this->hasMany(ReportLog::class);
    }

    // Calculate next run time
    public function calculateNextRun()
    {
        $now = Carbon::now();
        $time = Carbon::parse($this->time);

        switch ($this->frequency) {
            case 'daily':
                $next = Carbon::today()->setTime($time->hour, $time->minute);
                if ($next->isPast()) {
                    $next->addDay();
                }
                break;

            case 'weekly':
                $dayMap = [
                    'monday' => Carbon::MONDAY,
                    'tuesday' => Carbon::TUESDAY,
                    'wednesday' => Carbon::WEDNESDAY,
                    'thursday' => Carbon::THURSDAY,
                    'friday' => Carbon::FRIDAY,
                    'saturday' => Carbon::SATURDAY,
                    'sunday' => Carbon::SUNDAY,
                ];

                $targetDay = $dayMap[$this->day_of_week] ?? Carbon::MONDAY;
                $next = Carbon::now()->next($targetDay)->setTime($time->hour, $time->minute);
                break;

            case 'monthly':
                $next = Carbon::now()->day($this->day_of_month)->setTime($time->hour, $time->minute);
                if ($next->isPast()) {
                    $next->addMonth();
                }
                break;

            default:
                $next = Carbon::tomorrow();
        }

        return $next;
    }

    // Check if report should run now
    public function shouldRun()
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->next_run_at) {
            return true;
        }

        return Carbon::now()->greaterThanOrEqualTo($this->next_run_at);
    }
}
