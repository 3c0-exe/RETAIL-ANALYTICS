<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity(): void
    {
        // Log when a model is created
        static::created(function ($model) {
            $model->logActivity('created');
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $model->logActivity('updated', $model->getChanges());
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Log an activity
     */
    protected function logActivity(string $action, array $changes = []): void
    {
        // Don't log if no user is authenticated
        if (!Auth::check()) {
            return;
        }

        // Get model name without namespace
        $modelName = class_basename($this);

        // Build description
        $description = $this->buildActivityDescription($action, $modelName);

        // Create activity log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $modelName,
            'model_id' => $this->id,
            'description' => $description,
            'changes' => !empty($changes) ? json_encode($changes) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Build a human-readable description
     */
    protected function buildActivityDescription(string $action, string $modelName): string
    {
        $userName = Auth::user()->name;
        $identifier = $this->getActivityIdentifier();

        return match ($action) {
            'created' => "{$userName} created {$modelName}: {$identifier}",
            'updated' => "{$userName} updated {$modelName}: {$identifier}",
            'deleted' => "{$userName} deleted {$modelName}: {$identifier}",
            default => "{$userName} performed {$action} on {$modelName}: {$identifier}",
        };
    }

    /**
     * Get a human-readable identifier for the model
     * Override this method in your models for custom identifiers
     */
    protected function getActivityIdentifier(): string
    {
        // Try common identifier fields
        if (isset($this->name)) {
            return $this->name;
        }

        if (isset($this->title)) {
            return $this->title;
        }

        if (isset($this->email)) {
            return $this->email;
        }

        // Fallback to ID
        return "ID: {$this->id}";
    }

    /**
     * Get activity logs for this model
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }
}
