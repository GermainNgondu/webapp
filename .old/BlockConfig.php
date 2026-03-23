<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockConfig {
    public function __construct(
        public string $label,
        public string $icon = 'cube',
        public ?string $preview = null,
        public string $category = 'Général',
    ) {}
}