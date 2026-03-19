<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Field
{
    public function __construct(
        public string $label, 
        public int $colSpan = 12,           
        public ?string $type = null,
        public ?string $placeholder = null,
        public bool $multiple = false,
        public array $options = [],
        public string $component = 'input',
        public bool $required = false,
        public ?string $permission = null,
        public ?string $editPermission = null,
    ) {}
}