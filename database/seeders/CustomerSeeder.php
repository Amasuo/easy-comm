<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $numCustomers = 50;

        for ($i = 0; $i < $numCustomers; $i++) {
            Customer::factory()->create([
                'store_id' => fake()->randomElement([1 ,4]),
            ]);
        }
    }
}
