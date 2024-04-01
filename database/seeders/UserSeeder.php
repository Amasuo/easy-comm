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
        ]);
        $admin->assignAdminRole();

        $storeAdmin = \App\Models\User::factory()->create([
            'firstname' => 'dabchiAdmin',
            'lastname' => 'dabchiAdmin',
            'email' => 'dabchi@admin.com',
        ]);

        $storeAdmin->addStore(Store::findOrFail(1), isAdmin: true);

        $storeSimple = \App\Models\User::factory()->create([
            'firstname' => 'dabchiSimple',
            'lastname' => 'dabchiSimple',
            'email' => 'dabchi@simple.com',
        ]);

        $storeSimple->addStore(Store::findOrFail(1), isAdmin: false);
        $storeSimple->addStore(Store::findOrFail(2), isAdmin: false);

        $storeSimple2 = \App\Models\User::factory()->create([
            'firstname' => 'dabchiSimple2',
            'lastname' => 'dabchiSimple2',
            'email' => 'dabchi@simple2.com',
        ]);

        $storeSimple2->addStore(Store::findOrFail(3), isAdmin: false);


        $storeAdmin = \App\Models\User::factory()->create([
            'firstname' => '7weyjiAdmin',
            'lastname' => '7weyjiAdmin',
            'email' => '7weyji@admin.com',
        ]);

        $storeAdmin->addStore(Store::findOrFail(4), isAdmin: true);

        $storeSimple = \App\Models\User::factory()->create([
            'firstname' => '7weyjiSimple',
            'lastname' => '7weyjiSimple',
            'email' => '7weyji@simple.com',
        ]);

        $storeSimple->addStore(Store::findOrFail(4), isAdmin: false);
        $storeSimple->addStore(Store::findOrFail(5), isAdmin: false);

        $storeSimple2 = \App\Models\User::factory()->create([
            'firstname' => '7weyjiSimple2',
            'lastname' => '7weyjiSimple2',
            'email' => '7weyji@simple2.com',
        ]);

        $storeSimple2->addStore(Store::findOrFail(5), isAdmin: false);
    }
}
