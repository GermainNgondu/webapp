<?php

namespace App\Features\Media\Domain\Models;

use App\Features\Media\Support\Path\MediaCustomPathGenerator;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMediaModel;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

class MediaLibrary extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug'];

    protected static function booting(): void
    {
        PathGeneratorFactory::setCustomPathGenerators(static::class, MediaCustomPathGenerator::class);
    }

    /**
     * Configuration des conversions Spatie (Miniatures).
     */
    public function registerMediaConversions(SpatieMediaModel $media = null): void
    {
        $this->addMediaConversion('preview')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();
        
        $conversion = $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->nonQueued();

        if ($media && $media->mime_type === 'application/pdf') {
            $conversion->format('jpg'); 
        }
    }
}