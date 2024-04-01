<?php

namespace Database\Seeders;

use App\Models\ProductOptionValueProductVariant;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $productVariant = ProductVariant::factory()->create([
            'product_id' => 1,
            'custom_price_int' => 1100,
            'custom_purchase_price_int' => 1000,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 1,
            'product_variant_id' => $productVariant->id,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 4,
            'product_variant_id' => $productVariant->id,
        ]);

        $productVariant = ProductVariant::factory()->create([
            'product_id' => 1,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 2,
            'product_variant_id' => $productVariant->id,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 3,
            'product_variant_id' => $productVariant->id,
        ]);


        $productVariant = ProductVariant::factory()->create([
            'product_id' => 1,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 1,
            'product_variant_id' => $productVariant->id,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 5,
            'product_variant_id' => $productVariant->id,
        ]);


        $productVariant = ProductVariant::factory()->create([
            'product_id' => 2,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 6,
            'product_variant_id' => $productVariant->id,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 7,
            'product_variant_id' => $productVariant->id,
        ]);

        $productVariant = ProductVariant::factory()->create([
            'product_id' => 2,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 6,
            'product_variant_id' => $productVariant->id,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 8,
            'product_variant_id' => $productVariant->id,
        ]);

        $productVariant = ProductVariant::factory()->create([
            'product_id' => 4,
        ]);

        $productOptionValueProductVariant = ProductOptionValueProductVariant::create([
            'product_option_value_id' => 9,
            'product_variant_id' => $productVariant->id,
        ]);
    }
}
