<?php

namespace App\Core\Installer\Http\Actions;

use App\Core\Installer\Data\ClientData;
use App\Models\Client;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveClient {
    use AsAction;
    public function handle(Client $client, ClientData $data) {
        $client->update($data->toArray());
    }
}