<?php

namespace App\Features\Media\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Features\Media\Support\Enums\MediaSource;
use App\Features\Media\Support\Enums\MediaType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;


class Media extends BaseMedia
{
    use SoftDeletes;

    protected $appends = ['url', 'thumb_url', 'size_human', 'type', 'source'];
    /**
     * Détermine si le média est une vidéo distante (YouTube/Vimeo/etc)
     */
    public function isVideo(): bool
    {
        return (bool) $this->getCustomProperty('is_video', false);
    }

    /**
     * Récupère l'URL de la vidéo originale
     */
    public function getVideoUrl(): ?string
    {
        return $this->getCustomProperty('video_url');
    }

    /**
     * Récupère le nom du fournisseur (youtube, vimeo...)
     */
    public function getVideoProvider(): ?string
    {
        return $this->getCustomProperty('video_provider');
    }
    /**
     * Détermine le type de média (image, video, etc.) pour l'affichage.
     */
    public function getTypeAttribute(): MediaType
    {
        $source = $this->getCustomProperty('source');

        if ($source != 'local') {
            return match ($source) {
                 MediaSource::YOUTUBE->value => MediaType::YOUTUBE,
                 MediaSource::VIMEO->value => MediaType::VIMEO,
                 MediaSource::DAILYMOTION->value => MediaType::DAILYMOTION,
                 default => MediaType::OTHER,
            };
        }
        
        $type = $this->getCustomProperty('type');

        if($type){ return MediaType::from($type); }

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

    /**
     * Scope typé avec MediaType.
     * On garantit que $type est obligatoirement un cas de notre Enum.
     */
    public function scopeOfType(Builder $query, MediaType $type): Builder
    {
        return match ($type) {
            MediaType::VIDEO => $query->where('custom_properties->type', MediaType::VIDEO->value),
            MediaType::IMAGE => $query->where('custom_properties->type', MediaType::IMAGE->value),
            MediaType::AUDIO => $query->where('custom_properties->type', MediaType::AUDIO->value),
            MediaType::DOCUMENT => $query->where('custom_properties->type', MediaType::DOCUMENT->value),
            MediaType::ARCHIVE => $query->where('custom_properties->type', MediaType::ARCHIVE->value),
            MediaType::OTHER => $query->where('custom_properties->type', MediaType::OTHER->value),
        };
    }    
}