<?php

namespace App\Features\Posts\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Field,Blocks};
use App\Core\Framework\Support\DataForm\Blocks\{HeroBlockData,QuoteBlockData,TextBlockData};
use Spatie\LaravelData\Data;

class PostData extends Data
{
    public function __construct(
        
        #[Field(label: 'Titre de l\'article', required: true, colSpan: 8)]
        public string $title,

        #[Field(label: 'Statut', type: 'select', colSpan: 4, options: ['draft' => 'Brouillon', 'published' => 'Publié'])]
        public string $status = 'draft',

        #[Blocks(
            allowedBlocks: [
                HeroBlockData::class,
                QuoteBlockData::class,
                TextBlockData::class,
            ],
            label: 'Contenu de la page'
        )]
        public array $content = [],
    ) {}
}