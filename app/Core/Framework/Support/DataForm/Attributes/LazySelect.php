<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class LazySelect
{
    public function __construct(
        public string $label,
        public string $model,
        public string $labelColumn,
        public string $valueColumn = 'id',
        public int $colSpan = 12,
        public bool $multiple = false,
        public ?string $permission = null,
    ) {}
}