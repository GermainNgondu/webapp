<?php

namespace App\Features\Test\Domain\Database\Seeders;

use App\Features\Test\Domain\Models\Delivery;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        // Création de 30 livraisons pour peupler la carte
        Delivery::factory()->count(30)->create();

        // Ajout d'une livraison spécifique "En route" pour vérification
        Delivery::factory()->create([
            'driver_name' => 'Jean Express',
            'status' => 'en_route',
            'lat' => 48.8566, // Centre de Paris
            'lng' => 2.3522,
        ]);
    }
}