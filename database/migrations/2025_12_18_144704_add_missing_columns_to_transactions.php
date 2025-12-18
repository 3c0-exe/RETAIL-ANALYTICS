<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add tax_amount if it doesn't exist
            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('subtotal');
            }

            // Add discount_amount if it doesn't exist
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('tax_amount');
            }

            // Check for 'total' vs 'total_amount' mismatch
            // The Seeder seems to look for 'total', but your plan said 'total_amount'
            // We will ensure 'total_amount' exists as per your blueprint.
            if (!Schema::hasColumn('transactions', 'total_amount') && !Schema::hasColumn('transactions', 'total')) {
                 $table->decimal('total_amount', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'discount_amount']);
        });
    }
};
