<?php

namespace App\Features\Test\Actions;


use App\Core\Framework\Support\Data\View\Contracts\BaseIndexResourceAction;
use App\Features\Test\Domain\Models\Delivery;
use App\Features\Test\DeliveryResource;


class GetDeliveryAction extends BaseIndexResourceAction
{
    protected function getModel(): string 
    {
        return Delivery::class; 
    }
    protected string $resource = DeliveryResource::class;

}