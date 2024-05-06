<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\OrderStatus::create(["value"=> "Processing"]);  // crée
        \App\Models\OrderStatus::create(["value"=> "Confirmed"]);   // confirmé
        \App\Models\OrderStatus::create(["value"=> "Packed up"]);   // emballé
        \App\Models\OrderStatus::create(["value"=> "In delivery"]); // en livraison
        \App\Models\OrderStatus::create(["value"=> "Delivered"]);   // livré & payé
        \App\Models\OrderStatus::create(["value"=> "Returning"]);   // en retour
        \App\Models\OrderStatus::create(["value"=> "Returned"]);    // retourné
        \App\Models\OrderStatus::create(["value"=> "Canceled"]);    // annulé (mahazch tel) -> customer red flag
        \App\Models\OrderStatus::create(["value"=> "Payed"]);       // payé
    }
}
