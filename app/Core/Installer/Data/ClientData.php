<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Tab, Field, Repeater, LazySelect,Section};
use App\Core\Installer\Data\ContactData;
use App\Models\User;
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
        

        // --- ONGLET : FACTURATION (Icône: credit-card, Badge: "Compta") ---
        #[Section(title: 'Facturation', description: 'Gestion des accès', icon: 'credit-card'),
            Tab(
            'Facturation',
            icon: 'credit-card',
            badge: 'Compta',
            permission: 'view-finance',
            editPermission: 'edit-finance'
        ), 
            Field(
                label: 'Numéro de TVA',
                type: 'text',
                colSpan: 12
            )
        ]
        public ?string $vat_number = null,

        #[Tab('Facturation'), 
            Field(
                label: 'Adresse de facturation',
                type: 'text',
                colSpan: 12
            )
        ]
        public ?string $address = null,

        #[Tab('Facturation'), 
            Field(
                label: 'Code Postal',
                type: 'text',
                colSpan: 4
            )
        ]
        public ?string $zip_code = null,

        #[Tab('Facturation'), 
            Field(
                label: 'Ville',
                type: 'text',
                colSpan: 8
            )
        ]
        public ?string $city = null,
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