<?php

namespace Database\Seeders;

use App\Models\ProductGender;
use Illuminate\Database\Seeder;

class ProductGenderSeeder extends Seeder
{
    public function run(): void
    {
        $productGender = ProductGender::factory()->create([
            'name' => 'Male',
        ]);

        $productGender = ProductGender::factory()->create([
            'name' => 'Female',
        ]);

        $productGender = ProductGender::factory()->create([
            'name' => 'n/a',
        ]);
    }
}
