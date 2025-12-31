<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default settings
        DB::table('admin_settings')->insert([
            ['key' => 'company_name', 'value' => 'Retail Analytics', 'type' => 'text', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_logo', 'value' => null, 'type' => 'text', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_timezone', 'value' => 'Asia/Manila', 'type' => 'text', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_currency', 'value' => 'PHP', 'type' => 'text', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_tax_rate', 'value' => '12', 'type' => 'number', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_from_name', 'value' => 'Retail Analytics', 'type' => 'text', 'group' => 'email', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_from_address', 'value' => 'noreply@example.com', 'type' => 'text', 'group' => 'email', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_message', 'value' => 'System is under maintenance. Please check back soon.', 'type' => 'text', 'group' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'enable_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'system', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
