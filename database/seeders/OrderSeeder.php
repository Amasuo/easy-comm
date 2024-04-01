<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProductVariant;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Customer::all() as $customer) {
            $order = Order::factory()->create([
                'store_id' => $customer->store_id,
                'customer_id' => $customer->id,
                'delivery_company_id' => fake()->numberBetween(1, 2),
                'delivery_driver_id' => fake()->numberBetween(1, 4),
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'phone' => $customer->phone,
                'state' => $customer->state,
                'city' => $customer->city,
                'street' => $customer->street,
            ]);

            $orderProductVariant = OrderProductVariant::create([
                'order_id' => $order->id,
                'product_variant_id' => fake()->numberBetween(1, 3),
                'count' => fake()->numberBetween(1, 5),
            ]);

            $orderProductVariant = OrderProductVariant::create([
                'order_id' => $order->id,
                'product_variant_id' => fake()->numberBetween(4, 6),
                'count' => fake()->numberBetween(1, 5),
            ]);
        }
    }
}
