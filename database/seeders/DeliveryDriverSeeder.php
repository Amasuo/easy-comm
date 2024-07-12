<?php

namespace Database\Seeders;

use App\Models\DeliveryDriver;
use Illuminate\Database\Seeder;

class DeliveryDriverSeeder extends Seeder
{
    public function run(): void
    {
        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 1,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 4,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 6,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 7,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 8,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 9,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 1,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 4,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 6,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 7,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 8,
        ]);

        DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 9,
        ]);
    }
}
