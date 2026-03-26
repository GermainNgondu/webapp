<?php

namespace App\Features\Settings\Providers;

use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Managers\LayoutManager;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(LayoutManager $layout): void
    {

        $modulePath = __DIR__ . '/..';
        
        if (is_dir($modulePath . '/Resources/views')) {
            $this->loadViewsFrom($modulePath . '/Resources/views', 'settings');
        }
        if (is_dir($modulePath . '/Domain/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Domain/Database/Migrations');
        }

        $layout->addSecondary(new NavigationItemData(
            label: 'Settings',
            route: 'admin.settings.index',
            icon: 'settings',
            order: 100,
        ));
    }
}