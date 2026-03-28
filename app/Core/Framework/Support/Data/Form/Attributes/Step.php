<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Step
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $permission = null,
        public ?string $action = null
    ) {}
}