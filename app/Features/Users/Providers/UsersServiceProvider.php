<?php

namespace App\Features\Users\Providers;

use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Managers\LayoutManager;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    public function boot(LayoutManager $layout): void
    {

        $modulePath = __DIR__ . '/..';

        if (is_dir($modulePath . '/Resources/views')) {
            $this->loadViewsFrom($modulePath . '/Resources/views', 'users');
        }
        if (is_dir($modulePath . '/Domain/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Domain/Database/Migrations');
        }

        $layout->addSecondary(new NavigationItemData(
            label: 'Users',
            route: 'admin.users.index',
            icon: 'users',
            order: 99,
        ));
    }
}