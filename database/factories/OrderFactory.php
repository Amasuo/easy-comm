<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_status_id' => fake()->numberBetween(1, 6),
            'delivery_company_id' => fake()->numberBetween(1, 2),
            'firstname' => fake()->firstname(),
            'lastname' => fake()->lastname(),
            'phone' => fake()->randomNumber(8),
            'state' => fake()->randomElement(['Tunis', 'Monastir', 'Sousse', 'Nabeul', 'Gafsa', 'Beja']),
            'city' => fake()->city(),
            'street' => fake()->streetAddress(),
            'delivered_at' => now()->addDays(fake()->numberBetween(1, 3))
        ];
    }
}
