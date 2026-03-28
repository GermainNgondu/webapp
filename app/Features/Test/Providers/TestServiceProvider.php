<?php

namespace App\Features\Test\Providers;

use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Support\Managers\LayoutManager;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot(LayoutManager $layout): void
    {

        $modulePath = __DIR__ . '/..';

        if (is_dir($modulePath . '/Resources/views')) {
            $this->loadViewsFrom($modulePath . '/Resources/views', 'test');
        }
        if (is_dir($modulePath . '/Domain/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Domain/Database/Migrations');
        }

        $layout->addSecondary(new NavigationItemData(
            label: 'Test',
            route: 'admin.test.index',
            icon: 'bug',
            order: 1,
        ));
    }
}