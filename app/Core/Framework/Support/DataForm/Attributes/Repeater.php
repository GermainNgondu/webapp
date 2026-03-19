<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Repeater {
    public function __construct(
        public string $dataClass,
        public string $label,
        public string $addLabel = 'add_item',
        public ?string $titleKey = null,
        public int $colSpan = 12,
        public ?string $permission = null,
        public ?string $editPermission = null,
    ) {}
}