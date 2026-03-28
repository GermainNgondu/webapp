<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DefaultSort
{
    public function __construct(
        public string $column,
        public string $direction = 'desc'
    ) {
    }
}