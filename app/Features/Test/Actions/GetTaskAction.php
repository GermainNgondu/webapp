<?php

namespace App\Features\Test\Actions;


use App\Core\Framework\Support\Data\View\Contracts\BaseIndexResourceAction;
use App\Features\Test\Domain\Models\Task;
use App\Features\Test\TaskResource;


class GetTaskAction extends BaseIndexResourceAction
{
    protected function getModel(): string 
    {
        return Task::class; 
    }
    protected string $resource = TaskResource::class;

}