<?php

namespace App\Core\Framework\Support\Data\Form\Services;

use App\Core\Framework\Support\Data\Form\Contracts\BaseFormService;

class TabsFormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $tabs = [];
        $forbiddenTabs = [];
        $user = auth()->user();

        foreach ($metadata['properties'] as $propMeta) 
        {
            $tabMeta = $propMeta['secondary']['tab'] ?? null;
            if (!$tabMeta) continue;

            $tabName = $tabMeta['name'];
            
            if (in_array($tabName, $forbiddenTabs)) continue;

            // SÉCURITÉ : Droit de voir l'onglet
            if ($tabMeta['permission'] && (!$user || !$user->can($tabMeta['permission']))) {
                $forbiddenTabs[] = $tabName;
                unset($tabs[$tabName]);
                continue;
            }

            // CALCUL DU READONLY DE L'ONGLET
            $isTabReadOnly = $tabMeta['editPermission'] && (!$user || !$user->can($tabMeta['editPermission']));

            if (!isset($tabs[$tabName])) {
                $tabs[$tabName] = [
                    'meta' => [
                        'name' => $tabName,
                        'slug' => \Illuminate\Support\Str::slug($tabName),
                        'icon' => $tabMeta['icon'],
                    ],
                    'fields' => []
                ];
            }

            // On passe le statut readonly de l'onglet au champ
            $fieldData = $this->resolveField($propMeta, $dataClass, $inputData, $isTabReadOnly);
            
            if ($fieldData) {
                $tabs[$tabName]['fields'][] = $fieldData;
            }
        }

        return $tabs;
    }
}