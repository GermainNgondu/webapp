<?php

namespace App\Core\Framework\Support\Data\Insight\Services;

use App\Core\Framework\Support\Data\Insight\Attributes\{ Chart, Metric, Trend, Card, Activity };
use App\Core\Framework\Support\Data\Insight\Manager\InsightManager;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionProperty;

class InsightDiscoveryService
{
    /**
     * Découvre la configuration des insights pour une classe donnée.
     * Utilise le cache si disponible.
     */
    public static function discover(string $className): array
    {
        if (app()->environment('production')) {
            $cachePath = self::getCachePath($className);
            if (File::exists($cachePath)) {
                return require $cachePath;
            }
        }

        return self::performDiscovery($className);
    }

    /**
     * Analyse la classe via la Reflection API pour extraire les widgets.
     */
    public static function performDiscovery(string $className): array
    {
        $reflection = new ReflectionClass($className);
        $widgets = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes = $property->getAttributes();

            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();

                if ($instance instanceof Metric || 
                    $instance instanceof Chart || 
                    $instance instanceof Trend || 
                    $instance instanceof Card ||
                    $instance instanceof Activity) {
                    $widgets[] = [
                        'type' => strtolower(class_basename($instance)),
                        'property' => $property->getName(),
                        'action' => $instance->action ?? null,
                        'config' => (array) $instance,
                    ];
                }
            }
        }

        return $widgets;
    }

    /**
     * Génère les fichiers de cache pour toutes les classes d'insights.
     * Appelé par la commande AdminCacheCommand.
     */
    public static function cacheAll(array $classNames): void
    {
        foreach ($classNames as $className) {
            $config = self::performDiscovery($className);
            $path = self::getCachePath($className);

            File::ensureDirectoryExists(dirname($path));
            
            $content = '<?php return ' . var_export($config, true) . ';';
            File::put($path, $content);
        }
    }

    /**
     * Supprime les fichiers de cache.
     */
    public static function clearCache(): void
    {
        File::cleanDirectory(base_path('bootstrap/cache/insights'));
    }

    /**
     * Détermine le chemin unique du fichier de cache pour une classe.
     */
    protected static function getCachePath(string $className): string
    {
        $hash = md5($className);
        return base_path("bootstrap/cache/insights/insight_{$hash}.php");
    }

    /**
     * Récupère tous les widgets disponibles depuis toutes les classes d'insights.
     */
    public static function getAllAvailableInsights(): array
    {
        $manager = app(InsightManager::class);
        $widgets = [];

        foreach ($manager->getDataClasses() as $className) {
            $widgets = array_merge($widgets, self::performDiscovery($className));
        }

        return $widgets;
    }
}