<?php

namespace App\Core\Admin\Domain\Data\Insights;

use App\Core\Admin\Actions\Insights\FormInsightWidgetAction;
use App\Core\Admin\Actions\Insights\GetSelectInsightAction;
use App\Core\Admin\Domain\Models\InsightWidget;
use App\Core\Framework\Support\Data\Form\Attributes\{Field,FormConfig,Select};
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

#[FormConfig(
    action: FormInsightWidgetAction::class,
    saveLabel: 'add',
    saveIcon:'plus',
    model: InsightWidget::class,
    dispatch: 'main::admin.dashboard',
)]
class InsightCreateWidgetData extends Data
{
    public function __construct(

        #[Field(type: 'hidden')]
        public mixed $id,
        
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

        #[Select(
            label: 'Type', 
            rules: 'required|string',
            actionOptions : GetSelectInsightAction::class,
            colSpan: 12,
            required:true
        )]
        public string $type,
    ) {}
}