<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class KanbanGroup
{
    public function __construct(
        public array $options = [],
    ) {
    }
}