<?php

namespace App\Core\Framework\Support\DataForm\Services;

use App\Core\Framework\Support\DataForm\Contracts\BaseFormService;
use App\Core\Framework\Support\DataForm\Attributes\Section;
use ReflectionClass;

class SimpleFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $reflection = new ReflectionClass($dataClass);
        $fields = [];

        foreach ($reflection->getProperties() as $property) {
            
            // 1. On vérifie s'il y a une section sur cette propriété
            $sectionAttr = $property->getAttributes(Section::class)[0] ?? null;
            if ($sectionAttr) {
                $sInst = $sectionAttr->newInstance();
                $fields[] = [
                    'type' => 'section_header',
                    'title' => $sInst->title,
                    'description' => $sInst->description,
                    'icon' => $sInst->icon,
                ];
            }

            // 2. On résout le champ normalement
            $fieldData = $this->resolveField($property, $inputData);
            if ($fieldData) {
                $fields[] = $fieldData;
            }
        }

        return $fields;
    }
}