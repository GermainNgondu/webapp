<?php

namespace App\Core\Framework\Support\Data\View\Services;

use ReflectionClass;
use Illuminate\Support\Facades\Cache;
use App\Core\Framework\Support\Data\View\Attributes\{
    Column, Filter, Grid, DataAction, DefaultSort, 
    Detail, KanbanGroup, MapLocation, CalendarDate
};

class LayoutDiscovery
{
    // Cache statique pour éviter de recalculer durant la même requête HTTP
    protected static array $registry = [];

    /**
     * Analyse la classe de données en un seul passage et met le résultat en cache.
     */
    public static function resolve(string $dataClass): array
    {
        if (isset(self::$registry[$dataClass])) {
            return self::$registry[$dataClass];
        }

        // Cache persistant pour la production (clé unique par classe)
        $cacheKey = "dataview_discovery_v1_" . md5($dataClass);

        self::$registry[$dataClass] = Cache::rememberForever($cacheKey, function () use ($dataClass) {
            $reflection = new ReflectionClass($dataClass);
            
            $schema = [
                'columns'  => [],
                'filters'  => [],
                'grid'     => [],
                'detail'   => [],
                'actions'  => [],
                'sort'     => null,
                'kanban'   => null,
                'map'      => ['lat' => 'latitude', 'lng' => 'longitude', 'label' => '', 'title' => '', 'description' => ''],
                'calendar' => ['start' => 'created_at', 'end' => null, 'label' => 'title'],
            ];

            // 1. Analyse des attributs au niveau de la CLASSE (Actions & Tri)
            foreach ($reflection->getAttributes() as $attribute) {
                $name = $attribute->getName();
                $inst = $attribute->newInstance();

                if ($name === DataAction::class) {
                    $schema['actions'][] = (array) $inst;
                }
                if ($name === DefaultSort::class) {
                    $schema['sort'] = ($inst->direction === 'desc' ? '-' : '') . $inst->column;
                }
            }

            // 2. Analyse des attributs au niveau des PROPRIÉTÉS (Un seul loop)
            foreach ($reflection->getProperties() as $property) {
                $propName = $property->getName();
                
                foreach ($property->getAttributes() as $attr) {
                    $attrName = $attr->getName();
                    $inst = $attr->newInstance();

                    match ($attrName) {
                        Column::class => 
                            $schema['columns'][$propName] = (array) $inst,
                        
                        Filter::class => 
                            $schema['filters'][$propName] = self::parseFilter($inst),
                        
                        Grid::class => 
                            $schema['grid'][$inst->position] = ['field' => $propName, 'icon' => $inst->icon],
                        
                        Detail::class => 
                            $schema['detail'][$inst->section][] = [
                                'field' => $propName, 'label' => $inst->label, 
                                'component' => $inst->component, 'order' => $inst->order
                            ],
                        
                        KanbanGroup::class => 
                            $schema['kanban'] = ['field' => $propName, 'options' => $inst->options],
                        
                        MapLocation::class => 
                            $schema['map'][$inst->type] = $propName,
                        
                        CalendarDate::class => 
                            $schema['calendar'][$inst->type] = $propName,

                        default => null
                    };
                }
            }
            return $schema;
        });

        return self::$registry[$dataClass];
    }

    /**
     * Helper pour traiter les Enums dans les filtres
     */
    protected static function parseFilter($instance): array
    {
        $data = (array) $instance;
        if (is_string($instance->options) && enum_exists($instance->options)) {
            $data['options'] = collect($instance->options::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->name])
                ->toArray();
        }
        return $data;
    }

    // Méthodes de compatibilité (Proxy vers resolve())
    public static function getSchema(string $class): array { return self::resolve($class)['columns']; }
     public static function getGridSchema(string $class): array { return self::resolve($class)['grid']; }
    public static function getCalendarConfig(string $class): array { return self::resolve($class)['calendar']; }
    public static function getKanbanConfig(string $class): ?array { return self::resolve($class)['kanban']; }
    public static function getMapConfig(string $class): array { return self::resolve($class)['map']; }
    public static function getDetailSchema(string $class): array { return self::resolve($class)['detail']; }
    public static function getFilters(string $class): array { return self::resolve($class)['filters']; }
    public static function getActions(string $class): array { return self::resolve($class)['actions']; }
    public static function getDefaultSort(string $class): ?string { return self::resolve($class)['sort']; }
    
}