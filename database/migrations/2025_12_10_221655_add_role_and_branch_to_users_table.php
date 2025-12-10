<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'branch_manager', 'analyst', 'viewer'])
                  ->default('viewer')
                  ->after('email');

            $table->foreignId('branch_id')
                  ->nullable()
                  ->after('role')
                  ->constrained()
                  ->nullOnDelete();

            $table->string('avatar')->nullable()->after('password');
            $table->enum('theme', ['light', 'dark'])->default('light')->after('avatar');
            $table->string('two_factor_secret')->nullable()->after('theme');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'branch_id', 'avatar', 'theme', 'two_factor_secret']);
        });
    }
};
