<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Grid {
    public function __construct(
        public string $position, // 'title', 'subtitle', 'description', 'badge', 'image', 'footer'
        public ?string $icon = null,
        public ?string $component = null,
    ) {}
}