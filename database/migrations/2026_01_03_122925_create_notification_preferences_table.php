<?php

// database/migrations/2026_01_03_125800_create_notification_preferences_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists (clean slate)
        Schema::dropIfExists('notification_preferences');

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type');
            $table->boolean('email_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'notification_type']);
        });

        // Add metadata column to alerts if not exists
        if (Schema::hasTable('alerts') && !Schema::hasColumn('alerts', 'metadata')) {
            Schema::table('alerts', function (Blueprint $table) {
                $table->json('metadata')->nullable()->after('related_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');

        if (Schema::hasColumn('alerts', 'metadata')) {
            Schema::table('alerts', function (Blueprint $table) {
                $table->dropColumn('metadata');
            });
        }
    }
};
