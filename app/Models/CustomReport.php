<?php

// CREATE THIS FILE: app/Models/CustomReport.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'config',
        'is_favorite'
    ];

    protected $casts = [
        'config' => 'array',
        'is_favorite' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getMetrics()
    {
        return $this->config['metrics'] ?? [];
    }

    public function getDimensions()
    {
        return $this->config['dimensions'] ?? [];
    }

    public function getDateRange()
    {
        return $this->config['date_range'] ?? null;
    }

    public function getChartType()
    {
        return $this->config['chart_type'] ?? 'bar';
    }
}
