<?php

namespace App\Core\Framework\Support\DataForm\Contracts;

use ReflectionClass;
use App\Core\Framework\Support\DataForm\Attributes\{Field, Repeater, VisibleIf, LazySelect, Section};

abstract class BaseFormService
{
    abstract public function build(string $dataClass, array $inputData = []): array;

    /**
     * Sécurité d'écriture : Nettoie les données reçues.
     */
    public function secureData(string $dataClass, array $inputData): array
    {
        $reflection = new ReflectionClass($dataClass);
        $user = auth()->user();
        $safeData = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            
            if ($name === 'id' && isset($inputData['id'])) {
                $safeData['id'] = $inputData['id'];
                continue;
            }

            $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
            $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;
            $lazyAttr = $property->getAttributes(LazySelect::class)[0] ?? null;

            if (!$fieldAttr && !$repeaterAttr && !$lazyAttr) continue;

            $inst = ($fieldAttr ?? $repeaterAttr ?? $lazyAttr)->newInstance();

            // SÉCURITÉ : Droit de voir ET d'éditer
            if ($inst->permission && (!$user || !$user->can($inst->permission))) continue;
            if ($inst->editPermission && (!$user || !$user->can($inst->editPermission))) continue;

            if (!isset($inputData[$name])) continue;

            if ($repeaterAttr) {
                $safeData[$name] = [];
                foreach ($inputData[$name] as $key => $row) {
                    $safeData[$name][$key] = $this->secureData($inst->dataClass, $row);
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
    protected function resolveField($property, array $inputData = [], bool $parentReadOnly = false): ?array
    {
        $fieldAttr = $property->getAttributes(Field::class)[0] ?? null;
        $repeaterAttr = $property->getAttributes(Repeater::class)[0] ?? null;
        $lazyAttr = $property->getAttributes(LazySelect::class)[0] ?? null;
        $visibleAttr = $property->getAttributes(VisibleIf::class)[0] ?? null;

        $inst = ($fieldAttr ?? $repeaterAttr ?? $lazyAttr)?->newInstance();
        if (!$inst) return null;

        $user = auth()->user();

        // FIX : SÉCURITÉ D'AFFICHAGE (ex: view-emails)
        if ($inst->permission && (!$user || !$user->can($inst->permission))) {
            return null; // On ne renvoie rien, le champ ne sera pas dans le tableau
        }

        $name = $property->getName();
        
        // CALCUL DU READONLY (Propagation)
        $isReadOnly = $parentReadOnly || ($inst->editPermission && (!$user || !$user->can($inst->editPermission)));

        $type = $inst->type ?? 'text';
        $description = $inst->description ?? null;

        $data = [
            'name' => $name,
            'label' => $inst->label,
            'colSpan' => ($type === 'hidden') ? 0 : ($inst->colSpan ?? 12),
            'multiple' => $inst->multiple ?? false,
            'readonly' => $isReadOnly,
            'type' => $type,
            'description' => $description,
            'required' => ($inst instanceof Field && $inst->required) || !$property->getType()?->allowsNull(),
            'options' => [],
        ];

        $sectionAttr = $property->getAttributes(Section::class)[0] ?? null;
        if ($sectionAttr) {
            $s = $sectionAttr->newInstance();
            $data['section'] = ['title' => $s->title, 'description' => $s->description, 'icon' => $s->icon];
        }

        if ($visibleAttr) {
            $v = $visibleAttr->newInstance();
            $data['visibleIf'] = ['field' => $v->field, 'value' => $v->value, 'operator' => $v->operator ?? '='];
        }

        if ($repeaterAttr) {
            $data = array_merge($data, [
                'type' => 'repeater',
                'dataClass' => $inst->dataClass,
                'addLabel' => $inst->addLabel,
                'titleKey' => $inst->titleKey,
                'schema' => $this->getSchemaFromDataClass($inst->dataClass, $inputData[$name] ?? [], $isReadOnly),
            ]);
        } elseif ($lazyAttr) {
            $data = array_merge($data, [
                'type' => 'select',
                'options' => $this->getInitialLazyOptions($inst, $inputData[$name] ?? null),
                'lazy' => [
                    'model' => str_replace('\\', '\\\\', $inst->model), 
                    'labelColumn' => $inst->labelColumn, 
                    'valueColumn' => $inst->valueColumn,
                    'iconColumn' => $inst->iconColumn ?? null,
                    'imageColumn' => $inst->imageColumn ?? null,
                ]
            ]);
        } else {
            $data['type'] = $inst->type;
            $data['options'] = $inst->options ?? [];
        }

        return $data;
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
        
        // On récupère toutes les colonnes nécessaires
        $columns = [$attr->valueColumn, $attr->labelColumn];
        if ($attr->iconColumn) $columns[] = $attr->iconColumn;
        if ($attr->imageColumn) $columns[] = $attr->imageColumn;

        return $query->get($columns)->mapWithKeys(function ($item) use ($attr) {
            return [$item->{$attr->valueColumn} => [
                'label' => $item->{$attr->labelColumn},
                'icon'  => $attr->iconColumn ? $item->{$attr->iconColumn} : null,
                'image' => $attr->imageColumn ? $item->{$attr->imageColumn} : null,
            ]];
        })->toArray();
    }
}