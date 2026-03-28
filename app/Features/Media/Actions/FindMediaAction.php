<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Domain\Models\Media;
use App\Features\Media\Domain\Data\MediaData;
use App\Core\Framework\Support\Data\View\Contracts\BaseShowAction;

class FindMediaAction extends BaseShowAction
{
    protected function getModel(): string { return Media::class; }
    protected function getDataClass(): string { return MediaData::class; }

    public function execute($id)
    {
        $media = Media::query()->findOrFail($id);

        return MediaData::from($media);
    }
}