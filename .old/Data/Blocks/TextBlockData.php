<?php

namespace App\Data\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\Field;
use App\Core\Framework\Support\DataForm\Attributes\BlockConfig;

#[BlockConfig(label: 'Paragraphe', icon: 'document-text', category: 'Contenu')]
class TextBlockData extends Data {
    public function __construct(
        #[Field(label: 'Texte riche', type: 'richtext', required: true)]
        public string $content,
    ) {}
}