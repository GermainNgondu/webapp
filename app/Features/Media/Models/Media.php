<?php

namespace App\Features\Media\Models;

use App\Features\Media\Support\Enums\MediaSource;
use App\Features\Media\Support\Enums\MediaType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;


class Media extends SpatieMedia
{
    use SoftDeletes;

    protected $appends = ['url', 'thumb_url', 'size_human', 'type', 'source'];

    /**
     * Détermine le type de média (image, video, etc.) pour l'affichage.
     */
    public function getTypeAttribute(): MediaType
    {
        if ($this->getCustomProperty('source') === MediaSource::YOUTUBE->value) {
            return MediaType::YOUTUBE;
        }

        $mime = $this->mime_type;

        return match (true) {
            str_starts_with($mime, 'image/') => MediaType::IMAGE,
            str_starts_with($mime, 'video/') => MediaType::VIDEO,
            str_starts_with($mime, 'audio/') => MediaType::AUDIO,
            str_contains($mime, 'pdf') || str_contains($mime, 'office') => MediaType::DOCUMENT,
            str_contains($mime, 'zip') || str_contains($mime, 'rar') => MediaType::ARCHIVE,
            default => MediaType::OTHER,
        };
    }

    /**
     * Récupère la source du média (Local, Unsplash, etc.).
     */
    public function getSourceAttribute(): MediaSource
    {
        return MediaSource::from($this->getCustomProperty('source', 'local'));
    }

    /**
     * Formate la taille en version lisible.
     */
    public function getSizeHumanAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($this->size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}