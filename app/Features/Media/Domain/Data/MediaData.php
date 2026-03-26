<?php

namespace App\Features\Media\Domain\Data;

use App\Core\Framework\Support\DataView\Attributes\{DataAction,Grid, Filter,Column};
use App\Features\Media\Domain\Models\Media;
use Spatie\LaravelData\Data;

#[DataAction(name: 'showModalImport', label: 'Importer', icon: 'plus', isGlobal: true, variant: 'primary')]
#[DataAction(name: 'ShowModalEdit', label: 'Modifier', icon: 'pencil-square')]
#[DataAction(name: 'delete', label: 'Supprimer', icon: 'trash', color: 'red', confirm: 'Supprimer ce fichier définitivement ?')]
class MediaData extends Data
{
    public function __construct(
        public int $id,
        #[Column(label: 'Nom du fichier', searchable: true, sortable: true)]
        #[Grid(position: 'title')]
        public string $name,
        public string $file_name,
        #[Filter(label: 'Type', type: 'select', options: ['image' => 'Images', 'video' => 'Vidéos', 'application' => 'Documents'])]
        public string $mime_type,
        #[Column(label: 'Type', component: 'core::ui.media-type-badge')]
        #[Grid(position: 'badge')]
        public string $type,
        public string $source,
        public string $size,
        #[Grid(position: 'image')]
        public string $url,
        #[Column(label: 'Taille', sortable: true)]
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
            type: $media->type->value,
            source: $media->source->value,
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