<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        // $table->decimal('total_amount', 12, 2)->after('discount_amount');
        // ✅ CORRECT (Just add the column without forcing a specific position)
        $table->decimal('total_amount', 12, 2);
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('total_amount');
    });
}
};
