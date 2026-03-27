<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Domain\Models\MediaLibrary;
use App\Features\Media\Support\Enums\MediaType;
use App\Features\Media\Support\Enums\MediaSource;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\UploadedFile;

class UploadMedia
{
    use AsAction;

    public function handle(MediaLibrary $library, UploadedFile $file, string $collection = 'all')
    {
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

        $type = match ($ext) {
            'jpg','jpeg','png','gif','webp','svg' => MediaType::IMAGE,
            'mp4','mov','avi','mkv','webm' => MediaType::VIDEO,
            'mp3','wav','aac','ogg','flac' => MediaType::AUDIO,
            'pdf','doc','docx','xls','xlsx','ppt','pptx' => MediaType::DOCUMENT,
            'zip','rar','tar','gz' => MediaType::ARCHIVE,
            default => MediaType::OTHER,
        };

        return $library->addMedia($file)
            ->withCustomProperties([
                'user_id' => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'type' => $type->value,
                'source' => MediaSource::LOCAL->value,
            ])
            ->toMediaCollection($collection);
    }
}