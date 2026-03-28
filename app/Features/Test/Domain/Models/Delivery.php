<?php

namespace App\Features\Test\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model {
    protected $fillable = ['lat', 'lng', 'status'];
    // Simulation de mouvement pour le real-time (pour tes tests)
    public function updateLocation($lat, $lng) {
        $this->update(['lat' => $lat, 'lng' => $lng]);
    }
}