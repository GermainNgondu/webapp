<?php

namespace App\Core\Framework\Support\DataForm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class FormConfig
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public string $layout = 'simple',
        public ?string $action = null, 
        public string $saveLabel = 'save',
        public ?string $icon = null,
        public ?string $redirect = null,
        public ?string $model = null,
        public ?string $successMessage = 'Opération réussie !',
        public ?string $errorMessage = 'Une erreur est survenue lors du traitement.',
    ) {}
}