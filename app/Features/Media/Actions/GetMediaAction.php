<?php

namespace App\Features\Media\Actions;


use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Features\Media\Domain\Models\Media;
use App\Features\Media\Domain\Data\MediaData;
use App\Features\Media\Support\Enums\MediaType;
use App\Core\Framework\Support\DataView\Contracts\BaseDataViewAction;

class GetMediaAction extends BaseDataViewAction
{
    protected function getModel(): string 
    {
        return Media::class; 
    }

    protected function getDataClass(): string 
    {
        return MediaData::class; 
    }

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