<?php

// database/seeders/NotificationPreferenceSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NotificationPreference;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = NotificationPreference::defaults();

        // Create default preferences for all existing users
        User::all()->each(function ($user) use ($defaults) {
            $allowedTypes = $user->getAllowedNotificationTypes();

            foreach ($allowedTypes as $type) {
                NotificationPreference::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'notification_type' => $type,
                    ],
                    [
                        'email_enabled' => $defaults[$type]['email'] ?? true,
                        'in_app_enabled' => $defaults[$type]['in_app'] ?? true,
                    ]
                );
            }
        });

        $this->command->info('Default notification preferences created for all users!');
    }
}
