<?php

namespace App\Features\Media\Support\Enums;

enum MediaSource: string
{
    case LOCAL = 'local';
    case URL = 'url';
    case AI = 'ai';
    case UNSPLASH = 'unsplash';
    case YOUTUBE = 'youtube';
    case VIMEO = 'vimeo';
    case DAILYMOTION = 'dailymotion';

    public function label(): string
    {
        return match($this) {
            self::LOCAL => 'Fichiers locaux',
            self::URL => 'URL externe',
            self::AI => 'Généré par IA',
            self::UNSPLASH => 'Unsplash',
            self::YOUTUBE => 'YouTube',
            self::VIMEO => 'Vimeo',
            self::DAILYMOTION => 'Dailymotion',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::LOCAL => 'computer-desktop',
            self::URL => 'link',
            self::AI => 'sparkles',
            self::UNSPLASH => 'photo',
            self::YOUTUBE => 'play-circle',
            self::VIMEO => 'play-circle',
            self::DAILYMOTION => 'play-circle',
        };
    }

    public function isExternal(): bool
    {
        return in_array($this, [self::URL, self::UNSPLASH, self::YOUTUBE, self::VIMEO, self::DAILYMOTION]);
    }
}