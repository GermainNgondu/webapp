<?php

namespace App\Core\Framework\Support\Data\Form\Services;

use App\Core\Framework\Support\Data\Form\Attributes\Tab;
use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;
use Illuminate\Support\Str;
use ReflectionClass;

class TabsFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $reflection = new ReflectionClass($dataClass);
        $tabs = [];
        $forbiddenTabs = [];
        $user = auth()->user();

        foreach ($reflection->getProperties() as $property) 
        {
            $tabAttr = $property->getAttributes(Tab::class)[0] ?? null;
            if (!$tabAttr) continue;

            $tabInst = $tabAttr->newInstance();
            $tabName = $tabInst->name;
            
            if (in_array($tabName, $forbiddenTabs)) continue;

            // SÉCURITÉ : Droit de voir l'onglet
            if ($tabInst->permission && (!$user || !$user->can($tabInst->permission))) {
                $forbiddenTabs[] = $tabName;
                unset($tabs[$tabName]);
                continue;
            }

            // CALCUL DU READONLY DE L'ONGLET
            $isTabReadOnly = $tabInst->editPermission && (!$user || !$user->can($tabInst->editPermission));

            if (!isset($tabs[$tabName])) {
                $tabs[$tabName] = [
                    'meta' => [
                        'name' => $tabName,
                        'slug' => Str::slug($tabName),
                        'icon' => $tabInst->icon,
                    ],
                    'fields' => []
                ];
            }

            // On passe le statut readonly de l'onglet au champ
            $fieldData = $this->resolveField($property, $inputData, $isTabReadOnly);
            
            if ($fieldData) {

                $tabs[$tabName]['fields'][] = $fieldData;
            }
        }

        return $tabs;
    }
}