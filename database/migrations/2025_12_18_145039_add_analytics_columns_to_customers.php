<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add total_spend if missing
            if (!Schema::hasColumn('customers', 'total_spend')) {
                $table->decimal('total_spend', 12, 2)->default(0)->after('email');
            }

            // Add visit_count if missing
            if (!Schema::hasColumn('customers', 'visit_count')) {
                $table->integer('visit_count')->default(0)->after('total_spend');
            }

            // Add last_purchase_at if missing
            if (!Schema::hasColumn('customers', 'last_purchase_at')) {
                $table->timestamp('last_purchase_at')->nullable()->after('visit_count');
            }

            // Add segment if missing
            if (!Schema::hasColumn('customers', 'segment')) {
                $table->string('segment')->default('new')->after('last_purchase_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['total_spend', 'visit_count', 'last_purchase_at', 'segment']);
        });
    }
};
