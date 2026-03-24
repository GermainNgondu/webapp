<?php

namespace App\Core\Installer\Http\Actions;

use App\Core\Framework\Support\DataView\Contracts\BaseDataViewAction;
use App\Core\Installer\Data\ClientData;
use App\Models\Client;
use Lorisleiva\Actions\Concerns\AsAction;


class GetClientDataAction extends BaseDataViewAction
{
    use AsAction;

    protected function getModel(): string { return Client::class; }
    protected function getDataClass(): string { return ClientData::class; }
}