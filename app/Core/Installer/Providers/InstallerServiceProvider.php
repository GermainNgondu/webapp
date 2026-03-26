<?php

namespace App\Core\Installer\Providers;

use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'installer');
    }
}