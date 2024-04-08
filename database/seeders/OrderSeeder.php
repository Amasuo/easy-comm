<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DeliveryDriver;
use App\Models\Order;
use App\Models\OrderProductVariant;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $numOrders = 1500;

        for ($i = 0; $i < $numOrders; $i++) {
            $customer = Customer::inRandomOrder()->first();
            $order = Order::factory()->create([
                'store_id' => $customer->store_id,
                'customer_id' => $customer->id,
                'delivery_company_id' => fake()->numberBetween(1, 2),
                'delivery_driver_id' => DeliveryDriver::where('store_id', $customer->store_id)->inRandomOrder()->first()->id,
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'phone' => $customer->phone,
                'state' => $customer->state,
                'city' => $customer->city,
                'street' => $customer->street,
            ]);

            $orderProductVariant = OrderProductVariant::create([
                'order_id' => $order->id,
                'product_variant_id' => ProductVariant::whereHas('product', function ($query) use ($order) {
                    return $query->where('store_id', $order->store_id);
                })->inRandomOrder()->first()->id,
                'count' => fake()->numberBetween(1, 3),
            ]);

            OrderProductVariant::create([
                'order_id' => $order->id,
                'product_variant_id' => ProductVariant::where('id', '!=', $orderProductVariant->product_variant_id)
                    ->whereHas('product', function ($query) use ($order) {
                        return $query->where('store_id', $order->store_id);
                    })->inRandomOrder()->first()->id,
                'count' => fake()->numberBetween(1, 3),
            ]);
        }
    }
}
