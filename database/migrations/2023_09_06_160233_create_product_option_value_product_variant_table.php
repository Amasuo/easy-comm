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
        Schema::create('product_option_value_product_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('product_option_value_id')->nullable()->constrained('product_option_values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_product_variant');
    }
};
