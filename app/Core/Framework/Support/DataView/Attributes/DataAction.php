<?php

namespace App\Core\Framework\Support\DataView\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class DataAction {
    public function __construct(
        public string $name,           
        public string $label,          
        public string $icon,           
        public bool $isGlobal = false, 
        public string $variant = 'ghost',
        public ?string $color = null,
        public ?string $confirm = null,
    ) {}
}