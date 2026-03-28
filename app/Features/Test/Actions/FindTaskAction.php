<?php

namespace App\Features\Test\Actions;

use App\Core\Framework\Support\Data\View\Contracts\BaseShowResourceAction;
use App\Features\Test\TaskResource;

class FindTaskAction extends BaseShowResourceAction
{
    /**
     * On lie l'action à la ressource Task.
     * La BaseShowAction utilisera automatiquement TaskResource::detailData()
     * pour transformer le modèle en DTO.
     */
    protected string $resource = TaskResource::class;
}