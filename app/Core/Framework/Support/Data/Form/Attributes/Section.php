<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Section
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $icon = null,
    ) {}
}