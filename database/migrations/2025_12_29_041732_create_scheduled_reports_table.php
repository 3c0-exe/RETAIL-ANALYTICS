<?php

// CREATE THIS FILE: database/migrations/2024_XX_XX_create_scheduled_reports_table.php
// Run: php artisan make:migration create_scheduled_reports_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('report_type'); // 'sales', 'customers', 'custom'
            $table->foreignId('custom_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->string('day_of_week')->nullable(); // For weekly: 'monday', 'tuesday', etc.
            $table->integer('day_of_month')->nullable(); // For monthly: 1-31
            $table->time('time')->default('08:00:00'); // Time to send
            $table->json('recipients'); // Array of email addresses
            $table->enum('format', ['pdf', 'excel', 'csv'])->default('pdf');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();
        });

        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_report_id')->constrained()->onDelete('cascade');
            $table->timestamp('sent_at');
            $table->string('status'); // 'success', 'failed'
            $table->text('error_message')->nullable();
            $table->integer('recipient_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_logs');
        Schema::dropIfExists('scheduled_reports');
    }
};
