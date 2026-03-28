<?php

namespace App\Features\Media\Providers;

use App\Core\Framework\Data\NavigationItemData;
use App\Core\Framework\Support\Managers\LayoutManager;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot(LayoutManager $layout): void
    {

        $modulePath = __DIR__ . '/..';

        if (is_dir($modulePath . '/Resources/views')) {
            $this->loadViewsFrom($modulePath . '/Resources/views', 'media');
        }
        if (is_dir($modulePath . '/Domain/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Domain/Database/Migrations');
        }

        $layout->addSecondary(new NavigationItemData(
            label: 'Media',
            route: 'admin.media.index',
            icon: 'images',
            order: 98,
        ));
    }
}