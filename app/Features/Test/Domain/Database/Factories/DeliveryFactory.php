<?php

namespace App\Features\Test\Domain\Database\Factories;

use App\Features\Test\Domain\Models\Delivery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition(): array
    {
        return [
            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
            'driver_name' => $this->faker->name(),
            // Coordonnées autour de Paris (Lat: 48.85, Lng: 2.35)
            'lat' => $this->faker->latitude(48.81, 48.90),
            'lng' => $this->faker->longitude(2.25, 2.45),
            'status' => $this->faker->randomElement(['pending', 'en_route', 'delivered']),
            'created_at' => now(),
        ];
    }
}