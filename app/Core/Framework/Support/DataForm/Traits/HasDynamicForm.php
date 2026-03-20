<?php

namespace App\Core\Framework\Support\DataForm\Traits;

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

    public function validateStep(array $fieldNames)
    {
        try {
            $this->validateOnly($fieldNames[0], $this->getRules()); 
            foreach($fieldNames as $field) {
                $this->validateOnly($field);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }
}