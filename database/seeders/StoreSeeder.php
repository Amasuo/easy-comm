<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\Store::factory()->create([
            'firstname' => 'ADMIN',
            'lastname' => 'ADMIN',
            'email' => 'admin@admin.com',
        ]);

        $adminRole = Role::where('name','admin')->first();
        $admin->assignRole($adminRole);
    }
}
