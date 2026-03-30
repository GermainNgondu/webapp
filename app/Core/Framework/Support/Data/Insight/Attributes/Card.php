<?php

namespace App\Core\Framework\Support\Data\Insight\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Card
{
    public function __construct(
        public string $label,
        public ?string $icon = null,
        public ?string $description = null,
        public ?string $color = 'zinc',
        public int $colSpan = 1,
        public ?string $action = null,
    ) {}
}