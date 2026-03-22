<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasLazySelect
{
    public function searchLazyOptions($modelClass, $labelCol, $valueCol, $search = '', $page = 1)
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            abort(403, "Seuls les modèles Eloquent peuvent être interrogés.");
        }

        $perPage = 20;
        $results = $modelClass::query()
            ->when($search, fn($q) => $q->where($labelCol, 'like', "%{$search}%"))
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->pluck($labelCol, $valueCol)->toArray(),
            'hasMore' => $results->hasMorePages(),
        ];
    }
}
