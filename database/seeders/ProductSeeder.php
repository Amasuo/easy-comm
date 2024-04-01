<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::factory()->create([
            'store_id' => 1,
            'product_gender_id' => 1,
            'name' => 'Product1',
            'price_int' => 1000,
            'purchase_price_int' => 900,
        ]);

        $product = Product::factory()->create([
            'store_id' => 1,
            'product_gender_id' => 2,
            'name' => 'Product2',
            'price_int' => 800,
            'purchase_price_int' => 750,
        ]);

        $product = Product::factory()->create([
            'store_id' => 4,
            'product_gender_id' => 1,
            'name' => 'Product3',
            'price_int' => 1500,
            'purchase_price_int' => 1300,
        ]);

        $product = Product::factory()->create([
            'store_id' => 4,
            'product_gender_id' => 2,
            'name' => 'Product4',
            'price_int' => 400,
            'purchase_price_int' => 380,
        ]);
    }
}
