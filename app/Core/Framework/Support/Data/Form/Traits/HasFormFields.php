<?php

namespace App\Core\Framework\Support\Data\Form\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

trait HasFormFields
{
    use HasWizardForm;
    use HasFormMedia;
    use HasLazySelect;
    use HasRepeater;
    use HasBlocks;

    public ?Model $model = null;

    public array $form = [];

    public $dataClass;

    /**
     * Prépare les règles de validation en tableau (array) de manière plus robuste.
     */
    protected function getFieldRules(array $field, $modelId = null): array
    {
        $rules = $field['rules'] ?? [];
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        // Nettoyage des règles vides
        $rules = array_filter($rules);

        // Ajout intelligent de 'required' ou 'nullable'
        if ($field['required'] ?? false) {
            $rules = array_diff($rules, ['nullable']);
            if (!in_array('required', $rules)) {
                $rules[] = 'required';
            }
        } else {
            if (!empty($rules) && !in_array('nullable', $rules)) {
                $rules[] = 'nullable';
            }
        }

        // Remplacement dynamique de l'ID pour les règles de type 'unique'
        return array_map(function ($rule) use ($modelId) {
            if (is_string($rule)) {
                return str_replace(':id', $modelId ?? 'NULL', $rule);
            }
            return $rule;
        }, $rules);
    }

    protected function validateData(string $dataClass, array $payload)
    {
        try {
            return $dataClass::validateAndCreate($payload);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages(
                collect($e->errors())
                    ->mapWithKeys(fn ($messages, $key) => ["form.{$key}" => $messages])
                    ->all()
            );
        }
    }
}