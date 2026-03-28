<?php

namespace App\Core\Framework\Support\Data\View\Services;

use ReflectionClass;
use App\Core\Framework\Support\Data\View\Attributes\{
    Column,
    Filter,
    Grid,
    DataAction,
    DefaultSort
};

class LayoutDiscovery
{
    public static function getSchema(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $schema = [];

        foreach ($reflection->getProperties() as $property) {
            $attr = $property->getAttributes(Column::class)[0] ?? null;
            if ($attr) {
                $instance = $attr->newInstance();
                $schema[$property->getName()] = (array) $instance;
            }
        }
        return $schema;
    }

    public static function getFilters(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $filters = [];

        foreach ($reflection->getProperties() as $property) {
            $attr = $property->getAttributes(Filter::class)[0] ?? null;
            if ($attr) {
                $instance = $attr->newInstance();
                $data = (array) $instance;
                
                // Si c'est une Enum, on extrait les cases automatiquement
                if (is_string($instance->options) && enum_exists($instance->options)) {
                    $data['options'] = collect($instance->options::cases())
                        ->mapWithKeys(fn($case) => [$case->value => $case->name])
                        ->toArray();
                }
                
                $filters[$property->getName()] = $data;
            }
        }
        return $filters;
    }

    public static function getGridSchema(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $gridConfig = [];

        foreach ($reflection->getProperties() as $property) {
            $attr = $property->getAttributes(Grid::class)[0] ?? null;
            if ($attr) {
                $instance = $attr->newInstance();
                // On indexe par position pour un accès rapide dans Blade
                $gridConfig[$instance->position] = [
                    'field' => $property->getName(),
                    'icon'  => $instance->icon,
                ];
            }
        }
        return $gridConfig;
    }

    /**
     * Extrait les actions définies au niveau de la classe Data
     */
    public static function getActions(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $actions = [];

        // On récupère tous les attributs DataAction (grâce au flag IS_REPEATABLE)
        $attributes = $reflection->getAttributes(DataAction::class);

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            
            // On transforme l'objet en tableau pour faciliter l'usage dans Blade
            $actions[] = [
                'name'     => $instance->name,
                'label'    => $instance->label,
                'icon'     => $instance->icon,
                'isGlobal' => $instance->isGlobal,
                'isBulk'   => $instance->isBulk,
                'variant'  => $instance->variant,
                'color'    => $instance->color,
                'confirm'  => $instance->confirm,
            ];
        }

        return $actions;
    }

    public static function getDefaultSort(string $dataClass): ?string
    {
        $reflection = new ReflectionClass($dataClass);
        $attr = $reflection->getAttributes(DefaultSort::class)[0] ?? null;

        if ($attr) {
            $instance = $attr->newInstance();
            return ($instance->direction === 'desc' ? '-' : '') . $instance->column;
        }

        return null;
    }
}