<?php

namespace App\Features\Media\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class CleanTemporaryFilesAction
{
    use AsAction;

    public function handle()
    {
        $files = Storage::files('private/livewire-tmp');

        foreach ($files as $file)
        {
            Storage::delete($file);
        }
    }
}