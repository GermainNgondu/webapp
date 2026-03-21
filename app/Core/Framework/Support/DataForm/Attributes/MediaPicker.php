<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MediaPicker
{
    public function __construct(
        public string $label,
        public ?string $description = null,
        public string $collection = 'all',
        public bool $multiple = false,
        public bool $required = false,
        public ?string $permission = null,
        public ?string $editPermission = null,
        public int $colSpan = 12,
    ) {}
}