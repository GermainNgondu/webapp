<?php
namespace App\Features\Media;

use App\Core\Framework\Support\Resource\Contracts\BaseResource;
use App\Features\Media\Domain\Data\{
    MediaDetailData,
    MediaFormData,
    MediaInsightData,
    MediaListData
};
use App\Features\Media\Domain\Models\Media;

class MediaResource extends BaseResource
{

    public static function model(): string 
    {
        return Media::class;
    }
    public static function listData(): string 
    {
        return MediaListData::class;
    }

    public static function detailData(): string 
    {
        return MediaDetailData::class;
    }

    public static function formData(): string 
    {
        return MediaFormData::class;
    }

    public static function insightData(): string 
    {
        return MediaInsightData::class;
    }

    public static function icon(): string 
    {
        return 'images';
    }
    
}