<?php

use App\Core\Framework\Support\DataForm\Services\{ 
    AccordionFormService, 
    TabsFormService, 
    WizardFormService, 
    SimpleFormService,
    FormService,
};

use Livewire\Component;
use Illuminate\Validation\ValidationException;
use App\Core\Framework\Support\DataForm\Traits\HasDynamicForm;

new class extends Component
{
    use HasDynamicForm;

    public array $config = [];
    public array $builder = [];
    public string $layout ='simple';

    public function mount(string|int $id = null)
    {
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

        if($model && $id)
        {  
            $data = ($this->dataClass)::from($model::find($id))->toArray();
        }
        else
        {
            try 
            {
                $data = ($this->dataClass)::empty();

            } catch (\Throwable $th) {
                //throw $th;
            }
        }

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


        }catch (ValidationException $e) {
            throw ValidationException::withMessages(
                collect($e->errors())
                    ->mapWithKeys(fn ($messages, $key) => ["form.{$key}" => $messages])
                    ->all()
            );

        } catch (\Exception $e) {
            $this->addError('form_global', $e->getMessage());
            $this->dispatch('notify', 
                message: $this->config['errorMessage'] ?? 'Une erreur est survenue', 
                variant: 'error'
            );
        }
    }
};
?>

<div class="space-y-6">
    {{-- Header --}}
    <x-ui.alert/>
    <div class="flex flex-col items-center gap-4">
        @if($config['icon'])
            <flux:icon :name="$config['icon']" class="h-6 w-6 text-zinc-600" />
        @endif
        <div>
            <flux:heading size="xl">{{ $config['title'] }}</flux:heading>
            @if($config['description']) <flux:subheading>{{ $config['description'] }}</flux:subheading> @endif
        </div>
    </div>

    <form  wire:submit.prevent="save" {{ $attributes }}>

        @csrf
        @if($layout === 'accordion')
            <x-dataform.layouts.accordion :sections="$builder" />
        @elseif($layout === 'tabs')
            <x-dataform.layouts.tabs :tabs="$builder" />
        @elseif($layout=== 'simple')
            <x-dataform.layouts.simple :fields="$builder" />
        @elseif($layout === 'wizard')
            <x-dataform.layouts.wizard :steps="$builder" />
        @endif
    
        {{-- Footer/Action --}}
        @if($config['layout'] !== 'wizard')
            <div class="flex justify-end gap-3 mt-8">
                <flux:button variant="primary" wire:click="save" class="w-full sm:w-auto cursor-pointer">
                    {{ $config['saveLabel'] }}
                </flux:button>
            </div>
        @endif
    </form>
</div>