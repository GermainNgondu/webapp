<?php

namespace App\Core\Admin\Domain\Data;

use App\Core\Admin\Actions\Insights\FormInsightAction;
use App\Core\Admin\Domain\Models\Insight;
use App\Core\Framework\Support\Data\Form\Attributes\{Field,FormConfig};
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[FormConfig(
    action: FormInsightAction::class,
    saveLabel: 'add',
    saveIcon: 'plus',
    model: Insight::class,
    dispatch: 'main::admin.dashboard',
)]
class InsightData extends Data
{
    public function __construct(
        #[Field(
            label: 'Nom', 
            type: 'text', 
            rules: 'required|string',
            colSpan: 12,
        )]
        public string $name,

        #[Field(
            label: 'Description', 
            type: 'textarea', 
            rules: 'required|string',
            colSpan: 12,
        )]
        public string $description,

        #[Field(
            label: 'Favoris', 
            type: 'checkbox', 
            rules: 'required|boolean',
            colSpan: 12,
        )]
        public bool $favorite,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'favorite' => ['required', 'boolean'],
        ];
    }

    public static function messages(...$args): array
    {
        return [
            'name.required' => 'Le nom est requis.',
            'description.required' => 'La description est requise.',
            'favorite.required' => 'Le champ favoris doit être un booléen.',
        ];
    }
}