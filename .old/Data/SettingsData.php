<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Accordion,Field};
use Spatie\LaravelData\Data;

class SettingsData extends Data 
{
    public function __construct(
        #[Accordion('Identité', icon: 'user'), Field(label: 'Nom', type: 'text', colSpan: 6)]
        public string $name,

        #[Accordion('Identité'), Field(label: 'Prénom', type: 'text', colSpan: 6)]
        public string $firstname,

        #[Accordion('Sécurité', icon: 'lock-keyhole'), Field(label: 'Mot de passe', type: 'password')]
        public string $password,
        

        #[Accordion('Préférences', icon: 'bell'), Field(label: 'Recevoir les notifications', type: 'toggle')]
        public bool $notifications = true,
    ) {}
}