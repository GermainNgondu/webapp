<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Tab {
    public function __construct(
        public string $name,
        public ?string $icon = null,
        public ?string $badge = null,
        public ?string $permission = null,
        public ?string $editPermission = null,
    ) {}
}