<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Tab, Field, Repeater,Section};
use App\Core\Installer\Data\ContactData;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ClientData extends Data {
    public function __construct(
        public ?int $id = null,

        
        // --- ONGLET : GÉNÉRAL (Icône: building-office) ---
        #[Section(title: 'Général', description: 'Informations publiques du profil', icon: 'building-office'),
            Tab('Général', icon: 'building-office'), 
            Field(
                label: 'Nom de l\'entreprise / Client',
                type: 'text',
                colSpan: 8,
                required: true,
                description: 'le nom sera utilisé pour la facturation et les devis',
            ), 
            Required
        ]
        public string $name,


        #[Tab('Général'), 
            Field(
                label: 'Type de client',
                type: 'select',
                colSpan: 4,
                required: true,
                options: ['company' => 'Entreprise', 'individual' => 'Particulier','organization'=> 'Organization','school'=> 'School']
            ), 
            Required
        ]
        public string $type,

        // --- ONGLET : ---
        #[Section(title: 'Contacts', description: 'Gestion des accès', icon: 'user'),
            Tab('Contacts'), 
            Repeater(
            dataClass: ContactData::class, 
            label: 'Liste des contacts', 
            addLabel: 'Ajouter un contact',
            titleKey: 'name'
        )]
        /** @var ContactData[] */
        public array $contacts = [],
        
        #[Section(title: 'Notes', description: 'Notes internes', icon: 'document-text'), Field(
            label: 'Notes internes',
            type: 'richtext',
            colSpan: 12,
        )]
        public ?string $notes = null,
    ) {}

    public static function rules(ValidationContext|null $context = null): array
    {
        $clientId = $context->payload['id'] ?? null;

        return [
            'email' => [
                'nullable',
                'email',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
        ];
    }
}