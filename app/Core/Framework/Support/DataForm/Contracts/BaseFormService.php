<?php

namespace App\Core\Framework\Support\DataForm\Contracts;

use ReflectionClass;
use ReflectionProperty;
use Exception;
use App\Core\Framework\Support\DataForm\Attributes\{Field, Repeater, VisibleIf, LazySelect, Section, MediaPicker};

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
    protected function resolveField(ReflectionProperty $property, array $inputData = [], bool $parentReadOnly = false): ?array
    {
        $inst = $this->getPrimaryAttribute($property);
        if (!$inst) return null;

        // SÉCURITÉ D'AFFICHAGE
        if (!$this->canView($inst)) {
            return null;
        }

        $dataClass = $property->getDeclaringClass()->getName();
        $name = $property->getName();
        
        // CALCUL DU READONLY (Propagation)
        $isReadOnly = $parentReadOnly || !$this->canEdit($inst);

        $type = $inst->type ?? 'text';
        if ($inst instanceof MediaPicker) $type = 'media-picker';
        if ($inst instanceof Repeater) $type = 'repeater';
        if ($inst instanceof LazySelect) $type = 'select';

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
        } elseif (($inst->type ?? '') === 'media') {
            $data['existing'] = $this->getExistingMedia($dataClass, $inst, $inputData);
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

    protected function getExistingMedia(string $dataClass, object $inst, array $inputData): array
    {
        $modelClass = $this->getModelClass($dataClass);
        $collection = $inst->options['collection'] ?? 'default';
        
        $model = isset($inputData['id']) ? $modelClass::find($inputData['id']) : null;

        return $model ? $model->getMedia($collection)->map(fn($m) => [
            'id' => $m->id,
            'url' => $m->getUrl('thumb') ?: $m->getUrl(),
            'name' => $m->file_name,
        ])->toArray() : [];
    }

    /**
     * Récupère l'attribut principal d'un champ
     */
    protected function getPrimaryAttribute(ReflectionProperty $property): ?object
    {
        $primaryClasses = [Field::class, Repeater::class, LazySelect::class, MediaPicker::class];
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

    /**
     * Détermine la classe du Modèle Eloquent lié à la classe Data.
     */
    protected function getModelClass(string $dataClass): string
    {
        // Option 1 : Vérifier si une constante 'MODEL' est définie dans votre Data Class
        if (defined("$dataClass::MODEL")) {
            return $dataClass::MODEL;
        }

        // Option additionnelle pour une interface ou une méthode existante
        if (method_exists($dataClass, 'getModelClass')) {
            return $dataClass::getModelClass();
        }

        // Option 2 : Convention de nommage (ex: ClientData -> Client)
        $modelName = str_replace('Data', '', class_basename($dataClass));
        $guessedModel = "App\\Models\\" . $modelName;

        if (class_exists($guessedModel)) {
            return $guessedModel;
        }

        throw new Exception("Impossible de trouver le modèle associé à $dataClass. Ajoutez 'public const MODEL = MonModele::class;' dans votre Data class.");
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
}