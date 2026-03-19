<?php

namespace App\Core\Installer\Data;

use Spatie\LaravelData\Data;
use App\Core\Framework\Support\DataForm\Attributes\{FormField, Step};
use Spatie\LaravelData\Support\Validation\ValidationContext;

class InstallData extends Data
{
    public function __construct(
        // ÉTAPE 1 : CHOIX DE LA LANGUE
        #[Step(1), FormField(
            label: 'install.fields.locale', 
            type: 'select', 
            options: ['fr' => 'Français', 'en' => 'English']
        )]
        public string $locale = 'fr',

        // ÉTAPE 2 : PRÉREQUIS SYSTÈME (Géré par un Slot)
        #[Step(2), FormField(
            label: 'install.steps.requirements', 
            hidden: true
        )]
        public bool $requirements_passed = false,

        // ÉTAPE 3 : CONFIGURATION BASE DE DONNÉES
        #[Step(3), FormField(
            label: 'install.fields.db_host', 
            placeholder: 'install.placeholders.db_host'
        )]
        public string $db_host = '127.0.0.1',

        #[Step(3), FormField(label: 'install.fields.db_name')]
        public string $db_name,

        #[Step(3), FormField(label: 'install.fields.db_user')]
        public string $db_user,

        #[Step(3), FormField(
            label: 'install.fields.db_password', 
            type: 'password'
        )]
        public ?string $db_password = null,

        // ÉTAPE 4 : CONFIGURATION DE L'APPLICATION
        #[Step(4), FormField(
            label: 'install.fields.app_name', 
            placeholder: 'install.placeholders.app_name'
        )]
        public string $app_name,

        #[Step(4), FormField(label: 'install.fields.app_url')]
        public string $app_url,

        // ÉTAPE 5 : COMPTE ADMINISTRATEUR
        #[Step(5), FormField(label: 'install.fields.admin_name')]
        public string $admin_name,

        #[Step(5), FormField(
            label: 'install.fields.admin_email', 
            type: 'email'
        )]
        public string $admin_email,

        #[Step(5), FormField(
            label: 'install.fields.admin_password', 
            type: 'password'
        )]
        public string $admin_password,
    ) {}

    /**
     * Règles de validation spécifiques pour l'installation.
     */
    public static function rules(ValidationContext|null $context = null): array
    {
        return [
            'admin_password' => ['required', 'min:8'],
            'app_url'        => ['required', 'url'],
            'admin_email'    => ['required', 'email'],
            'db_name'        => ['required', 'string'],
            'db_user'        => ['required', 'string'],
        ];
    }
}