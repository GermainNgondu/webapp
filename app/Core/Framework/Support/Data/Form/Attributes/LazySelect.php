<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

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
        public ?string $editPermission = null,
        public ?string $iconColumn = null,
        public ?string $imageColumn = null,
    ) {}
}