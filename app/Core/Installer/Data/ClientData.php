<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Tab, Field, Repeater, LazySelect};
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
        #[Tab('Général', icon: 'building-office'), 
            Field(
                label: 'Nom de l\'entreprise / Client',
                type: 'text',
                colSpan: 8,
                required: true
            ), 
            Required
        ]
        public string $name,
        #[Tab('Général'), LazySelect(
            label: 'Gestionnaire du compte',
            model: User::class,
            labelColumn: 'name',
            colSpan: 6
        )]
        public ?int $user_id = null,
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
        #[Tab('Contacts'), Repeater(
            dataClass: ContactData::class, 
            label: 'Liste des contacts', 
            addLabel: 'Ajouter un contact',
            titleKey: 'name'
        )]
        /** @var ContactData[] */
        public array $contacts = [],
        

        // --- ONGLET : FACTURATION (Icône: credit-card, Badge: "Compta") ---
        #[Tab(
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