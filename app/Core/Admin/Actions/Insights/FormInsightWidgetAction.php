<?php

namespace App\Core\Admin\Actions\Insights;

use App\Core\Admin\Domain\Models\InsightWidget;
use App\Core\Framework\Support\Data\Insight\Services\InsightDiscoveryService;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class FormInsightWidgetAction
{
    use AsAction;

    public function handle(array $data): void
    {

        $insight = InsightDiscoveryService::getInsight($data['type']);
        
        $insight['config'] = array_merge($insight['config'],['label'=> $data['label'], 'description'=> $data['description'] ?? '']);
        
        $item = [
            'uuid'=> (string) Str::ulid(),
            'insight_id' => $data['insight_id'],
            'user_id'=> auth()->user()->id,
            'type'=> $insight['type'],
            'action_class'=> $insight['action'],
            'settings'=> $insight
        ];

        try {
            InsightWidget::create($item);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}