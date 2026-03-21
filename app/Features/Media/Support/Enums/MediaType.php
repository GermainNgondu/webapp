<?php

namespace App\Features\Media\Support\Enums;

enum MediaType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case YOUTUBE = 'youtube';
    case VIMEO = 'vimeo';
    case DAILYMOTION = 'dailymotion';
    case DOCUMENT = 'document';
    case ARCHIVE = 'archive';
    case OTHER = 'other';
}