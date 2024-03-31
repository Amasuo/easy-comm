<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::factory()->create([
            'name' => 'Dabchi',
        ]);

        $store = Store::factory()->create([
            'name' => 'Dabchek',
            'parent_id' => 1
        ]);

        $store = Store::factory()->create([
            'name' => 'Dbachna',
            'parent_id' => 1
        ]);
    }
}
