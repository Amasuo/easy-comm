<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $customer = Customer::factory()->create([
                'store_id' => 1
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            $customer = Customer::factory()->create([
                'store_id' => 4
            ]);
        }
    }
}
