<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->decimal('branch_price', 10, 2)->nullable(); // Optional: override product price per branch
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Prevent duplicate product per branch
            $table->unique(['branch_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_products');
    }
};
