<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Domain\Models\Media;
use App\Features\Media\Domain\Data\MediaData;
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
}