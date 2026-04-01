<?php

namespace App\Core\Framework\Providers;

use App\Core\Framework\Support\Data\Insight\Manager\InsightManager;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(InsightManager::class, fn() => new InsightManager());
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'core');   
    }
}