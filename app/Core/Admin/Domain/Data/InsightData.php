<?php

namespace App\Core\Admin\Domain\Data;

use App\Core\Admin\Actions\Insights\FormInsightAction;
use App\Core\Admin\Domain\Models\Insight;
use App\Core\Framework\Support\Data\Form\Attributes\{Field,FormConfig};
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
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
            label: 'Name', 
            type: 'text', 
            rules: 'required|string',
            colSpan: 12,
            required:true,
        )]
        public string $name,

        #[Field(
            label: 'Description', 
            type: 'textarea', 
            rules: 'string',
            colSpan: 12,
        )]
        public Optional|string $description,

        #[Field(
            label: 'Primary', 
            type: 'checkbox', 
            rules: 'boolean',
            colSpan: 12,
        )]
        public Optional|bool $is_primary,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['string'],
            'is_primary' => ['boolean'],
        ];
    }

    public static function messages(...$args): array
    {
        return [
            'name.required' => 'Le nom est requis.',
        ];
    }
}