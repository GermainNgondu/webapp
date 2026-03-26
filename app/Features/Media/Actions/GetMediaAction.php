<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Domain\Data\MediaData;
use App\Features\Media\Domain\Models\Media;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;

class GetMediaAction
{
    use AsAction;

    /**
     * @return DataCollection<MediaData>
     */
    public function handle(?string $search = null, ?string $type = null): mixed
    {
        $query = Media::query()->latest();

        // Filtre par recherche (Nom ou Nom de fichier)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Filtre par type (ex: 'video' ou 'image')
        if ($type === 'video') {
            $query->where('custom_properties->is_video', true);
        } elseif ($type === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        }

        return MediaData::collect($query->paginate(10));
    }
}