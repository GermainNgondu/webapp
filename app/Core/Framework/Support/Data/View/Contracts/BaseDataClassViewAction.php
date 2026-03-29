<?php

namespace App\Core\Framework\Support\Data\View\Contracts;

use App\Core\Framework\Support\Data\View\Traits\Actions\HasQueryDataViewAction;

abstract class BaseDataClassViewAction
{
    use HasQueryDataViewAction;
    abstract protected function getDataClass(): string;
}