<?php

namespace App\Features\Media\Support\Path;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaCustomPathGenerator implements PathGenerator
{
    public function getPathForConversions(Media $media): string
    {
        return Str::slug($media->name).'-'.$media->id.'/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return Str::slug($media->name).'-'.$media->id.'/responsive-images/';
    }

    public function getPath(Media $media): string
    {
        return Str::slug($media->name).'-'.$media->id.'/original/';
    }
}