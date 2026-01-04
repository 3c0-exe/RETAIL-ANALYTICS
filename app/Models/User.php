<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\LogsActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // Keep for backwards compatibility
        'role_id',     // NEW: Link to roles table
        'branch_id',
        'avatar',
        'theme',
        'two_factor_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array', // Add this
        ];
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function managedBranch()
    {
        return $this->hasOne(Branch::class, 'manager_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function customReports()
    {
        return $this->hasMany(CustomReport::class);
    }

    public function scheduledReports()
    {
        return $this->hasMany(ScheduledReport::class);
    }

    // NEW: Role & Permission relationships
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permissionOverrides()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    // Permission checking methods
    public function hasPermission(string $permissionName): bool
    {
        // Check if permission is explicitly denied at user level
        $override = $this->permissionOverrides()
            ->where('permissions.name', $permissionName)
            ->first();

        if ($override) {
            return (bool) $override->pivot->granted;
        }

        // Check role permissions
        if ($this->roleModel) {
            return $this->roleModel->hasPermission($permissionName);
        }

        // Fallback to old role system for backwards compatibility
        return $this->hasLegacyPermission($permissionName);
    }

    public function can($ability, $arguments = [])
    {
        // Override Laravel's default can() to use our permission system
        if (is_string($ability) && strpos($ability, '.') !== false) {
            return $this->hasPermission($ability);
        }
        return parent::can($ability, $arguments);
    }

    // Grant/revoke permissions at user level
    public function grantPermission(Permission $permission): void
    {
        $this->permissionOverrides()->syncWithoutDetaching([
            $permission->id => ['granted' => true]
        ]);
    }

    public function revokePermission(Permission $permission): void
    {
        $this->permissionOverrides()->syncWithoutDetaching([
            $permission->id => ['granted' => false]
        ]);
    }

    // Helper methods (keep existing)
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || ($this->roleModel && $this->roleModel->name === 'admin');
    }

    public function isBranchManager(): bool
    {
        return $this->role === 'branch_manager' || ($this->roleModel && $this->roleModel->name === 'branch_manager');
    }

    public function isAnalyst(): bool
    {
        return $this->role === 'analyst' || ($this->roleModel && $this->roleModel->name === 'analyst');
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer' || ($this->roleModel && $this->roleModel->name === 'viewer');
    }

    public function canManageBranches(): bool
    {
        return $this->hasPermission('branches.edit') || $this->isAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermission('users.edit') || $this->isAdmin();
    }

    public function canViewAllBranches(): bool
    {
        return $this->hasPermission('branches.view') || $this->isAdmin() || $this->isAnalyst();
    }

    // Legacy permission check for backwards compatibility
    private function hasLegacyPermission(string $permissionName): bool
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Branch managers have limited permissions
        if ($this->isBranchManager()) {
            $allowed = ['products.view', 'products.edit', 'sales_analytics.view', 'forecasting.view'];
            return in_array($permissionName, $allowed);
        }

        // Analysts can view most things
        if ($this->isAnalyst()) {
            return str_contains($permissionName, '.view');
        }

        // Viewers can only view
        if ($this->isViewer()) {
            $allowed = ['sales_analytics.view', 'customer_analytics.view', 'forecasting.view'];
            return in_array($permissionName, $allowed);
        }

        return false;


    }

    // Add to relationships section:
    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class)->latest();
    }

    // Add these helper methods at the end of the class:

    /**
     * Get user's preference for a notification type
     */
    public function getNotificationPreference(string $type, string $channel = 'email'): bool
    {
        $preference = $this->notificationPreferences()
            ->where('notification_type', $type)
            ->first();

        if (!$preference) {
            // Return default
            $defaults = NotificationPreference::defaults();
            return $defaults[$type][$channel] ?? true;
        }

        return $channel === 'email' ? $preference->email_enabled : $preference->in_app_enabled;
    }

    /**
     * Should this user receive this notification type?
     */
    public function shouldReceiveNotification(string $type): array
    {
        return [
            'email' => $this->getNotificationPreference($type, 'email'),
            'in_app' => $this->getNotificationPreference($type, 'in_app'),
        ];
    }

    /**
     * Get unread alert count
     */
    public function unreadAlertsCount(): int
    {
        return $this->alerts()->unread()->count();
    }

    /**
     * Which notification types should this role receive?
     */
    public function getAllowedNotificationTypes(): array
    {
        if ($this->isAdmin()) {
            return array_keys(NotificationPreference::types());
        }

        if ($this->isBranchManager()) {
            return [
                'low_stock',
                'out_of_stock',
                'overstock',
                'sales_drop',
                'high_value_transaction',
                'daily_summary',
                'import_completion',
            ];
        }

        if ($this->isAnalyst()) {
            return [
                'sales_drop',
                'forecast_deviation',
                'customer_segment_change',
                'daily_summary',
            ];
        }

        // Viewer
        return [
            'daily_summary',
        ];
    }

    /**
 * Check if user wants email notifications for a specific type
 */
    public function wantsEmailNotification(string $type): bool
    {
        if (!$this->notification_preferences) {
            return true; // Default to sending
        }

        $preferences = is_string($this->notification_preferences)
            ? json_decode($this->notification_preferences, true)
            : $this->notification_preferences;

        return $preferences["email_{$type}"] ?? true;
    }

    /**
 * Get unread alerts count
 */

}
