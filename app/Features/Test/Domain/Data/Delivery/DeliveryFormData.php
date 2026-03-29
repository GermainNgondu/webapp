<?php

namespace App\Features\Test\Domain\Data\Delivery;

use App\Core\Framework\Support\Data\Form\Attributes\Field;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class DeliveryFormData extends Data
{
    public function __construct(
        #[Field(label: 'Chauffeur', type: 'select', required: true, options: ['todo', 'in_progress', 'review', 'done'])]
        public string $driver_name,
    ) {}
    public static function rules(ValidationContext $context = null): array {
        return [
            'driver_name' => ['required', 'string', 'min:3'],
        ];
    }

    public static function messages(...$args): array {
        return [
            'driver_name.required' => 'Le chauffeur est requis.',
            'driver_name.min' => 'Le chauffeur doit contenir au moins 3 caractères.',
        ];
    }
}