<?php

namespace App\Core\Framework\Support\DataForm\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\Field;

class TextBlockData extends Data {
    public function __construct(
        #[Field(label: 'Texte riche', type: 'richtext', required: true)]
        public string $content,
    ) {}
}