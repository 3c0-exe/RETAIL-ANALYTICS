<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id', // ADDED
        'file_name',
        'file_path',
        'import_type',
        'total_rows',
        'successful_rows',
        'failed_rows',
        'status',
        'errors',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'successful_rows' => 'integer',
        'failed_rows' => 'integer',
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Helper methods
    public function markAsProcessing()
    {
        $this->status = 'processing';
        $this->started_at = now();
        $this->save();
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    public function markAsFailed($errors = [])
    {
        $this->status = 'failed';
        $this->errors = $errors;
        $this->completed_at = now();
        $this->save();
    }

    public function getSuccessRate()
    {
        if ($this->total_rows === 0) return 0;
        return round(($this->successful_rows / $this->total_rows) * 100, 2);
    }
}
