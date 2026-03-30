<?php

namespace App\Core\Framework\Support\Data\Insight\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Metric
{
    public function __construct(
        public string $label,
        public string $action,
        public ?string $icon = null,
        public ?string $description = null,
        public ?string $color = 'zinc',
        public ?string $format = 'number', // 'number', 'currency', 'bytes', 'duration'
        public ?string $suffix = null,
        public int $colSpan = 3,
        
    ) {}
}