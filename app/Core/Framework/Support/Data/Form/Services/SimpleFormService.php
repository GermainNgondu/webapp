<?php

namespace App\Core\Framework\Support\Data\Form\Services;

use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;

class SimpleFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $fields = [];

        foreach ($metadata['properties'] as $propMeta) {
            
            // 1. On vérifie s'il y a une section sur cette propriété
            if (isset($propMeta['secondary']['section'])) {
                $sInst = $propMeta['secondary']['section'];
                $fields[] = [
                    'type' => 'section_header',
                    'title' => $sInst['title'],
                    'description' => $sInst['description'],
                    'icon' => $sInst['icon'],
                ];
            }

            // 2. On résout le champ normalement
            $fieldData = $this->resolveField($propMeta, $dataClass, $inputData);
            if ($fieldData) {
                $fields[] = $fieldData;
            }
        }

        return $fields;
    }
}