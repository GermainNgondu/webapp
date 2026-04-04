<?php

namespace App\Core\Admin\Actions\Insights;

use App\Core\Admin\Domain\Models\Insight;
use App\Core\Framework\Support\Data\Insight\Services\InsightDiscoveryService;
use Lorisleiva\Actions\Concerns\AsAction;

class GetInsightAction
{
    use AsAction;

    public function handle(?string $id = null): Insight|null
    {
        $insight = $this->insightUser($id);

        if(!$insight)
        {
            //Fist default user insight 
            $insight = auth()->user()->insights()->first();

            //Get default admin insight
            if(!$insight)
            {
                $insight = Insight::where('is_root', true)->first();
            }
        }

        return $insight ?? null;
    }

    private function insightUser(?string $id = null): Insight|null
    {
         $query = auth()->user()->insights();

        if($id) 
        {
            return  $query->where('uuid', $id)->first();
        }
         
        return $query->where('is_primary', true)->first();
    }
}