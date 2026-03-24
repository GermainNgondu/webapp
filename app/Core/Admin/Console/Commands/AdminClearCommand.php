<?php

namespace App\Core\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AdminClearCommand extends Command
{
    protected $signature = 'admin:clear';
    protected $description = 'Supprime le cache de configuration des modules';

    public function handle()
    {
        $cachePath = base_path('bootstrap/cache/admin_features.php');

        if (File::exists($cachePath)) {
            File::delete($cachePath);
            $this->info('✅ Le cache des modules admin a été supprimé.');
        } else {
            $this->comment('ℹ️ Aucun fichier de cache trouvé. Rien à supprimer.');
        }
    }
}