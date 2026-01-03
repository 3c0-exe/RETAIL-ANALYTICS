<?php

// app/Console/Commands/TestNotificationEmail.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericAlert;
use App\Models\Alert;
use App\Models\User;

class TestNotificationEmail extends Command
{
    protected $signature = 'test:notification-email {email}';
    protected $description = 'Send a test notification email to verify email system';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::first();

        // Create a test alert
        $alert = Alert::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => '✅ Test Email Notification',
            'message' => 'This is a test email from Prisma Retail Analytics. If you receive this, your notification email system is working perfectly!',
            'severity' => 'info',
            'is_read' => false,
            'metadata' => [
                'test_time' => now()->format('Y-m-d H:i:s'),
                'sent_to' => $email,
                'system_status' => 'operational'
            ]
        ]);

        try {
            // Send email immediately (not queued)
            Mail::to($email)->send(new GenericAlert($alert));

            $this->info("✅ Test notification email sent successfully to: {$email}");
            $this->info("📧 Check your inbox (and spam folder) at: {$email}");
            $this->info("🔔 Alert ID: {$alert->id} created");

            // Clean up test alert
            $this->newLine();
            if ($this->confirm('Delete test alert from database?', true)) {
                $alert->delete();
                $this->info("🗑️  Test alert deleted");
            }

        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $alert->delete();
        }
    }
}
