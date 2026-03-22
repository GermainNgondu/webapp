<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use App\Core\Framework\Support\DataForm\Services\WizardFormService;
use Illuminate\Validation\ValidationException;

trait HasWizardForm
{
    // Wizard step save
    public function saveStep($stepIndex)
    {
        // 1. Récupérer les champs de l'étape actuelle via le service
        $wizardService = app(WizardFormService::class);
        $allSteps = $wizardService::init()->build($this->dataClass, $this->form);
        
        $currentStep = $allSteps[$stepIndex] ?? null;
        if (!$currentStep) return false;

        $currentStepFields = $currentStep['fields'] ?? [];

        // 2. Préparer les règles de validation uniquement pour ces champs
        $rules = [];
        foreach ($currentStepFields as $field) {
            $rules['form.' . $field['name']] = $this->getFieldRules($field); 
        }

        try {
            $this->validate($rules);

            // Exécution d'une action associée à l'étape s'il y en a une
            if (!empty($currentStep['action'])) {
                $actionClass = $currentStep['action'];
                app($actionClass)->run($this->form, $this->model);
            }
            
            return true;
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            return false;
        }
    }
}
