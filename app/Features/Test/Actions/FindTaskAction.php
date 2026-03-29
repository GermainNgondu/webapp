<?php

namespace App\Features\Test\Actions;

use App\Core\Framework\Support\Data\View\Contracts\BaseShowResourceAction;
use App\Features\Test\TaskResource;

class FindTaskAction extends BaseShowResourceAction
{
    protected string $resource = TaskResource::class;
}