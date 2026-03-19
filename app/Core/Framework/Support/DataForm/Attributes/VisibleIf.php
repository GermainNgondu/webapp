<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class VisibleIf
{
    public function __construct(
        public string $field,
        public mixed $value
    ) {}
}