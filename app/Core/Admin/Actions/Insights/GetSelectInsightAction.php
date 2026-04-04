<?php

namespace App\Core\Admin\Actions\Insights;

use App\Core\Framework\Support\Data\Insight\Services\InsightDiscoveryService;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSelectInsightAction
{
    use AsAction;

    public function handle(): array
    {
        $insights = InsightDiscoveryService::getAllAvailableInsights();

        foreach ($insights as $insight) 
        {
            $id = $insight['id'];
            $type = ucfirst($insight['type']);
            $label = Str::ucwords(Str::replace('-',' ',$insight['label']));
            $target = Str::before(Str::afterLast($insight['class'],'Features\\'),'\\');

            $items[$id] = "$label ({$type} - {$target})";
        }

        return $items ?? []; 
    }
}