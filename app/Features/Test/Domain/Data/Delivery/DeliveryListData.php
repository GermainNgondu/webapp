<?php

namespace App\Features\Test\Domain\Data\Delivery;

use App\Core\Framework\Support\Data\View\Attributes\{MapLocation};
use Spatie\LaravelData\Data;

class DeliveryListData extends Data {
    public function __construct(
        public string $id,
        #[MapLocation(type: 'label')]
        public string $tracking_number,
        #[MapLocation(type: 'title')]
        public string $driver_name,
        #[MapLocation(type: 'lat')]
        public float $lat,
        #[MapLocation(type: 'lng')]
        public float $lng,

        public string $status,
    ){}
}