<?php

namespace App\Core\Framework\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class NavigationItemData extends Data
{
    public function __construct(
        public string $label,
        public ?string $route = null,
        public ?string $icon = null,
        public ?string $badge = null,
        public int $order = 100,
        /** @var DataCollection<NavigationItemData>|null */
        public ?DataCollection $children = null,
        public array $meta = [],
    ) {}
}