<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Models\MediaLibrary;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\UploadedFile;

class UploadMedia
{
    use AsAction;

    public function handle(MediaLibrary $library, UploadedFile $file, string $collection = 'all')
    {
        return $library->addMedia($file)
            ->withCustomProperties([
                'user_id' => auth()->id(),
                'original_name' => $file->getClientOriginalName()
            ])
            ->toMediaCollection($collection);
    }
}