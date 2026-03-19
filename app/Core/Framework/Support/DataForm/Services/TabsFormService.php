<?php

namespace App\Core\Framework\Support\DataForm\Services;

use ReflectionClass;
use App\Core\Framework\Support\DataForm\Attributes\{Tab, Field, Repeater, LazySelect, VisibleIf};
use Illuminate\Support\Str;

class TabsFormService
{
    public static function init()
    {
        return new self();
    }

    /**
     * Construit la structure des onglets pour l'affichage (Build)
     */
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

            // Sécurité : Permission au niveau de l'onglet
            if ($tabInst->permission && (!$user || !$user->can($tabInst->permission))) {
                $forbiddenTabs[] = $tabName;
                unset($tabs[$tabName]);
                continue;
            }

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

            $name = $property->getName();
            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
            $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;
            $lazyAttr = $property->getAttributes(LazySelect::class)[0] ?? null;
            $visibleAttr = $property->getAttributes(VisibleIf::class)[0] ?? null;

            $inst = $fieldAttr?->newInstance() ?? $repeaterAttr?->newInstance() ?? $lazyAttr?->newInstance();

            if ($inst) {
                if ($inst->permission && (!$user || !$user->can($inst->permission))) continue;

                $isFieldReadOnly = $isTabReadOnly || ($inst->editPermission && (!$user || !$user->can($inst->editPermission)));

                $fieldData = [
                    'name' => $name,
                    'label' => $inst->label,
                    'colSpan' => $inst->colSpan,
                    'readonly' => $isFieldReadOnly,
                    'required' => ($inst instanceof Field && $inst->required) || !$property->getType()?->allowsNull(),
                    'multiple' => method_exists($inst, 'multiple') ? $inst->multiple : false,
                ];

                if ($visibleAttr) {
                    $vInst = $visibleAttr->newInstance();
                    $fieldData['visibleIf'] = [
                        'field' => $vInst->field,
                        'value' => $vInst->value,
                    ];
                }

                if ($repeaterAttr) {
                    $fieldData = array_merge($fieldData, [
                        'type' => 'repeater',
                        'dataClass' => $inst->dataClass,
                        'addLabel' => $inst->addLabel,
                        'titleKey' => $inst->titleKey,
                        'schema' => $this->getSchemaFromDataClass($inst->dataClass, $inputData[$name] ?? []),
                    ]);
                } elseif ($lazyAttr) {
                    // Configuration spécifique pour le Lazy Loading
                    $fieldData = array_merge($fieldData, [
                        'type' => 'select',
                        'options' => $this->getInitialLazyOptions($inst, $inputData[$name] ?? null),
                        'lazy' => [
                            'model' => str_replace('\\', '\\\\', $inst->model),
                            'labelColumn' => $inst->labelColumn,
                            'valueColumn' => $inst->valueColumn,
                        ]
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
     * Nettoyage récursif des données (Sécurité finale avant validation)
     */
    public function secureData(string $dataClass, array $inputData): array
    {
        $reflection = new ReflectionClass($dataClass);
        $user = auth()->user();
        $safeData = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            
            // On récupère tous les attributs possibles
            $tabAttr = $property->getAttributes(Tab::class)[0] ?? null;
            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
            $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;
            $lazyAttr = $property->getAttributes(LazySelect::class)[0] ?? null;

            // 1. Conservation de l'ID (crucial pour les repeaters et IDs temporaires)
            if ($name === 'id' && isset($inputData['id'])) {
                $safeData['id'] = $inputData['id'];
                continue;
            }

            // Si ce n'est pas un champ géré par le formulaire, on l'ignore
            if (!$fieldAttr && !$repeaterAttr && !$lazyAttr) continue;
            
            $tabInst = $tabAttr?->newInstance();
            $fieldInst = $fieldAttr?->newInstance() ?? $repeaterAttr?->newInstance() ?? $lazyAttr?->newInstance();

            // 2. Sécurité : On vérifie les permissions de l'onglet si présent
            if ($tabInst) {
                if ($tabInst->permission && (!$user || !$user->can($tabInst->permission))) continue;
                if ($tabInst->editPermission && (!$user || !$user->can($tabInst->editPermission))) continue;
            }

            // 3. Sécurité : On vérifie les permissions du champ
            if ($fieldInst->permission && (!$user || !$user->can($fieldInst->permission))) continue;
            if ($fieldInst->editPermission && (!$user || !$user->can($fieldInst->editPermission))) continue;

            if (!isset($inputData[$name])) continue;

            // 4. Traitement récursif pour les repeaters
            if ($repeaterAttr) {
                $safeData[$name] = [];
                $subClass = $repeaterAttr->newInstance()->dataClass;

                foreach ($inputData[$name] as $key => $row) {
                    // MAGIE : On préserve la clé ($key) 'temp_...' pour le mapping d'erreurs
                    $safeData[$name][$key] = $this->secureData($subClass, $row);
                }
            } else {
                // Pour les champs simples ou LazySelect
                $safeData[$name] = $inputData[$name];
            }
        }

        return $safeData;
    }

    /**
     * Génère le schéma pour les lignes d'un repeater
     */
    protected function getSchemaFromDataClass(string $dataClass, array $repeaterRows = []): array
    {
        $subReflection = new ReflectionClass($dataClass);
        $schema = [];
        $user = auth()->user();

        foreach ($subReflection->getProperties() as $prop) {

            $fieldAttr = $prop->getAttributes(Field::class)[0] ?? null;
            $lazyAttr = $prop->getAttributes(LazySelect::class)[0] ?? null;
            $visibleAttr = $prop->getAttributes(VisibleIf::class)[0] ?? null;

            if ($fieldAttr || $lazyAttr) {
                $inst = $fieldAttr ? $fieldAttr->newInstance() : $lazyAttr->newInstance();
                $name = $prop->getName();

                if ($inst->permission && (!$user || !$user->can($inst->permission))) {
                    continue;
                }

                $fieldConfig = [
                    'name'     => $name,
                    'label'    => $inst->label,
                    'type'     => $lazyAttr ? 'select' : $inst->type,
                    'colSpan'  => $inst->colSpan,
                    'multiple' => $inst->multiple,
                    'required' => (property_exists($inst, 'required') && $inst->required) || !$prop->getType()?->allowsNull(),
                ];
                
                if ($visibleAttr) 
                {
                    $vInst = $visibleAttr->newInstance();
                    $fieldConfig['visibleIf'] = [
                        'field' => $vInst->field,
                        'value' => $vInst->value,
                    ];
                }

                if ($lazyAttr) {

                    $existingIds = collect($repeaterRows)->pluck($name)->flatten()->filter()->unique()->toArray();
                    
                    $fieldConfig['options'] = $inst->model::whereIn($inst->valueColumn, $existingIds)
                        ->pluck($inst->labelColumn, $inst->valueColumn)
                        ->toArray();

                    $fieldConfig['lazy'] = [
                        'model' => str_replace('\\', '\\\\', $inst->model),
                        'labelColumn' => $inst->labelColumn,
                        'valueColumn' => $inst->valueColumn,
                    ];
                } else {
                    $fieldConfig['options'] = $inst->options;
                }

                $schema[] = $fieldConfig;
            }
        }
        return $schema;
    }

    /**
     * Charge uniquement les options déjà sélectionnées pour l'affichage initial du LazySelect
     */
    protected function getInitialLazyOptions($attr, $value): array 
    {
        if (empty($value)) return [];
        
        $values = is_array($value) ? $value : [$value];
        
        // On récupère uniquement les libellés des IDs déjà présents dans la donnée
        return $attr->model::whereIn($attr->valueColumn, $values)
            ->pluck($attr->labelColumn, $attr->valueColumn)
            ->toArray();
    }
}