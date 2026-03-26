<?php

namespace App\Core\Admin\Support\Discovery;

use Illuminate\Support\Facades\File;

class FeatureDiscovery
{
    /**
     * Scanne les dossiers pour trouver les ServiceProviders des Features.
     */
    public static function discoverProviders(): array
    {
        $providers = [];
        $featuresPath = app_path('Features');

        if (!File::isDirectory($featuresPath)) return [];

        foreach (File::directories($featuresPath) as $modulePath) {
            $moduleName = basename($modulePath);
            $pPath = $modulePath . '/Providers';

            if (File::isDirectory($pPath)) {
                foreach (File::files($pPath) as $file) {
                    $class = "App\\Features\\{$moduleName}\\Providers\\" . $file->getBasename('.php');

                    if (class_exists($class)) {
                        $providers[] = $class;
                    }
                }
            }
        }
        return $providers;
    }

    /**
     * Scanne les dossiers pour trouver les fichiers de routes.
     */
    public static function discoverRoutes(): array
    {
        $routes = ['public' => [], 'admin' => []];
        $featuresPath = app_path('Features');

        if (!File::isDirectory($featuresPath)) return $routes;

        foreach (File::directories($featuresPath) as $modulePath) {
            $moduleName = strtolower(basename($modulePath));
           
            // On vérifie l'existence des fichiers selon ta convention
            if (File::exists($p = $modulePath . '/Http/Routes/web.php')) {
                $routes['public'][] = $p;
            }
            if (File::exists($a = $modulePath . '/Http/Routes/admin.php')) {
                $routes['admin'][$moduleName] = $a;
            }
        }
        
        return $routes;
    }
}