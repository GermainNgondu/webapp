<?php

namespace App\Core\Framework\Support\Data\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Blocks
{
    public function __construct(
        public array $allowedBlocks = [],
        public ?string $label = null,
        public string $placeholder = 'Ajouter un bloc',
    ) {}
}
