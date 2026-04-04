<?php

namespace App\Core\Admin\Actions\Insights;

use App\Core\Admin\Domain\Models\Insight;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class FormInsightAction
{
    use AsAction;

    public function handle(array $data): void
    {
        dd($data);
        $slug = Str::slug($data['name']);

        $data['uuid'] = (string) Str::ulid();

        $data['user_id'] = auth()->user()->id;
        
        $data['slug'] = $slug;

        Insight::create($data);
    }
}