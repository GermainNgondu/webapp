<?php

namespace App\Core\Framework\Support\Data\Form\Services;


use App\Core\Framework\Support\Data\Form\Attributes\{Field, Accordion};
use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;
use ReflectionClass;

class AccordionFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $reflection = new ReflectionClass($dataClass);
        $accordions = [];

        foreach ($reflection->getProperties() as $property) {
            $accordionAttr = $property->getAttributes(Accordion::class)[0] ?? null;
            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;

            if ($accordionAttr && $fieldAttr) {
                $accordionName = $accordionAttr->newInstance()->name;
                $accordionIcon = $accordionAttr->newInstance()->icon;

                // On groupe les champs par nom d'accordéon
                if (!isset($accordions[$accordionName])) {
                    $accordions[$accordionName] = [
                        'title' => $accordionName,
                        'icon' => $accordionIcon,
                        'fields' => []
                    ];
                }

                $accordions[$accordionName]['fields'][] = $this->resolveField($property, $inputData);
            }
        }

        return array_values($accordions);
    }
}