<?php

namespace Database\Seeders;

use App\Models\DeliveryDriver;
use Illuminate\Database\Seeder;

class DeliveryDriverSeeder extends Seeder
{
    public function run(): void
    {
        $deliveryDriver = DeliveryDriver::factory()->create([
            'firstname' => 'AramexDriver1',
            'lastname' => 'AramexDriver1',
            'delivery_company_id' => 1,
            'store_id' => 1,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'firstname' => 'AramexDriver2',
            'lastname' => 'AramexDriver2',
            'delivery_company_id' => 1,
            'store_id' => 4,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'firstname' => 'FirstDriver1',
            'lastname' => 'FirstDriver1',
            'delivery_company_id' => 2,
            'store_id' => 1,
        ]);

        $deliveryDriver = DeliveryDriver::factory()->create([
            'firstname' => 'FirstDriver2',
            'lastname' => 'FirstDriver2',
            'delivery_company_id' => 2,
            'store_id' => 4,
        ]);
    }
}
