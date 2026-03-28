<?php

namespace App\Core\Framework\Support\Data\View\Contracts;

use App\Core\Framework\Support\Data\View\Traits\Actions\HasQueryDataViewAction;

abstract class BaseIndexResourceAction
{
    use HasQueryDataViewAction;
    protected string $resource;
   
    protected function getDataClass()
    {        
        $resource = $this->resource;
        return $resource::listData();
    }
}