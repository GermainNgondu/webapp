<?php

namespace App\Core\Framework\Support\Data\Form\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\Data\Form\Attributes\{Field};

class HeroBlockData extends Data {
    public function __construct(
        #[Field(label: 'Titre principal', required: true)]
        public string $title,

        #[Field(label: 'Sous-titre', type: 'text')]
        public ?string $subtitle,

        #[Field(label: 'Bouton CTA', type: 'text')]
        public ?string $cta_label,

        #[Field(label: 'Lien CTA', type: 'text')]
        public ?string $cta_url,
    ) {}
}