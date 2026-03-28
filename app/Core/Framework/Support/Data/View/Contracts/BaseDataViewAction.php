<?php

namespace App\Core\Framework\Support\Data\View\Contracts;

use App\Core\Framework\Support\Data\View\Traits\Actions\HasQueryDataViewAction;

abstract class BaseDataViewAction
{
    use HasQueryDataViewAction;
    abstract protected function getDataClass(): string;
}