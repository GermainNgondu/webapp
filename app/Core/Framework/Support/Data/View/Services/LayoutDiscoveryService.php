<?php

namespace App\Core\Framework\Support\Data\View\Services;

use App\Core\Framework\Support\Data\View\Attributes\{ Column, Filter, Grid, DataAction, DefaultSort, Detail, KanbanGroup, MapLocation, CalendarDate };
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class LayoutDiscoveryService
{
    // Cache statique pour éviter de recalculer durant la même requête HTTP
    protected static array $registry = [];

    /**
     * Analyse la classe de données en un seul passage et met le résultat en cache.
     */
    public static function resolve(string $dataClass): array
    {
        // 1. Retour immédiat si déjà résolu dans la requête courante (RAM)
        if (isset(self::$registry[$dataClass])) {
            return self::$registry[$dataClass];
        }

        $cacheKey = "dataview_discovery_v3_" . md5($dataClass);

        // 2. En local, on ignore le cache pour permettre le développement fluide des DTOs
        if (App::isLocal()) {
            Cache::forget($cacheKey);
        }

        self::$registry[$dataClass] = Cache::remember($cacheKey, now()->addDay(), function () use ($dataClass) {
            if (!class_exists($dataClass)) {
                return self::emptySchema();
            }

            $reflection = new ReflectionClass($dataClass);
            $schema = self::emptySchema();

            // --- ANALYSE DES ATTRIBUTS DE CLASSE (Actions & Tri) ---
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

            // --- ANALYSE DES PROPRIÉTÉS (Passage Unique) ---
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
                                'field' => $propName, 
                                'label' => $inst->label, 
                                'component' => $inst->component, 
                                'order' => $inst->order,
                                'colSpan'=> $inst->colSpan,
                                'inline'=> $inst->inline
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
     * Structure de base pour éviter les erreurs "Undefined index"
     */
    protected static function emptySchema(): array
    {
        return [
            'columns'  => [],
            'filters'  => [],
            'grid'     => [],
            'detail'   => [],
            'actions'  => [],
            'sort'     => null,
            'kanban'   => ['field' => 'status', 'options' => []],
            'map'      => ['lat' => 'lat', 'lng' => 'lng', 'label' => 'id'],
            'calendar' => ['start' => 'created_at', 'end' => null, 'label' => 'id'],
        ];
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
    public static function getColumnsSchema(string $class): array { return self::resolve($class)['columns']; }
    public static function getGridSchema(string $class): array { return self::resolve($class)['grid']; }
    public static function getDetailSchema(string $class): array { return self::resolve($class)['detail']; }
    public static function getCalendarConfig(string $class): array { return self::resolve($class)['calendar']; }
    public static function getKanbanConfig(string $class): ?array { return self::resolve($class)['kanban']; }
    public static function getMapConfig(string $class): array { return self::resolve($class)['map']; }

    public static function getFilters(string $class): array { return self::resolve($class)['filters']; }
    public static function getActions(string $class): array { return self::resolve($class)['actions']; }
    public static function getDefaultSort(string $class): ?string { return self::resolve($class)['sort']; }
 
    /**
     * Vide le cache
     */
    public static function flush(string $class): void
    {
        Cache::forget("dataview_discovery_v3_" . md5($class));
        unset(self::$registry[$class]);
    }
}