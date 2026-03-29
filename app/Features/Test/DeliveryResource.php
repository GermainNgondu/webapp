<?php

namespace App\Features\Test;

use App\Core\Framework\Support\Resource\Contracts\BaseResource;
use App\Features\Test\Domain\Data\Delivery\DeliveryDetailData;
use App\Features\Test\Domain\Data\Delivery\DeliveryFormData;
use App\Features\Test\Domain\Data\Delivery\DeliveryInsightData;
use App\Features\Test\Domain\Data\Delivery\DeliveryListData;
use App\Features\Test\Domain\Models\Delivery;

class DeliveryResource extends BaseResource
{
    public static function model(): string { return Delivery::class; }
    public static function listData(): string { return DeliveryListData::class; }
    public static function detailData(): string { return DeliveryDetailData::class; }
    public static function formData(): string { return DeliveryFormData::class; }
    public static function insightData(): string { return DeliveryInsightData::class; }
    public static function icon(): string { return 'truck'; }
}