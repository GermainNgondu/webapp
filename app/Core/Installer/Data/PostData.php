<?php

namespace App\Core\Installer\Data;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\{Field};

class PostData extends Data
{
    public function __construct(
        
        #[Field(label: 'Titre de l\'article', required: true, colSpan: 8)]
        public string $title,

        #[Field(label: 'Statut', type: 'select', colSpan: 4, options: ['draft' => 'Brouillon', 'published' => 'Publié'])]
        public string $status = 'draft',

        #[Field(label: 'Contenu de la page', type: 'blocks')]
        public array $content = [],
    ) {}
}