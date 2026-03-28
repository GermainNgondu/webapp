<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Detail {
    public function __construct(
        public string $label,
        public ?string $section = null,
        public ?string $component = null,
        public int $order = 0,
    ) {}
}