<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Field};
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class ContactData extends Data {
    public function __construct(
        public ?string $id = null,
        
        #[Field(label: 'Nom complet', colSpan: 6, required: true)]
        public string $name,
        #[Field(label: 'Email professionnel', colSpan: 12, type: 'email',permission: 'view-emails'), Email]
        public ?string $email = null,

        #[Field(label: 'Téléphone', colSpan: 6, type: 'tel')]
        public ?string $phone = null,

        #[Field(label: 'Site Web', colSpan: 6)]
        public ?string $website = null,

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