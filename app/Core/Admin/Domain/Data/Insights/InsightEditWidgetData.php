<?php

namespace App\Core\Admin\Domain\Data\Insights;

use App\Core\Admin\Actions\Insights\FormInsightWidgetAction;
use App\Core\Admin\Domain\Models\InsightWidget;
use App\Core\Framework\Support\Data\Form\Attributes\{Field,FormConfig};
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

#[FormConfig(
    action: FormInsightWidgetAction::class,
    saveLabel: 'save',
    saveIcon:'pencil-square',
    model: InsightWidget::class,
    dispatch: 'main::admin.dashboard',
)]
class InsightEditWidgetData extends Data
{
    public function __construct(

        #[Field(type: 'hidden')]
        public mixed $uuid,
        
        #[Field(type: 'hidden')]
        public mixed $insight_id,

        #[Field(
            label: 'Label', 
            type: 'text', 
            rules: 'required|string',
            colSpan: 12,
            required:true,
        )]
        public string $label,

        #[Field(
            label: 'Description', 
            type: 'textarea', 
            rules: 'string',
            colSpan: 12,
        )]
        public Optional|string $description,
    ) {}
}