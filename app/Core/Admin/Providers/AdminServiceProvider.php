<?php

namespace App\Core\Admin\Providers;

use App\Core\Admin\Console\Commands\{AdminCacheCommand, AdminClearCommand};
use App\Core\Admin\Support\Discovery\FeatureDiscovery;
use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Managers\LayoutManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    protected ?array $cache = null;

    public function register(): void
    {
        $this->app->singleton(LayoutManager::class);

        if ($this->app->environment('prod')) {
            $this->loadCache();
        }

        // Si le cache existe, on l'utilise. Sinon, on scanne en direct (Dev mode)
        $providers = FeatureDiscovery::discoverProviders();

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(LayoutManager $layout): void
    {
        $this->mapRoutes();
        $this->loadRoutesFrom(__DIR__.'/../Http/Routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'admin');
        
        // Enregistrement de la commande
        if ($this->app->runningInConsole()) {
            $this->commands([
                AdminCacheCommand::class,
                AdminClearCommand::class,
            ]);
        }

         $layout->addPrimary(new NavigationItemData(
            label: 'Dashboard',
            route: 'dashboard',
            icon: 'layout-dashboard',
            order: 1,
        ));
    }

    protected function loadCache(): void
    {
        $cachePath = base_path('bootstrap/cache/admin_features.php');
        if (File::exists($cachePath)) {
            $this->cache = require $cachePath;
        }
    }

    protected function mapRoutes(): void
    {
        $routes = ($this->app->environment('prod') && $this->cache) 
            ? $this->cache['routes'] 
            : FeatureDiscovery::discoverRoutes();

        // Routes Publiques
        foreach ($routes['public'] as $path) {
            Route::middleware('web')->group($path);
        }

        // Routes Admin centralisées
        foreach ($routes['admin'] as $moduleName => $path) {
            Route::middleware(['web', 'auth'])
                ->prefix('admin')
                ->name("admin.{$moduleName}.")
                ->group($path);
        }
    }
}