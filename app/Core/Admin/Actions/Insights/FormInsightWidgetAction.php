<?php

namespace App\Core\Admin\Actions\Insights;

use Exception;
use App\Core\Admin\Domain\Models\InsightWidget;
use App\Core\Framework\Support\Data\Insight\Services\InsightDiscoveryService;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class FormInsightWidgetAction
{
    use AsAction;

    public function handle(array $data): void
    {
        $uuid = $data['uuid'] ?? null;

        if($uuid)
        {
            $widget = InsightWidget::where('uuid',$uuid)->first();

            if($widget)
            {
                $settings  = $widget['settings'];
                $settings['config'] =  array_merge($settings['config'],['label'=> $data['label'], 'description'=> $data['description'] ?? '']);
                $widget->update(['settings'=> $settings]);
            }
            else
            {
                throw new Exception("Widget not found", 404);   
            }
        }
        else
        {
            $widget= InsightDiscoveryService::getWidget($data['type']);
            
            $widget['config'] = array_merge($widget['config'],['label'=> $data['label'], 'description'=> $data['description'] ?? '']);
            
            $item = [
                'uuid'=> (string) Str::ulid(),
                'insight_id' => $data['insight_id'],
                'user_id'=> auth()->user()->id,
                'type'=> $widget['type'],
                'action_class'=> $widget['action'],
                'settings'=> $widget
            ];

            try {
                InsightWidget::create($item);
            } catch (Exception $e) {
                throw $e;
            }            
        }
    }
}