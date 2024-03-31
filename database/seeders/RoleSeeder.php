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
        $adminRole = \App\Models\Role::create(["name"=> "Admin"]);
        $storeAdminRole = \App\Models\Role::create(["name"=> "Store Admin"]);
        $storeSimpleRole = \App\Models\Role::create(["name"=> "Store Simple"]);
    }
}
