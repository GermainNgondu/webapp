<?php

namespace App\Features\Media\Actions;


use App\Core\Framework\Support\Data\View\Contracts\BaseIndexResourceAction;
use App\Features\Media\Domain\Models\Media;
use App\Features\Media\MediaResource;
use App\Features\Media\Support\Enums\MediaType;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class GetMediaAction extends BaseIndexResourceAction
{
    protected function getModel(): string 
    {
        return Media::class; 
    }
    protected string $resource = MediaResource::class;

    protected function discoverFilters(): array
    {
        $filters = parent::discoverFilters();

        return collect($filters)
            ->reject(fn ($filter) => $filter->getName() === 'mime_type')
            ->push(AllowedFilter::callback('mime_type', function (Builder $query, $value) {
                $type = MediaType::tryFrom($value);

                if ($type) {
                    $query->ofType($type);
                } else {
                    $query->where('mime_type', 'like', "%{$value}%");
                }
            }))
            ->values() 
            ->toArray();
    }
}