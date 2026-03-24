<?php

namespace App\Core\Installer\Data;

use App\Core\Framework\Support\DataForm\Attributes\{Field, Step,Repeater};
use App\Core\Installer\Http\Actions\SaveProjectIdentity;
use Spatie\LaravelData\Data;

class InstallData extends Data
{
    public function __construct(
        #[Step(name: 'Société', icon: 'building', action: SaveProjectIdentity::class)]
        #[Field(label: 'Nom de la société', required: true)]
        public string $name,

        #[Step(name: 'Contact', icon: 'user')]
        #[Repeater(label: 'Contacts', dataClass: ContactData::class)]
        public array $contacts = [],
        
        #[Step(name: 'Configuration', icon: 'settings')]
        #[Field(label: 'Activer le SSL', type: 'toggle')]
        public bool $ssl = true,
    ) {}
}