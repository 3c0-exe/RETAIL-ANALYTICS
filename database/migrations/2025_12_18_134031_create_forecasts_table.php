<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('category')->nullable();
            $table->date('forecast_date');
            $table->decimal('predicted_sales', 12, 2);
            $table->decimal('confidence_lower', 12, 2);
            $table->decimal('confidence_upper', 12, 2);
            $table->string('model_version')->default('exponential_smoothing_v1');
            $table->json('metadata')->nullable(); // Store alpha, beta, gamma values
            $table->timestamps();

            $table->index(['branch_id', 'forecast_date']);
            $table->index(['product_id', 'forecast_date']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
