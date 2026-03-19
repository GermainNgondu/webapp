<?php

namespace App\Core\Framework\Support\DataForm\Services;

use ReflectionClass;
use ReflectionProperty;
use App\Core\Framework\Support\DataForm\Attributes\Field;

class FormService
{
    public static function init()
    {
        return new self();
    }

    public function getSchema(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $schema = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attribute = $property->getAttributes(Field::class)[0] ?? null;

            if ($attribute) {
                $instance = $attribute->newInstance();
                
                // Logique intelligente : on devine le type si non précisé
                $phpType = $property->getType()?->getName();
                
                $schema[] = [
                    'name' => $property->getName(),
                    'label' => $instance->label,
                    'colSpan'     => $instance->colSpan,
                    'placeholder' => $instance->placeholder,
                    'options' => $instance->options,
                    'component' => $this->guessComponent($instance, $phpType),
                    'type' => $instance->type ?? $this->guessHtmlType($phpType),
                ];
            }
        }

        return $schema;
    }

    protected function guessComponent(Field $attr, ?string $phpType): string
    {
        if ($attr->component !== 'input') return $attr->component;
        if ($phpType === 'bool') return 'checkbox';
        if (!empty($attr->options)) return 'select';
        
        return 'input';
    }

    protected function guessHtmlType(?string $phpType): string
    {
        return match($phpType) {
            'int', 'float' => 'number',
            'bool' => 'checkbox',
            default => 'text',
        };
    }


}