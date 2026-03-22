<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Accordion
{
    public function __construct(
        public string $name,
        public ?string $icon = null,
        public bool $active = false,
    ) {}
}