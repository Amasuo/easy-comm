<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1
        $store = Store::factory()->create([
            'name' => 'Dabchi',
        ]);

        // 2
        $store = Store::factory()->create([
            'name' => 'Dabchek',
            'parent_id' => 1
        ]);

        // 3
        $store = Store::factory()->create([
            'name' => 'Dbachna',
            'parent_id' => 1
        ]);

        // 4
        $store = Store::factory()->create([
            'name' => '7weyji',
        ]);

        // 5
        $store = Store::factory()->create([
            'name' => '7weyjek',
            'parent_id' => 4
        ]);

        // 6
        $store = Store::factory()->create([
            'name' => '9ach',
        ]);

        // 7
        $store = Store::factory()->create([
            'name' => 'Sel3a',
        ]);

        // 8
        $store = Store::factory()->create([
            'name' => '3atar',
        ]);

        // 9
        $store = Store::factory()->create([
            'name' => 'DS',
        ]);
    }
}
