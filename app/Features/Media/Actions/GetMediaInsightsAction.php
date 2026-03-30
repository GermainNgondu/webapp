<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Domain\Models\Media;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMediaInsightsAction
{
    use AsAction;

    public function handle($property): mixed
    {
        return match($property) {
            'totalCount' => Media::count(),
            'diskUsage' => Media::sum('size'),
            'history' => [
                'labels' => ['Jan', 'Feb', 'Mar'],
                'datasets' => [[
                    'label' => 'Uploads',
                    'data' => [12, 19, 3]
                ]]
            ],
            'uploadTrend' => 60 // +15%
        };
    }
}