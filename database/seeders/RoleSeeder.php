<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create(["name"=> "Admin"]);
        \App\Models\Role::create(["name"=> "Store Admin"]);
        \App\Models\Role::create(["name"=> "Store Simple"]);
    }
}
