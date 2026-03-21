<?php

namespace App\Features\Media\Actions;

use App\Features\Media\Models\MediaLibrary;
use App\Features\Media\Support\Enums\MediaSource;
use App\Features\Media\Support\Parser\MediaVideoParser;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadMediaFromUrl
{
    use AsAction;

    public function handle(MediaLibrary $library, string $url, string $collection = 'all')
    {
        $videoInfo = MediaVideoParser::parse($url);

        if ($videoInfo) {
            // Récupération du titre via OEmbed (YouTube/Vimeo/Dailymotion le supportent)
            $title = $this->fetchVideoTitle($url) ?? "Vidéo " . $videoInfo['provider'];

            return $library->addMediaFromUrl($videoInfo['thumbnail'])
                ->usingName($title)
                ->withCustomProperties([
                    'source' => MediaSource::from($videoInfo['provider']),
                    'video_id' => $videoInfo['id'],
                    'video_provider' => $videoInfo['provider'],
                    'video_url' => $url,
                    'is_video' => true,
                ])
                ->toMediaCollection($collection);
        }

        return $library->addMediaFromUrl($url)->toMediaCollection($collection);
    }

    protected function fetchVideoTitle(string $url): ?string
    {
        try {
            // YouTube OEmbed endpoint par exemple
            $response = Http::get("https://noembed.com/embed", ['url' => $url]);
            return $response->json()['title'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}