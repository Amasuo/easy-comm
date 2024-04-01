<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DeliveryDriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstname(),
            'lastname' => fake()->lastname(),
            'phone' => fake()->randomNumber(8),
        ];
    }
}
