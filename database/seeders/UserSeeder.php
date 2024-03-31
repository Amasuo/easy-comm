<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleStoreUser;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::factory()->create([
            'firstname' => 'ADMIN',
            'lastname' => 'ADMIN',
            'email' => 'admin@admin.com',
            'phone' => '12345678',
        ]);
        $admin->assignAdminRole();

        $storeAdmin = \App\Models\User::factory()->create([
            'firstname' => 'StoreAdmin',
            'lastname' => 'StoreAdmin',
            'email' => 'store@admin.com',
            'phone' => '12345677',
        ]);

        $storeAdmin->addStore(Store::findOrFail(1), isAdmin: true);
        $storeAdmin->addStore(Store::findOrFail(2), isAdmin: true);
        $storeAdmin->addStore(Store::findOrFail(3), isAdmin: true);

        $storeSimple = \App\Models\User::factory()->create([
            'firstname' => 'StoreSimple',
            'lastname' => 'StoreSimple',
            'email' => 'store@simple.com',
            'phone' => '12345688',
        ]);

        $storeSimple->addStore(Store::findOrFail(1), isAdmin: false);
        $storeSimple->addStore(Store::findOrFail(2), isAdmin: false);

        $storeSimple2 = \App\Models\User::factory()->create([
            'firstname' => 'StoreSimple2',
            'lastname' => 'StoreSimple2',
            'email' => 'store@simple2.com',
            'phone' => '12345699',
        ]);

        $storeSimple2->addStore(Store::findOrFail(3), isAdmin: false);
    }
}
