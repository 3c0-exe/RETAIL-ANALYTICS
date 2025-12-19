<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            // $table->timestamp('timestamp');
            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2); // This should exist
            $table->string('payment_method');
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->enum('status', ['completed', 'refunded', 'void'])->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
