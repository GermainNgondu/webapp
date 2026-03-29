<?php

namespace App\Features\Test\Domain\Data\Delivery;

use App\Features\Test\Domain\Models\Delivery;
use Spatie\LaravelData\Data;

class DeliveryInsightData extends Data
{
    public function __construct(
        public string $id,
        public string $tracking_number,
        public float $lat,
        public float $lng,
        public string $status,
    ){}

    public static function getStats(): array { 
        return [
            'active_deliveries' => Delivery::where('status', 'en_route')->count(),
            'avg_delivery_time' => '24 min',
        ];
    }
}