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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->default(DB::raw('(UUID())'));
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('delivery_company_id')->nullable()->constrained('delivery_companies')->onDelete('cascade');
            $table->foreignId('delivery_driver_id')->nullable()->constrained('delivery_drivers')->onDelete('cascade');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone');
            $table->string('state');
            $table->string('city');
            $table->string('street')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
