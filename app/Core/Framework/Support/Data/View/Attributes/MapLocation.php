<?php
namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MapLocation {
    public function __construct(
        public string $type = 'lat', // 'lat', 'lng' ou 'address'
    ) {}
}