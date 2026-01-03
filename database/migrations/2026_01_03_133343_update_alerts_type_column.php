<?php

// database/migrations/2026_01_03_133909_update_alerts_type_column.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change type column from ENUM to VARCHAR
        DB::statement('ALTER TABLE `alerts` MODIFY COLUMN `type` VARCHAR(255) NOT NULL');

        // Add read_at if not exists
        if (!Schema::hasColumn('alerts', 'read_at')) {
            Schema::table('alerts', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable()->after('is_read');
            });
        }
    }

    public function down(): void
    {
        // Revert to original ENUM (adjust based on your original values)
        DB::statement("ALTER TABLE `alerts` MODIFY COLUMN `type` ENUM('low_stock', 'sales_drop', 'forecast_deviation', 'system') NOT NULL");

        if (Schema::hasColumn('alerts', 'read_at')) {
            Schema::table('alerts', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }
    }
};
