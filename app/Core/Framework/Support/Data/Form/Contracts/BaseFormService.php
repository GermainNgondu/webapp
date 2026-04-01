<?php

namespace App\Core\Framework\Support\Data\Form\Contracts;

use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use App\Core\Framework\Support\Data\Form\Attributes\{
    Field, 
    Repeater, 
    VisibleIf, 
    LazySelect, 
    Section, 
    MediaPicker, 
    Blocks,
    FormConfig,
    Accordion,
    Tab,
    Step
};

abstract class BaseFormService
{
    // Cache statique pour la requête courante (RAM)
    protected static array $metadataRegistry = [];

    abstract public function build(string $dataClass, array $inputData = []): array;

    /**
     * Analyse la classe de données et met en cache la structure.
     */
    public static function resolveMetadata(string $dataClass): array
    {
        if (isset(self::$metadataRegistry[$dataClass])) {
            return self::$metadataRegistry[$dataClass];
        }

        $cacheKey = "form_metadata_v3_" . md5($dataClass);

        if (App::isLocal()) {
            Cache::forget($cacheKey);
        }

        self::$metadataRegistry[$dataClass] = Cache::remember($cacheKey, now()->addDay(), function () use ($dataClass) {
            if (!class_exists($dataClass)) {
                return ['config' => [], 'properties' => []];
            }

            $reflection = new ReflectionClass($dataClass);
            $metadata = [
                'config' => [],
                'properties' => [],
            ];

            // --- FormConfig ---
            $formConfigAttr = $reflection->getAttributes(FormConfig::class)[0] ?? null;
            if ($formConfigAttr) {
                $config = $formConfigAttr->newInstance();
                $metadata['config'] = [
                    'title'       => $config->title,
                    'description' => $config->description,
                    'layout'      => $config->layout,
                    'action'      => $config->action,
                    'model'       => $config->model,
                    'saveLabel'   => $config->saveLabel,
                    'saveIcon'    => $config->saveIcon,
                    'icon'        => $config->icon,
                    'redirect'    => $config->redirect,
                    'successMessage' => $config->successMessage,
                    'errorMessage' => $config->errorMessage,
                    'dispatch'    => $config->dispatch,
                    'cancel'      => $config->cancel,
                ];
            } else {
                $metadata['config'] = [
                    'title' => class_basename($dataClass),
                    'layout' => 'simple',
                    'saveLabel' => 'save',
                    'saveIcon'=> null,
                    'action' => null,
                    'model'=> null,
                    'successMessage' => 'Opération réussie !',
                    'errorMessage' => 'Une erreur est survenue lors du traitement.',
                    'dispatch'=> null,
                    'cancel'=> null,
                ];
            }

            // --- Properties ---
            $primaryClasses = [Field::class, Repeater::class, LazySelect::class, MediaPicker::class, Blocks::class];

            foreach ($reflection->getProperties() as $property) {
                $name = $property->getName();
                $propMeta = [
                    'name' => $name,
                    'primary' => null,
                    'secondary' => [],
                    'defaultValue' => $property->hasDefaultValue() ? $property->getDefaultValue() : null,
                ];

                // Attribut Primaire
                foreach ($primaryClasses as $class) {
                    $attr = $property->getAttributes($class)[0] ?? null;
                    if ($attr) {
                        $propMeta['primary'] = $attr->newInstance();
                        break;
                    }
                }

                if (!$propMeta['primary'] && $name !== 'id') continue;

                // Attributs Secondaires
                $sectionAttr = $property->getAttributes(Section::class)[0] ?? null;
                if ($sectionAttr) {
                    $inst = $sectionAttr->newInstance();
                    $propMeta['secondary']['section'] = ['title' => $inst->title, 'description' => $inst->description, 'icon' => $inst->icon];
                }

                $visibleAttr = $property->getAttributes(VisibleIf::class)[0] ?? null;
                if ($visibleAttr) {
                    $inst = $visibleAttr->newInstance();
                    $propMeta['secondary']['visibleIf'] = ['field' => $inst->field, 'value' => $inst->value, 'operator' => $inst->operator ?? '='];
                }

                $accordionAttr = $property->getAttributes(Accordion::class)[0] ?? null;
                if ($accordionAttr) {
                    $inst = $accordionAttr->newInstance();
                    $propMeta['secondary']['accordion'] = ['name' => $inst->name, 'icon' => $inst->icon];
                }

                $tabAttr = $property->getAttributes(Tab::class)[0] ?? null;
                if ($tabAttr) {
                    $inst = $tabAttr->newInstance();
                    $propMeta['secondary']['tab'] = [
                        'name' => $inst->name, 
                        'icon' => $inst->icon,
                        'permission' => $inst->permission ?? null,
                        'editPermission' => $inst->editPermission ?? null
                    ];
                }

                $stepAttr = $property->getAttributes(Step::class)[0] ?? null;
                if ($stepAttr) {
                    $inst = $stepAttr->newInstance();
                    $propMeta['secondary']['step'] = [
                        'name' => $inst->name, 
                        'icon' => $inst->icon, 
                        'description' => $inst->description ?? null,
                        'action' => $inst->action ?? null
                    ];
                }

                $metadata['properties'][$name] = $propMeta;
            }

            return $metadata;
        });

        return self::$metadataRegistry[$dataClass];
    }

    /**
     * Sécurité d'écriture : Nettoie les données reçues.
     */
    public function secureData(string $dataClass, array $inputData): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $safeData = [];

        foreach ($metadata['properties'] as $name => $prop) {
            if ($name === 'id' && isset($inputData['id'])) {
                $safeData['id'] = $inputData['id'];
                continue;
            }

            $inst = $prop['primary'];
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
    protected function resolveField(array $propMeta, string $dataClass, array $inputData = [], bool $parentReadOnly = false): ?array
    {
        $inst = $propMeta['primary'];
        if (!$inst) return null;

        // SÉCURITÉ D'AFFICHAGE
        if (!$this->canView($inst)) { return null; }

        $name = $propMeta['name'];
        
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

        $data = array_merge($data, $propMeta['secondary']);

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

    protected function getSchemaFromDataClass(string $dataClass, array $inputData = [], bool $parentReadOnly = false): array
    {
        $metadata = self::resolveMetadata($dataClass);
        $schema = [];
        foreach ($metadata['properties'] as $propMeta) {
            $field = $this->resolveField($propMeta, $dataClass, $inputData, $parentReadOnly);
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
        $metadata = self::resolveMetadata($blockClass);
        $defaults = [];

        foreach ($metadata['properties'] as $name => $propMeta) {
            $inst = $propMeta['primary'];
            if (!$inst) continue;

            if ($propMeta['defaultValue'] !== null) {
                $defaults[$name] = $propMeta['defaultValue'];
            } else {
                $defaults[$name] = $this->getDefaultValueForAttribute($inst);
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
        return self::resolveMetadata($dataClass)['config'];
    }

    /**
     * Récupère la classe du modèle associé à la classe Data.
     * @param string $dataClass
     * @throws \Exception
     * @return string
     */
    protected function getModelClass(string $dataClass): string
    {
        $config = self::resolveMetadata($dataClass)['config'];
        
        if (!empty($config['model'])) {
            return $config['model'];
        }

        // Priorité 2 : La constante MODEL dans la classe Data
        if (defined("$dataClass::MODEL")) {
            return $dataClass::MODEL;
        }

        throw new \Exception("Impossible de trouver le modèle associé à $dataClass.");
    }
}