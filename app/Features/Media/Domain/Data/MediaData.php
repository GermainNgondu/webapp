<?php

namespace App\Features\Media\Domain\Data;

use App\Features\Media\Domain\Models\Media;
use App\Features\Media\Support\Enums\MediaSource;
use App\Features\Media\Support\Enums\MediaType;
use Spatie\LaravelData\Data;

class MediaData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $file_name,
        public string $mime_type,
        public MediaType $type,
        public MediaSource $source,
        public string $size,
        public string $url,
        public string $human_readable_size,
        public bool $is_video = false,
        public ?string $video_url = null,
        public ?string $video_provider = null,
        public ?string $extension = null,
    ) {}

    public static function fromModel(Media $media): self
    {
        return new self(
            id: $media->id,
            name: $media->name,
            file_name: $media->file_name,
            mime_type: $media->mime_type,
            type: $media->type,
            source: $media->source,
            size: (string) $media->size,
            url: $media->getUrl(),
            human_readable_size: $media->size_human,
            is_video: $media->isVideo(),
            video_url: $media->getVideoUrl(),
            video_provider: $media->getVideoProvider(),
            extension: $media->extension,
        );
    }
}