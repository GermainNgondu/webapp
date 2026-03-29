<?php

namespace App\Features\Test\Actions;

use App\Core\Framework\Support\Data\View\Contracts\BaseShowResourceAction;
use App\Features\Test\DeliveryResource;

class FindDeliveryAction extends BaseShowResourceAction
{
    protected string $resource = DeliveryResource::class;
}