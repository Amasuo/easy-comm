<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $priceInt = fake()->numberBetween(6, 30) * 100;   // 60dt -> 300dt
        $purchasePriceInt = $priceInt - (fake()->numberBetween(2, 4) * 100);

        return [
            'store_id' => fake()->numberBetween(1, Store::count()),
            'name' => fake()->randomElement(['Veste', 'Pantalon', 'Robe', 'Jupe', 'Chemise', 'Gilet', 'Pull', 'Blouson']),
            'product_gender_id' => fake()->numberBetween(1, 2),
            'price_int' => $priceInt,
            'purchase_price_int' => $purchasePriceInt,
        ];
    }
}
