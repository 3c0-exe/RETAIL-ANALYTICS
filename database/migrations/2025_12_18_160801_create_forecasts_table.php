<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('forecasts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('branch_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('category')->nullable();
        $table->date('forecast_date');
        $table->decimal('predicted_sales', 12, 2);
        $table->decimal('confidence_lower', 12, 2);
        $table->decimal('confidence_upper', 12, 2);
        $table->string('model_version')->default('exponential_smoothing_v1');
        $table->json('metadata')->nullable();
        $table->timestamps();

        $table->index(['branch_id', 'forecast_date']);
        $table->index(['product_id', 'forecast_date']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
