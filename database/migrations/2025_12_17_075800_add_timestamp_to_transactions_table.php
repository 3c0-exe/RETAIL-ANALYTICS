<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // THIS IS THE LINE YOU ARE ADDING:
            // It adds a column named 'timestamp' that can be empty (nullable)
            // and places it right after the 'branch_id' column for neatness.
            $table->timestamp('timestamp')->nullable()->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // This removes the column if you ever roll back the migration
            $table->dropColumn('timestamp');
        });
    }
};
