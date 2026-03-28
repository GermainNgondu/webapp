<?php

namespace App\Features\Test\Domain\Data;

use App\Core\Framework\Support\Data\Form\Attributes\Field;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class TaskFormData extends Data {
    public function __construct(
        #[Field(label: 'Titre', type: 'text', required: true, min: 3)]
        public string $title,
        #[Field(label: 'Statut', type: 'select', required: true, options: ['todo', 'in_progress', 'review', 'done'])]
        public string $status,
    ) {}
    public static function rules(ValidationContext $context = null): array {
        return [
            'title' => ['required', 'string', 'min:3'],
            'status' => ['required', 'in:todo,in_progress,review,done'],
        ];
    }

    public static function messages(...$args): array {
        return [
            'title.required' => 'Le titre est requis.',
            'title.min' => 'Le titre doit contenir au moins 3 caractères.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être l\'un des suivants : todo, in_progress, review, done.',
        ];
    }
}