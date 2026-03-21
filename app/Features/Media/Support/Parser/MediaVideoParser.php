<?php

namespace App\Features\Media\Support\Parser;

use App\Features\Media\Support\Enums\MediaSource;

class MediaVideoParser
{
    public static function parse(string $url): ?array
    {
        // YouTube
        if (preg_match('%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return [
                'id' => $match[1],
                'provider' => MediaSource::YOUTUBE->value,
                'thumbnail' => "https://img.youtube.com/vi/{$match[1]}/maxresdefault.jpg",
                'embed' => "https://www.youtube.com/embed/{$match[1]}"
            ];
        }

        // Vimeo
        if (preg_match('%vimeo\.com/(?:video/|channels/\w+/|groups/\w+/videos/|)(\d+)%i', $url, $match)) {
            $id = $match[1];
            return [
                'id' => $id,
                'provider' => MediaSource::VIMEO->value,
                'thumbnail' => "https://vumbnail.com/{$id}.jpg",
                'embed' => "https://player.vimeo.com/video/{$id}"
            ];
        }

        // Dailymotion
        if (preg_match('%(?:dailymotion\.com|dai\.ly)/(?:video|embed/video)/([^_]+)%i', $url, $match)) {
            return [
                'id' => $match[1],
                'provider' => MediaSource::DAILYMOTION->value,
                'thumbnail' => "https://www.dailymotion.com/thumbnail/video/{$match[1]}",
                'embed' => "https://www.dailymotion.com/embed/video/{$match[1]}"
            ];
        }

        return null;
    }
}