<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CalendarDate {
    public function __construct(
        public string $type = 'start', // 'start', 'end' ou 'label'
    ) {}
}