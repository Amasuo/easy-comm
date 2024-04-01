<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'firstname' => fake()->name(),
            'lastname' => fake()->name(),
            'phone' => fake()->randomNumber(8),
            'state' => fake()->randomElement(['Tunis', 'Monastir', 'Sousse', 'Nabeul', 'Gafsa', 'Beja']),
            'city' => fake()->city(),
            'street' => fake()->streetAddress(),
        ];
    }
}
