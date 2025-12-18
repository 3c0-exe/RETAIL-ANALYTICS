<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Add SKU if missing
            if (!Schema::hasColumn('transaction_items', 'sku')) {
                $table->string('sku')->after('product_id');
            }

            // Add Product Name if missing (Seeder likely needs this too)
            if (!Schema::hasColumn('transaction_items', 'product_name')) {
                $table->string('product_name')->after('sku');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['sku', 'product_name']);
        });
    }
};
