<?php

namespace App\Core\Installer\Data;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\{Field, Step,Repeater};

class InstallData extends Data
{
    public function __construct(
        #[Step(name: 'Société', description: 'Informations de base', icon: 'building')]
        #[Field(label: 'Nom du client')]
        public string $name,

        #[Step(name: 'Contact', description: 'Qui gère ce compte ?', icon: 'user')]
        #[Repeater(label: 'Contacts', dataClass: ContactData::class)]
        public array $contacts = [],
        
        #[Step(name: 'Configuration', description: 'Réglages techniques')]
        #[Field(label: 'Activer le SSL', type: 'toggle')]
        public bool $ssl = true,
    ) {}
}