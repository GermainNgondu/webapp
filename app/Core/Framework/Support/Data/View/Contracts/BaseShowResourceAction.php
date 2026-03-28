<?php

namespace App\Core\Framework\Support\Data\View\Contracts;

abstract class BaseShowResourceAction
{
    protected string $resource;

    public function execute(mixed $id)
    {
        $resource = $this->resource;
        $dataClass = $resource::detailData();

        $model = $resource::model()::with($this->with())->findOrFail($id);

        return $dataClass::from($model);
    }

    // Relations à charger par défaut
    protected function with(): array {
        return [];
    }
}