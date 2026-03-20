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
        $user = auth()->user();

        foreach ($reflection->getProperties() as $property) {
            $stepAttr = $property->getAttributes(Step::class)[0] ?? null;
            if (!$stepAttr) continue;

            $stepInst = $stepAttr->newInstance();
            $stepName = $stepInst->name;

            if ($stepInst->permission && (!$user || !$user->can($stepInst->permission))) continue;

            if (!isset($steps[$stepName])) {
                $steps[$stepName] = [
                    'meta' => [
                        'name' => $stepName,
                        'description' => $stepInst->description,
                        'icon' => $stepInst->icon,
                    ],
                    'fields' => [],
                    'validation_fields' => [] // Liste des noms de champs pour la validation
                ];
            }

            $fieldData = $this->resolveField($property, $inputData);
            
            if ($fieldData) {
                $steps[$stepName]['fields'][] = $fieldData;
                // On stocke le chemin complet pour Livewire (form.nom_du_champ)
                $steps[$stepName]['validation_fields'][] = 'form.' . $property->getName();
            }
        }

        return array_values($steps);
    }
}