<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Step
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $permission = null,
    ) {}
}