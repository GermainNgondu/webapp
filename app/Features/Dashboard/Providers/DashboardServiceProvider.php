<?php

namespace App\Features\Dashboard\Providers;

use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Managers\LayoutManager;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function boot(LayoutManager $layout): void
    {

        $modulePath = __DIR__ . '/..';

        if (file_exists($modulePath . '/Http/Routes/web.php')) {
            $this->loadRoutesFrom($modulePath . '/Http/Routes/web.php');
        }

        if (is_dir($modulePath . '/Resources/views')) {
            $this->loadViewsFrom($modulePath . '/Resources/views', 'dashboard');
        }
        if (is_dir($modulePath . '/Domain/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Domain/Database/Migrations');
        }

        $layout->addPrimary(new NavigationItemData(
            label: 'Dashboard',
            route: 'admin.dashboard.index',
            icon: 'layout-dashboard',
            order: 1,
        ));
    }
}