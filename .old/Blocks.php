<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Blocks
{
    public function __construct(
        public array $allowed = [],
    ) {}
}
