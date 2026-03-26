<?php

namespace App\Features\Media\Domain\Data;

use App\Features\Media\Domain\Models\MediaLibrary;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class MediaLibraryData extends Data
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $description,
        /** @var DataCollection<MediaData> */
        public ?DataCollection $items,
    ) {}

    public static function fromModel(MediaLibrary $library): self
    {
        return new self(
            id: $library->id,
            name: $library->name,
            description: $library->description,
            items: MediaData::collect($library->getMedia('all')),
        );
    }
}