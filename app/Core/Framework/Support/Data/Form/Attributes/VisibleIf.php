<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class VisibleIf
{
    public function __construct(
        public string $field,
        public mixed $value,
        public string $operator = '=' // =, !=, >, <, in, not_in
    ) {}
}