<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use ReflectionClass;
use ReflectionProperty;
use App\Core\Framework\Support\DataForm\Attributes\FormField;

trait HasDynamicForm
{
    /**
     * Extrait les métadonnées de la classe Spatie Data
     */
    public function getFormSchema(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $fields = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes = $property->getAttributes(FormField::class);
            
            if (!empty($attributes)) {
                $fields[] = [
                    'name' => $property->getName(),
                    'info' => $attributes[0]->newInstance(),
                    'type' => $property->getType()?->getName(), // Récupère le type PHP (string, int, etc.)
                ];
            }
        }

        return $fields;
    }
}