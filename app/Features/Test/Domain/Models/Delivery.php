<?php

namespace App\Features\Test\Domain\Models;

use App\Features\Test\Domain\Database\Factories\DeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model 
{
    use HasFactory;
    protected $fillable = ['tracking_numbe','driver_name','lat', 'lng', 'status'];
    // Simulation de mouvement pour le real-time (pour tes tests)
    public function updateLocation($lat, $lng) {
        $this->update(['lat' => $lat, 'lng' => $lng]);
    }

    /**
     * Crée une nouvelle instance de la factory pour le modèle.
     */
    protected static function newFactory()
    {
        return DeliveryFactory::new();
    }
}