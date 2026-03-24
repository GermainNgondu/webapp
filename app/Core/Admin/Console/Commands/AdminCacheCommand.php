<?php

namespace App\Core\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Core\Admin\Support\Discovery\FeatureDiscovery;

class AdminCacheCommand extends Command
{
    protected $signature = 'admin:cache';
    protected $description = 'Cache la configuration des modules (Providers et Routes)';

    public function handle()
    {
        $this->info('🚀 Scanning features for caching...');

        $data = [
            'providers' => FeatureDiscovery::discoverProviders(),
            'routes'    => FeatureDiscovery::discoverRoutes(),
        ];

        $cachePath = base_path('bootstrap/cache/admin_features.php');
        
        // Exportation en PHP pur pour une lecture instantanée
        $content = '<?php return ' . var_export($data, true) . ';';
        File::put($cachePath, $content);

        $this->info('✅ Admin cache created successfully in bootstrap/cache/admin_features.php');
    }
}