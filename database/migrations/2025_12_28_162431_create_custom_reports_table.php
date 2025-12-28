<?php

// CREATE THIS FILE: database/migrations/2024_XX_XX_create_custom_reports_table.php
// Run: php artisan make:migration create_custom_reports_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Report name
            $table->string('type'); // 'sales', 'customers', 'products'
            $table->json('config'); // Store all configuration
            // Config structure:
            // {
            //   "metrics": ["total_sales", "transaction_count"],
            //   "dimensions": ["branch", "category"],
            //   "date_range": {"start": "2024-01-01", "end": "2024-12-31"},
            //   "chart_type": "bar",
            //   "filters": {...}
            // }
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_reports');
    }
};
