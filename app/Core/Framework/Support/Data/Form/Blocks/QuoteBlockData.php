<?php

namespace App\Core\Framework\Support\Data\Form\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\Data\Form\Attributes\Field;

class QuoteBlockData extends Data {
    public function __construct(
        #[Field(label: 'La citation', type: 'text')]
        public string $quote,

        #[Field(label: 'Auteur', type: 'text', colSpan: 6)]
        public string $author,
    ) {}
}