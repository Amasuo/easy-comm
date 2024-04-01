<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'stock' => fake()->numberBetween(50, 99),
        ];
    }
}
