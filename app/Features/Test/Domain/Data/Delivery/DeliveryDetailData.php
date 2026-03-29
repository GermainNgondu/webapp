<?php

namespace App\Features\Test\Domain\Data\Delivery;

use App\Core\Framework\Support\Data\View\Attributes\Detail;
use Spatie\LaravelData\Data;

class DeliveryDetailData extends Data
{
    public function __construct(
        public string $id,
        #[Detail(label: 'Chauffeur', section: 'Logistique')]
        public string $driver_name,
        
        #[Detail(label: 'Position Actuelle', section: 'Tracking', component: 'viewers.map-viewer')]
        public string $url
    ){}
}