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
        \App\Models\OrderStatus::create(["value"=> "Confirmed", 'is_filterable' => true, 'icon' => 'mdi-phone-check']);   // confirmé
        \App\Models\OrderStatus::create(["value"=> "Packed up"]);   // emballé
        \App\Models\OrderStatus::create(["value"=> "In delivery", 'is_filterable' => true, 'icon' => 'mdi-truck-delivery']); // en livraison
        \App\Models\OrderStatus::create(["value"=> "Delivered", 'is_filterable' => true, 'icon' => 'mdi-truck-check']);   // livré
        \App\Models\OrderStatus::create(["value"=> "Returning"]);   // en retour
        \App\Models\OrderStatus::create(["value"=> "Returned", 'is_filterable' => true, 'icon' => 'mdi-sync']);    // retourné
        \App\Models\OrderStatus::create(["value"=> "Canceled", 'is_filterable' => true, 'icon' => 'mdi-cancel']);    // annulé (mahazch tel) -> customer red flag
        \App\Models\OrderStatus::create(["value"=> "Payed", 'is_filterable' => true, 'icon' => 'mdi-cash-check']);       // payé
    }
}
