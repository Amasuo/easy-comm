<?php

namespace Database\Seeders;

use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Database\Seeder;

class ProductOptionSeeder extends Seeder
{
    public function run(): void
    {
        $productOption = ProductOption::factory()->create([
            'product_id' => 1,
            'name' => 'Color',
        ]);

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'Red';
        $productOptionValue->save();

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'Blue';
        $productOptionValue->save();


        $productOption = ProductOption::factory()->create([
            'product_id' => 1,
            'name' => 'Size',
        ]);

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'S';
        $productOptionValue->save();

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'M';
        $productOptionValue->save();

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'L';
        $productOptionValue->save();


        $productOption = ProductOption::factory()->create([
            'product_id' => 2,
            'name' => 'Color',
        ]);

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'White';
        $productOptionValue->save();


        $productOption = ProductOption::factory()->create([
            'product_id' => 2,
            'name' => 'Size',
        ]);

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'M';
        $productOptionValue->save();

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'XL';
        $productOptionValue->save();


        $productOption = ProductOption::factory()->create([
            'product_id' => 4,
            'name' => 'Size',
        ]);

        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $productOption->id;
        $productOptionValue->value = 'S';
        $productOptionValue->save();
    }
}
