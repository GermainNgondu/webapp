<?php

namespace App\Core\Framework\Support\Data\Insight\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Activity
{
    public function __construct(
        public string $label,
        public string $action,
        public int $limit = 5,
        public ?string $icon = 'clock',
        public int $colSpan = 6,
        public ?string $color = 'slate',
    ) {}
}