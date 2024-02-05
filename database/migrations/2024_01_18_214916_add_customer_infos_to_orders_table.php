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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('firstname')->after('delivery_driver_id');
            $table->string('lastname')->after('firstname');
            $table->string('phone')->after('lastname');
            $table->string('state')->after('phone');
            $table->string('city')->after('state');
            $table->string('street')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('phone');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropColumn('street');
        });
    }
};
