<?php

namespace App\Core\Framework\Support\DataForm\Services;

use ReflectionClass;
use App\Core\Framework\Support\DataForm\Attributes\{Tab, Field, Repeater};
use Illuminate\Support\Str;

class TabsFormService
{
    public static function init()
    {
        return new self();
    }

    /**
     * Construit la structure des onglets pour l'affichage
     */
    public function build(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $tabs = [];
        $forbiddenTabs = [];
        $user = auth()->user();

        foreach ($reflection->getProperties() as $property) {
            $tabAttr = $property->getAttributes(Tab::class)[0] ?? null;
            if (!$tabAttr) continue;

            $tabInst = $tabAttr->newInstance();
            $tabName = $tabInst->name;

            // Sécurité : Si l'onglet est déjà dans la liste noire, on passe direct
            if (in_array($tabName, $forbiddenTabs)) {
                continue;
            }
            // Vérification : Si l'attribut définit une permission, on teste
            if ($tabInst->permission && (!$user || !$user->can($tabInst->permission))) {
                $forbiddenTabs[] = $tabName; // On blackliste l'onglet pour les prochains champs
                unset($tabs[$tabName]);      // Au cas où un champ sans permission l'aurait créé avant
                continue;
            }

            // Mode Lecture Seule au niveau de l'onglet
            $isTabReadOnly = $tabInst->editPermission && (!$user || !$user->can($tabInst->editPermission));

            if (!isset($tabs[$tabName])) {
                $tabs[$tabName] = [
                    'meta' => [
                        'name' => $tabName,
                        'slug' => Str::slug($tabName),
                        'icon' => $tabInst->icon,
                        'badge' => $tabInst->badge,
                    ],
                    'fields' => []
                ];
            }

            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
            $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;
            $inst = $fieldAttr?->newInstance() ?? $repeaterAttr?->newInstance();

            if ($inst) {
                // 3. Sécurité : Droit de VOIR le champ
                if ($inst->permission && (!$user || !$user->can($inst->permission))) continue;

                // 4. Mode Lecture Seule au niveau du champ
                $isFieldReadOnly = $isTabReadOnly || ($inst->editPermission && (!$user || !$user->can($inst->editPermission)));

                $fieldData = [
                    'name' => $property->getName(),
                    'label' => $inst->label,
                    'colSpan' => $inst->colSpan,
                    'readonly' => $isFieldReadOnly,
                    'required' => ($inst instanceof Field && $inst->required) || !$property->getType()?->allowsNull(),
                    'multiple' => ($inst instanceof Field) ? $inst->multiple : false,
                ];

                if ($repeaterAttr) {
                    $fieldData = array_merge($fieldData, [
                        'type' => 'repeater',
                        'dataClass' => $inst->dataClass,
                        'addLabel' => $inst->addLabel,
                        'titleKey' => $inst->titleKey,
                        'schema' => $this->getSchemaFromDataClass($inst->dataClass),
                    ]);
                } else {
                    $fieldData = array_merge($fieldData, [
                        'type' => $inst->type,
                        'options' => $inst->options,
                    ]);
                }

                $tabs[$tabName]['fields'][] = $fieldData;
            }
        }
        return $tabs;
    }

    /**
     * Nettoyage récursif des données selon les permissions (Sécurité finale)
     */
    public function secureData(string $dataClass, array $inputData): array
    {
        $reflection = new ReflectionClass($dataClass);
        $user = auth()->user();
        $safeData = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            
            // On récupère les attributs de manière indépendante
            $tabAttr = $property->getAttributes(Tab::class)[0] ?? null;
            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
            $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;

            // 1. GESTION DE L'ID : Crucial pour les clés stables et le mapping d'erreurs
            if ($name === 'id' && isset($inputData['id'])) {
                $safeData['id'] = $inputData['id'];
                continue;
            }

            // 2. FILTRE : Si ce n'est ni un champ ni un repeater, on ignore la propriété
            if (!$fieldAttr && !$repeaterAttr) {
                continue;
            }
            
            $tabInst = $tabAttr?->newInstance();
            $inst = $fieldAttr?->newInstance() ?? $repeaterAttr?->newInstance();

            // 3. SÉCURITÉ : VÉRIFICATION DES PERMISSIONS
            // On vérifie l'onglet seulement s'il existe (cas du niveau parent)
            if ($tabInst) {
                if ($tabInst->permission && (!$user || !$user->can($tabInst->permission))) continue;
                if ($tabInst->editPermission && (!$user || !$user->can($tabInst->editPermission))) continue;
            }

            // On vérifie systématiquement le champ ou le repeater
            if ($inst->permission && (!$user || !$user->can($inst->permission))) continue;
            if ($inst->editPermission && (!$user || !$user->can($inst->editPermission))) continue;

            // 4. TRAITEMENT DES DONNÉES
            if (!isset($inputData[$name])) continue;

            if ($repeaterAttr) {
                $safeData[$name] = [];
                $subClass = $inst->dataClass; // Récupéré de l'attribut Repeater

                foreach ($inputData[$name] as $key => $row) {
                    // MAGIE : On préserve la clé ($key) qui est l'ID stable 'temp_...'
                    $safeData[$name][$key] = $this->secureData($subClass, $row);
                }
            } else {
                // Pour les champs simples (text, select, multiple, etc.)
                $safeData[$name] = $inputData[$name];
            }
        }

        return $safeData;
    }

    protected function getSchemaFromDataClass(string $dataClass): array
    {
        $subReflection = new ReflectionClass($dataClass);
        $schema = [];
        $user = auth()->user();

        foreach ($subReflection->getProperties() as $prop) {
            $attr = $prop->getAttributes(Field::class)[0] ?? null;
            if ($attr) {
                $inst = $attr->newInstance();
                
                if ($inst->permission && (!$user || !$user->can($inst->permission))) {
                    continue;
                }

                $schema[] = [
                    'name'     => $prop->getName(),
                    'label'    => $inst->label,
                    'type'     => $inst->type,
                    'colSpan'  => $inst->colSpan,
                    'options'  => $inst->options,
                    'multiple' => $inst->multiple,
                    'required' => $inst->required || !$prop->getType()?->allowsNull(),
                ];
            }
        }
        return $schema;
    }
}