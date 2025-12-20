<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Add SKU if missing - MAKE IT NULLABLE
            if (!Schema::hasColumn('transaction_items', 'sku')) {
                $table->string('sku')->nullable()->after('product_id');
            }

            // Add Product Name if missing
            if (!Schema::hasColumn('transaction_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('sku');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_items', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('transaction_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }
};
