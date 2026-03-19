<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use ReflectionClass;
use ReflectionProperty;
use App\Core\Framework\Support\DataForm\Attributes\FormField;

trait HasDynamicForm
{
    public function searchLazyOptions($model, $labelCol, $valueCol, $search = '', $page = 1)
    {
        $perPage = 20;
        $results = $model::query()
            ->when($search, fn($q) => $q->where($labelCol, 'like', "%{$search}%"))
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->pluck($labelCol, $valueCol)->toArray(),
            'hasMore' => $results->hasMorePages(),
        ];
    }
}