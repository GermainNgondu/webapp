<?php

namespace App\Core\Framework\Support\DataView\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Filter {
    public function __construct(
        public string $label,
        public string $type = 'text',      // text, select, date, boolean, multi-select
        public array|string|null $options = null, // Array ['value' => 'Label'] ou Classe Enum
        public string $placeholder = 'Filtrer...',
    ) {}
}