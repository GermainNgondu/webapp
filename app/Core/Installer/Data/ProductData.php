<?php

namespace App\Core\Installer\Data;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\Field;
use App\Core\Framework\Support\DataForm\Attributes\VisibleIf;

class ProductData extends Data
{
    public function __construct(
        #[Field(label: 'Nom du produit', type: 'text')]
        public string $name = '',

        #[Field(label: 'Prix HT', type: 'number')]
        public float $price = 0,

        #[Field(label: 'Type de client', type: 'select', options: ['pro' => 'Professionnel', 'part' => 'Particulier', 'asso' => 'Association'])]
        public string $client_type = 'part',

        // Affiche si le prix est STRICTEMENT supérieur à 500
        #[VisibleIf(field: 'price', value: 500, operator: '>')]
        #[Field(label: 'Justification prix élevé')]
        public ?string $justification = null,

        // Affiche si le type de client n'est PAS particulier
        #[VisibleIf(field: 'client_type', value: 'part', operator: '!=')]
        #[Field(label: 'Numéro SIRET')]
        public ?string $siret = null,

        // Affiche si la valeur est dans la liste (IN)
        #[VisibleIf(field: 'client_type', value: ['pro', 'asso'], operator: 'in')]
        #[Field(label: 'Document fiscal')]
        public ?string $tax_doc = null,
    ) {}
}