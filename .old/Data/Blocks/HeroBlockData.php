<?php

namespace App\Data\Blocks;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\{Field,MediaPicker,BlockConfig};

#[BlockConfig(label: 'En-tête (Hero)', icon: 'presentation-chart-bar', category: 'Design', preview: 'previews/hero.png')]
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