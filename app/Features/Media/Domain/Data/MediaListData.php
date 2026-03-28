<?php

namespace App\Features\Media\Domain\Data;

use App\Core\Framework\Support\Data\View\Attributes\{
    DataAction,
    Grid,
    Filter,
    Column,
    DefaultSort,
};
use App\Features\Media\Domain\Models\Media;
use Spatie\LaravelData\Data;

#[DefaultSort(column: 'created_at', direction: 'desc')]
#[DataAction(name: 'showModalImport', label: 'Importer', icon: 'plus', isGlobal: true, variant: 'primary')]
#[DataAction(name: 'show', label: 'Vue', icon: 'eye')]
#[DataAction(name: 'delete', label: 'Supprimer', icon: 'trash', color: 'red', confirm: 'Supprimer ce fichier définitivement ?')]
#[DataAction(name: 'bulkDelete', label: 'Supprimer', icon: 'trash', isBulk: true, confirm: 'Supprimer les fichiers sélectionnés ?')]
class MediaListData extends Data
{
    public function __construct(
        public int $id,
        #[Column(label: 'Nom du fichier', component: 'core::ui.media-cell', searchable: true, sortable: true)]
        #[Grid(position: 'title')]
        public string $name,
        #[Filter(label: 'Type', type: 'select', options: ['image' => 'Images', 'video' => 'Vidéos', 'application' => 'Documents'])]
        public string $mime_type,
        #[Column(label: 'Type', component: 'core::ui.media-type-badge')]
        #[Grid(position: 'badge')]
        public string $type,
        #[Grid(position: 'image')]
        public string $url,
        #[Column(label: 'Taille', sortable: true)]
        public string $human_readable_size,
    ) {}

    public static function fromModel(Media $media): self
    {
        return new self(
            id: $media->id,
            name: $media->name,
            mime_type: $media->mime_type,
            type: $media->type->value,
            url: $media->getUrl(),
            human_readable_size: $media->size_human,
        );
    }
}