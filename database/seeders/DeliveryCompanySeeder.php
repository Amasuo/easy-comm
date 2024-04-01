<?php

namespace Database\Seeders;

use App\Models\DeliveryCompany;
use Illuminate\Database\Seeder;

class DeliveryCompanySeeder extends Seeder
{
    public function run(): void
    {
        $deliveryCompany = DeliveryCompany::factory()->create([
            'name' => 'Aramex',
        ]);

        $deliveryCompany = DeliveryCompany::factory()->create([
            'name' => 'First Delivery',
        ]);
    }
}
