<?php

namespace App\Data\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\Field;
use App\Core\Framework\Support\DataForm\Attributes\BlockConfig;

#[BlockConfig(label: 'Citation', icon: 'chat-bubble-bottom-center-text', category: 'Contenu')]
class QuoteBlockData extends Data {
    public function __construct(
        #[Field(label: 'La citation', type: 'text')]
        public string $quote,

        #[Field(label: 'Auteur', type: 'text', colSpan: 6)]
        public string $author,
    ) {}
}