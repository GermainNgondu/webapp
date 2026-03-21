<?php

namespace App\Features\Media\Actions;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Features\Media\Models\MediaLibrary;

class GetMediaAction
{
    use AsAction;

    /**
     * @param MediaLibrary|null $library Si fourni, filtre les médias d'une bibliothèque précise
     */
    public function handle(?MediaLibrary $library = null)
    {
        // On démarre le QueryBuilder sur le modèle Media de Spatie
        $query = QueryBuilder::for(Media::class);

        // Si une bibliothèque est passée, on restreint la recherche
        if ($library) {
            $query->where('model_type', MediaLibrary::class)
                  ->where('model_id', $library->id);
        }

        return $query
            ->allowedFilters([
                // Filtrer par nom de fichier ou titre personnalisé
                'name',
                'file_name',
                // Filtrer par collection (ex: 'avatars', 'documents')
                'collection_name',
                // Filtrer par type MIME (ex: 'image/jpeg', 'application/pdf')
                AllowedFilter::partial('type', 'mime_type'),
                // Filtre personnalisé pour la recherche globale
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%{$value}%")
                          ->orWhere('file_name', 'LIKE', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['created_at', 'size', 'name'])
            ->defaultSort('-created_at'); // Plus récents en premier
    }
}