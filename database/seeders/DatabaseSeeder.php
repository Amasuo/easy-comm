<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DeliveryCompanySeeder::class);
        $this->call(DeliveryDriverSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ProductGenderSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
