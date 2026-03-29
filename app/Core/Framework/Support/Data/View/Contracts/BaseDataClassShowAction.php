<?php
namespace App\Core\Framework\Support\Data\View\Contracts;

abstract class BaseDataClassShowAction
{
    abstract protected function getModel(): string;
    abstract protected function getDataClass(): string;

    public function execute($id)
    {
        $model = $this->getModel()::findOrFail($id);
        return ($this->getDataClass())::from($model);
    }
}