<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Select
{
    public function __construct(
        public ?string $label = null, 
        public int $colSpan = 12,
        public ?string $placeholder = null,
        public bool $multiple = false,
        public array $options = [],
        public ?string $actionOptions= null,
        public ?string $permission = null,
        public ?string $editPermission = null,
        public ?string $description = null,
        public bool $required = false,
        public ?string $rules = null,
    ) {}
}