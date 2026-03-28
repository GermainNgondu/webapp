<?php

namespace App\Core\Framework\Support\Data\Form\Contracts;

use ReflectionClass;
use ReflectionProperty;
use App\Core\Framework\Support\Data\Form\Attributes\{
    Field, 
    Repeater, 
    VisibleIf, 
    LazySelect, 
    Section, 
    MediaPicker, 
    Blocks,
    FormConfig
};

abstract class BaseFormService
{
    abstract public function build(string $dataClass, array $inputData = []): array;

    /**
     * Sécurité d'écriture : Nettoie les données reçues.
     */
    public function secureData(string $dataClass, array $inputData): array
    {
        $reflection = new ReflectionClass($dataClass);
        $safeData = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            
            if ($name === 'id' && isset($inputData['id'])) {
                $safeData['id'] = $inputData['id'];
                continue;
            }

            $inst = $this->getPrimaryAttribute($property);
            if (!$inst) continue;

            // SÉCURITÉ : Droit de voir ET d'éditer
            if (!$this->canView($inst) || !$this->canEdit($inst)) continue;

            if (!isset($inputData[$name])) continue;

            if ($inst instanceof Repeater) {
                $safeData[$name] = [];
                foreach (($inputData[$name] ?? []) as $key => $row) {
                    $safeData[$name][$key] = $this->secureData($inst->dataClass, $row);
                }
            } elseif ($inst instanceof Blocks) {
                $safeData[$name] = [];
                foreach (($inputData[$name] ?? []) as $key => $block) {
                    $safeData[$name][$key] = [
                        'type' => $block['type'] ?? 'unknown',
                        'class' => $block['class'] ?? null,
                        'data' => (isset($block['class']) && class_exists($block['class'])) 
                            ? $this->secureData($block['class'], $block['data'] ?? []) 
                            : ($block['data'] ?? []),
                    ];
                }
            } else {
                $safeData[$name] = $inputData[$name];
            }
        }
        return $safeData;
    }

    /**
     * Sécurité de lecture : Construit la config du champ.
     */
    protected function resolveField(ReflectionProperty $property, array $inputData = [], bool $parentReadOnly = false): ?array
    {
        $inst = $this->getPrimaryAttribute($property);
        if (!$inst) return null;

        // SÉCURITÉ D'AFFICHAGE
        if (!$this->canView($inst)) { return null; }

        $dataClass = $property->getDeclaringClass()->getName();
        $name = $property->getName();
        
        // CALCUL DU READONLY (Propagation)
        $isReadOnly = $parentReadOnly || !$this->canEdit($inst);

        $type = $inst->type ?? 'text';

        if ($inst instanceof MediaPicker) $type = 'media-picker';
        if ($inst instanceof Repeater) $type = 'repeater';
        if ($inst instanceof LazySelect) $type = 'select';
        if ($inst instanceof Blocks) $type = 'blocks';

        $data = [
            'name' => $name,
            'label' => $inst->label ?? null,
            'colSpan' => ($type === 'hidden') ? 0 : ($inst->colSpan ?? 12),
            'multiple' => $inst->multiple ?? false,
            'readonly' => $isReadOnly,
            'type' => $type,
            'description' => $inst->description ?? null,
            'required' => ($inst instanceof Field && $inst->required == true),
            'options' => $inst->options ?? [],
            'rules' => $inst->rules ?? 'nullable',
        ];
        // Résolution de type spécifique (Media, Repeater, Lazy)
        $data = array_merge($data, $this->resolveTypeSpecificData($inst, $dataClass, $name, $inputData, $isReadOnly));

        $data = array_merge($data, $this->resolveSecondaryAttributes($property));

        return $data;
    }

    protected function resolveTypeSpecificData(object $inst, string $dataClass, string $name, array $inputData, bool $isReadOnly): array
    {
        $data = [];

        if ($inst instanceof MediaPicker) {
            $data['collection'] = $inst->collection; 
            
        } elseif ($inst instanceof Repeater) {
            $data['dataClass'] = $inst->dataClass;
            $data['addLabel'] = $inst->addLabel;
            $data['titleKey'] = $inst->titleKey;
            $data['schema'] = $this->getSchemaFromDataClass($inst->dataClass, $inputData[$name] ?? [], $isReadOnly);
        } elseif ($inst instanceof LazySelect) {
            $data['options'] = $this->getInitialLazyOptions($inst, $inputData[$name] ?? null);
            $data['lazy'] = [
                'model' => str_replace('\\', '\\\\', $inst->model), 
                'labelColumn' => $inst->labelColumn, 
                'valueColumn' => $inst->valueColumn,
                'iconColumn' => $inst->iconColumn ?? null,
                'imageColumn' => $inst->imageColumn ?? null,
            ];
        } elseif ($inst instanceof Blocks) {
            $data['allowedBlocks'] = collect($inst->allowedBlocks)->map(function ($blockClass) use ($inputData, $name, $isReadOnly) {
                return [
                    'class' => $blockClass,
                    'type' => class_basename($blockClass),
                    'schema' => $this->getSchemaFromDataClass($blockClass, $inputData[$name] ?? [], $isReadOnly),
                ];
            })->toArray();
            $data['placeholder'] = $inst->placeholder ?? 'Ajouter un bloc';
        }

        return $data;
    }

    protected function resolveSecondaryAttributes(ReflectionProperty $property): array
    {
        $data = [];
        
        $sectionAttr = $this->getAttributeInstance($property, Section::class);
        if ($sectionAttr) {
            $data['section'] = ['title' => $sectionAttr->title, 'description' => $sectionAttr->description, 'icon' => $sectionAttr->icon];
        }

        $visibleAttr = $this->getAttributeInstance($property, VisibleIf::class);
        if ($visibleAttr) {
            $data['visibleIf'] = ['field' => $visibleAttr->field, 'value' => $visibleAttr->value, 'operator' => $visibleAttr->operator ?? '='];
        }

        return $data;
    }
    /**
     * Récupère l'attribut principal d'un champ
     */
    protected function getPrimaryAttribute(ReflectionProperty $property): ?object
    {
        $primaryClasses = [
            Field::class, 
            Repeater::class, 
            LazySelect::class, 
            MediaPicker::class,
            Blocks::class
        ];
        foreach ($primaryClasses as $class) {
            $inst = $this->getAttributeInstance($property, $class);
            if ($inst) return $inst;
        }
        return null;
    }

    protected function getAttributeInstance(ReflectionProperty $property, string $class): ?object
    {
        $attr = $property->getAttributes($class)[0] ?? null;
        return $attr ? $attr->newInstance() : null;
    }

    protected function canView(object $inst): bool
    {
        $user = auth()->user();
        return !isset($inst->permission) || empty($inst->permission) || ($user && $user->can($inst->permission));
    }

    protected function canEdit(object $inst): bool
    {
        $user = auth()->user();
        return !isset($inst->editPermission) || empty($inst->editPermission) || ($user && $user->can($inst->editPermission));
    }

    protected function getSchemaFromDataClass(string $dataClass, array $repeaterRows = [], bool $parentReadOnly = false): array
    {
        $subReflection = new ReflectionClass($dataClass);
        $schema = [];
        foreach ($subReflection->getProperties() as $prop) {
            $field = $this->resolveField($prop, $repeaterRows, $parentReadOnly);
            if ($field) $schema[] = $field;
        }
        return $schema;
    }

    protected function getInitialLazyOptions($attr, $value): array 
    {
        if (empty($value)) return [];
        $values = is_array($value) ? $value : [$value];

        $query = $attr->model::whereIn($attr->valueColumn, $values);
        
        $columns = [$attr->valueColumn, $attr->labelColumn];
        if ($attr->iconColumn ?? null) $columns[] = $attr->iconColumn;
        if ($attr->imageColumn ?? null) $columns[] = $attr->imageColumn;

        return $query->get($columns)->mapWithKeys(function ($item) use ($attr) {
            return [$item->{$attr->valueColumn} => [
                'label' => $item->{$attr->labelColumn},
                'icon'  => ($attr->iconColumn ?? null) ? $item->{$attr->iconColumn} : null,
                'image' => ($attr->imageColumn ?? null) ? $item->{$attr->imageColumn} : null,
            ]];
        })->toArray();
    }

    /**
     * Génère les données initiales d'un bloc à partir de son DTO.
     */
    public function getBlockDefaultData(string $blockClass): array
    {
        $reflection = new ReflectionClass($blockClass);
        $defaults = [];

        foreach ($reflection->getProperties() as $prop) {
            // On vérifie si la propriété est un champ du formulaire (a un attribut Field, MediaPicker, etc.)
            $inst = $this->getPrimaryAttribute($prop);
            if (!$inst) continue;

            // On initialise avec la valeur par défaut définie dans la classe PHP, 
            // sinon avec une valeur vide selon le type.
            if ($prop->hasDefaultValue()) {
                $defaults[$prop->getName()] = $prop->getDefaultValue();
            } else {
                // Initialisation sécurisée pour éviter les erreurs Livewire/Alpine
                $defaults[$prop->getName()] = $this->getDefaultValueForAttribute($inst);
            }
        }

        return $defaults;
    }

    /**
     * Helper pour déterminer une valeur par défaut "safe" selon l'attribut.
     */
    protected function getDefaultValueForAttribute($inst): mixed
    {
        if ($inst instanceof Repeater || $inst instanceof Blocks) {
            return [];
        }
        return null; 
    }

    /**
     * Récupère la configuration globale du formulaire (Titre, Layout, Action).
     */
    public function getFormConfig(string $dataClass): array
    {
        $reflection = new ReflectionClass($dataClass);
        $attr = $reflection->getAttributes(FormConfig::class)[0] ?? null;

        if (!$attr) {
            return [
                'title' => class_basename($dataClass),
                'layout' => 'simple',
                'saveLabel' => 'save',
                'action' => null,
                'model'=> null,
                'successMessage' => 'Opération réussie !',
                'errorMessage' => 'Une erreur est survenue lors du traitement.',
            ];
        }

        $config = $attr->newInstance();

        return [
            'title'       => $config->title,
            'description' => $config->description,
            'layout'      => $config->layout,
            'action'      => $config->action,
            'model'       => $config->model,
            'saveLabel'   => $config->saveLabel,
            'icon'        => $config->icon,
            'redirect'    => $config->redirect,
            'successMessage' => $config->successMessage,
            'errorMessage' => $config->errorMessage,
        ];
    }

    /**
     * Récupère la classe du modèle associé à la classe Data.
     * @param string $dataClass
     * @throws \Exception
     * @return string
     */
    protected function getModelClass(string $dataClass): string
    {
        $reflection = new ReflectionClass($dataClass);
        $attr = $reflection->getAttributes(FormConfig::class)[0] ?? null;
        
        if ($attr) {
            $config = $attr->newInstance();
            // Priorité 1 : L'attribut FormConfig
            if ($config->model) {
                return $config->model;
            }
        }

        // Priorité 2 : La constante MODEL dans la classe Data
        if (defined("$dataClass::MODEL")) {
            return $dataClass::MODEL;
        }

        throw new \Exception("Impossible de trouver le modèle associé à $dataClass.");
    }
}