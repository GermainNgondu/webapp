<?php

namespace App\Core\Framework\Support\Data\Form\Services;

use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;

class WizardFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $steps = [];

        foreach ($metadata['properties'] as $propMeta) 
        {
            $stepMeta = $propMeta['secondary']['step'] ?? null;
            
            if ($stepMeta) 
            {
                $stepName = $stepMeta['name'];

                if (!isset($steps[$stepName])) 
                {
                    $steps[$stepName] = [
                        'title' => $stepName,
                        'icon' => $stepMeta['icon'],
                        'description' => $stepMeta['description'],
                        'action'=> $stepMeta['action'],
                        'fields' => []
                    ];
                }

                $steps[$stepName]['fields'][] = $this->resolveField($propMeta, $dataClass, $inputData);
            }
        }

        return array_values($steps);
    }
}