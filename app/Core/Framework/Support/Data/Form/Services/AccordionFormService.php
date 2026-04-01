<?php

namespace App\Core\Framework\Support\Data\Form\Services;


use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;

class AccordionFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $accordions = [];

        foreach ($metadata['properties'] as $propMeta) {
            $accMeta = $propMeta['secondary']['accordion'] ?? null;
            $inst = $propMeta['primary'];

            if ($accMeta && $inst) {
                $accordionName = $accMeta['name'];
                $accordionIcon = $accMeta['icon'];

                if (!isset($accordions[$accordionName])) {
                    $accordions[$accordionName] = [
                        'title' => $accordionName,
                        'icon' => $accordionIcon,
                        'fields' => []
                    ];
                }

                $accordions[$accordionName]['fields'][] = $this->resolveField($propMeta, $dataClass, $inputData);
            }
        }

        return array_values($accordions);
    }
}