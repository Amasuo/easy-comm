<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductOptionValueProductVariant;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $colors = ['rouge', 'vert', 'bleu'];
        $sizes = ['S', 'M', 'L'];
        
        $numProducts = 500;

        for ($i = 0; $i < $numProducts; $i++) {
            $product = Product::factory()->create();
            $productVariantOne = ProductVariant::factory()->create([
                'product_id' => $product->id,
            ]);
            $customPriceInt = $product->price_int + (fake()->numberBetween(1, 3) * 100);
            $productVariantTwo = ProductVariant::factory()->create([
                'product_id' => $product->id,
                'custom_price_int' => $customPriceInt,
                'custom_purchase_price_int' => $customPriceInt - (fake()->numberBetween(2, 4) * 100),
            ]);
            $colorOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Couleur',
            ]);
            foreach($colors as $color) {
                ProductOptionValue::create([
                    'product_option_id' => $colorOption->id,
                    'value' => $color,
                ]);
            }
            $colorOptionValueOne = $colorOption->product_option_values()->inRandomOrder()->first();
            ProductOptionValueProductVariant::create([
                'product_variant_id' => $productVariantOne->id,
                'product_option_value_id' => $colorOptionValueOne->id,
            ]);
            $colorOptionValueTwo = $colorOption->product_option_values()->where('id', '!=', $colorOptionValueOne->id)->inRandomOrder()->first();
            ProductOptionValueProductVariant::create([
                'product_variant_id' => $productVariantTwo->id,
                'product_option_value_id' => $colorOptionValueTwo->id,
            ]);
            

            $sizeOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Taille',
            ]);
            foreach($sizes as $size) {
                ProductOptionValue::create([
                    'product_option_id' => $sizeOption->id,
                    'value' => $size,
                ]);
            }
            $sizeOptionValueOne = $sizeOption->product_option_values()->inRandomOrder()->first();
            ProductOptionValueProductVariant::create([
                'product_variant_id' => $productVariantOne->id,
                'product_option_value_id' => $sizeOptionValueOne->id,
            ]);
            $colorOptionValueTwo = $colorOption->product_option_values()->where('id', '!=', $sizeOptionValueOne->id)->inRandomOrder()->first();
            ProductOptionValueProductVariant::create([
                'product_variant_id' => $productVariantTwo->id,
                'product_option_value_id' => $colorOptionValueTwo->id,
            ]);
        }
    }
}
