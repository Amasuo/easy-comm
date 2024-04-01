<?php

namespace Database\Seeders;

use App\Models\DeliveryDriver;
use Illuminate\Database\Seeder;

class DeliveryDriverSeeder extends Seeder
{
    public function run(): void
    {
        $deliveryDriver = DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 1,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'delivery_company_id' => 1,
            'store_id' => 4,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 1,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'delivery_company_id' => 2,
            'store_id' => 4,
        ]);
    }
}
