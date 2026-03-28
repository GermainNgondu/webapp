<?php

namespace App\Core\Framework\Support\Data\View\Attributes;

use Attribute;


#[Attribute(Attribute::TARGET_PROPERTY)]
class Column {
    public function __construct(
        public string $label,
        public bool $sortable = false,
        public bool $searchable = false,
        public ?string $component = null, // ex: 'badge', 'avatar'
        public bool $visible = true,
    ) {}
}