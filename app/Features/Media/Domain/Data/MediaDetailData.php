<?php

namespace App\Features\Media\Domain\Data;

use App\Core\Framework\Support\Data\View\Attributes\{
    DataAction,
    Detail
};
use App\Features\Media\Domain\Models\Media;
use Spatie\LaravelData\Data;

#[DataAction(name: 'show', label: 'Vue', icon: 'eye')]
#[DataAction(name: 'delete', label: 'Supprimer', icon: 'trash', color: 'red', confirm: 'Supprimer ce fichier définitivement ?')]
class MediaDetailData extends Data
{
    public function __construct(
        public int $id,
        #[Detail(label: 'Media', component: 'core::data.view.viewers.media-viewer')]
        public string $url,
        #[Detail(label: 'Nom du fichier')]
        public string $name,
        #[Detail(label: 'Type', component: 'core::ui.media-type-badge', inline: true)]
        public string $type,
        #[Detail(label: 'Taille', inline:true)]
        public string $human_readable_size,
    ) {}

    public static function fromModel(Media $media): self
    {
        return new self(
            id: $media->id,
            name: $media->name,
            type: $media->type->value,
            url: $media->getUrl(),
            human_readable_size: $media->size_human,
        );
    }
}