<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Field, VisibleIf, MediaPicker};
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class ContactData extends Data {
    public function __construct(
        public ?string $id = null,
        #[MediaPicker(label: 'Photo')]
        public ?int $photo_id,        
        #[Field(label: 'Nom complet', colSpan: 6, required: true)]
        public string $name,

        #[Field(label: 'Email professionnel', colSpan: 12, type: 'email',permission: 'view-emails'), Email]
        public ?string $email = null,

        #[Field(label: 'Téléphone', colSpan: 6, type: 'tel')]
        public ?string $phone = null,

        #[Field(label: 'Site Web', colSpan: 6)]
        public ?string $website = null,
        
        #[Field(label: 'Type', type: 'select', options: ['p' => 'Particulier', 's' => 'Société'])]
        public ?string $type = null,

        #[VisibleIf(field: 'type', value: 's')]
        #[Field(label: 'Numéro de TVA')]
        public ?string $tva_number = null,
        
        #[Field(
            label: 'Compétences', 
            type: 'select', 
            multiple: true,
            options: ['php' => 'PHP', 'js' => 'JavaScript', 'css' => 'CSS'],
            colSpan: 6
        )]
        public array $skills = [],
    ) {}
}