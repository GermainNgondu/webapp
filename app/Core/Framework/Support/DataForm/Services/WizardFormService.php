<?php

namespace App\Core\Framework\Support\DataForm\Services;

use App\Core\Framework\Support\DataForm\Attributes\Step;
use App\Core\Framework\Support\DataForm\Contracts\BaseFormService;
use ReflectionClass;

class WizardFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $reflection = new ReflectionClass($dataClass);
        $steps = [];

        foreach ($reflection->getProperties() as $property) 
        {
            $stepAttr = $property->getAttributes(Step::class)[0] ?? null;
            
            if ($stepAttr) 
            {
                $instance = $stepAttr->newInstance();
                $stepName = $instance->name;

                if (!isset($steps[$stepName])) 
                {
                    $steps[$stepName] = [
                        'title' => $stepName,
                        'icon' => $instance->icon,
                        'description' => $instance->description,
                        'action'=> $instance->action,
                        'fields' => []
                    ];
                }

                $steps[$stepName]['fields'][] = $this->resolveField($property, $inputData);
            }
        }

        return array_values($steps);
    }
}