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
        Schema::table('users', function (Blueprint $table) {
            $table->string('street')->nullable()->after('phone');
            $table->string('state')->nullable()->after('street');
            $table->string('city')->nullable()->after('state');
            $table->foreignId('language_id')->nullable()->after('city')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('state');
            $table->dropColumn('city');
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
        });
    }
};
