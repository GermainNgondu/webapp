<?php

namespace App\Core\Framework\Support\Data\Form\Traits;

use App\Core\Framework\Support\Data\Form\Services\{  AccordionFormService, TabsFormService, WizardFormService, SimpleFormService, FormService, };
use Exception;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Reactive;

trait HasForm
{
    use HasFormFields;

    public array $config = [];
    public array $builder = [];
    public string $layout ='simple';
    public bool $edit = false;


    public mixed $data = null;

    public function mount(string|int $id = null, mixed $data = [])
    {
        $this->data = $data;
        // On récupère la config globale (layout, titre, action...)
        $this->config = app(FormService::class)->getFormConfig($this->dataClass);

        $this->layout = $this->config['layout'] ?? 'simple';

        // le service de build selon le layout
        $service = match($this->layout) {
            'accordion'=> app(AccordionFormService::class),
            'wizard' => app(WizardFormService::class),
            'tabs' => app(TabsFormService::class),
            default  => app(SimpleFormService::class),
        };

        $model = $this->config['model'] ?? null;

        $this->formData($model,$id);
        //les champs
        $this->builder = $service->build($this->dataClass, $this->form);
    }

    public function formData(string $model = null, string|int $id = null): void
    {
        $data = [];

        $defaultData = $this->data ?? [];
        
        if($model && $id)
        {  
            $data = ($this->dataClass)::from($model::find($id))->toArray();
            $this->edit = true;
        }
        else
        {
            try 
            {
                $data = ($this->dataClass)::empty();

            } catch (\Throwable $th) {

                $this->addError('form_global', $th->getMessage());
            }
        }

        if($defaultData){ $data = array_merge($data,$defaultData);}

        $this->form = $data;
    }

    public function save()
    {
        $this->resetErrorBag();

        try {

            // Nettoyage récursif selon les permissions Spatie
            $safeData = FormService::init()->secureData($this->dataClass,$this->form);

            // Validation Spatie Data
            $data = $this->validateData($this->dataClass, $safeData);
            
            if ($this->config['action']) {
                app($this->config['action'])->run($data->toArray());
            }
            
            $this->dispatch('form_saved',data: $data->toArray())->to($this->config['dispatch'] ?? null);
            
            // Succès : Notification Flux
            $this->dispatch('notify', 
                    message: $this->config['successMessage'], 
                    variant: 'success'
                );

            if ($this->config['redirect']) {
            
                if (str_starts_with($this->config['redirect'], 'intended:')) {

                    $fallback = str_replace('intended:', '', $this->config['redirect']);
                    
                    return redirect()->intended($fallback);
                }

                if ($this->config['redirect'] === 'refresh') {
                    return $this->redirect(request()->header('Referer'));
                }
                
                return redirect($this->config['redirect']);
            }

            if(!$this->edit){ $this->reset('form');}


        }catch (ValidationException $e) {

            throw ValidationException::withMessages(
                collect($e->errors())
                    ->mapWithKeys(function ($messages, $key) {
                        if(str_contains($key, 'form.')) {
                            return ["{$key}" => $messages];
                        }
                        return ["form.{$key}" => $messages];
                    })
                    ->all()
            );

        } catch (Exception $e) {
            $this->addError('form_global', $e->getMessage());
            $this->dispatch('notify', 
                message: $this->config['errorMessage'] ?? 'Une erreur est survenue', 
                variant: 'error'
            );
        }
    }
}
