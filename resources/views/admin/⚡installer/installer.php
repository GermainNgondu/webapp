<?php

use App\Core\Framework\Support\DataForm\Services\AccordionFormService;
use App\Core\Framework\Support\DataForm\Services\SimpleFormService;
use App\Core\Framework\Support\DataForm\Services\TabsFormService;
use App\Core\Framework\Support\DataForm\Services\WizardFormService;
use App\Core\Framework\Support\DataForm\Traits\HasDynamicForm;
use App\Core\Installer\Data\{ClientData,InstallData,SettingsData,ProductData};
use App\Models\Client;
use Illuminate\Support\Str;
use Livewire\Component;

    
new class extends Component {
    use HasDynamicForm;

    
    public $client;

    public $t = 'simple';
    

    /**
     * Optionnel : Initialiser les valeurs par défaut
     */
    public function mount()
    {
        $this->dataClass = ProductData::class;
        $this->empty('product');
    }
    
    public function builder(string $target)
    {
        return match ($target) {
            'accordion' => AccordionFormService::init()->build(SettingsData::class),
            'tabs' => TabsFormService::init()->build(ClientData::class),
            'wizard' => WizardFormService::init()->build(ClientData::class),
            default => SimpleFormService::init()->build(ProductData::class),
        };
    }

    public function save()
    {  
        // Nettoyage récursif selon les permissions Spatie
        $safeData = TabsFormService::init()->secureData(ClientData::class,$this->form);

        // Validation Spatie Data
        $data = $this->validateData(ClientData::class, $safeData);

        if($this->client)
        {
            $this->client->update($data->toArray());
            $this->dispatch('notify', 
                message: 'Client mis à jour avec succès !', 
                variant: 'success'
            );
        }
        else
        {
            Client::create($data->toArray());

            $this->dispatch('notify', 
                message: 'Client créé avec succès !', 
                variant: 'success'
            );

            $this->reset('form');
        }
    }

    public function empty(string $model)
    {
        $this->form = match ($model) {
            'settings' => SettingsData::empty(),
            'client' => ClientData::empty(),
            'install' => InstallData::empty(),
            'product'=> ProductData::empty(),
        };
    }

    public function edit(int $id)
    {
        $this->client = Client::find($id);
        $this->form = ClientData::from($this->client)->toArray();
    }

    public function delete(int $id)
    {
         $this->client = Client::find($id);
         if($this->client){
            $this->client->delete();
            $this->dispatch('notify', 
                message: 'Client supprimé avec succès !', 
                variant: 'success'
            );
         }
    }

    public function addRepeaterRow($fieldName, $dataClass)
    {
        $newRow = $dataClass::empty();
        $id = 'temp_' . Str::random(8);
        $newRow['id'] = $id;

        $this->form[$fieldName][$id] = $newRow;
    }

    public function removeRepeaterRow($fieldName, $index)
    {
        if (isset($this->form[$fieldName][$index])) {
            unset($this->form[$fieldName][$index]);
        }
    }

    public function reorderRepeaterRow($fieldName, $newIdsOrder)
    {
        $ordered = [];
        foreach ($newIdsOrder as $id) {
            if (isset($this->form[$fieldName][$id])) {
                $ordered[$id] = $this->form[$fieldName][$id];
            }
        }
        $this->form[$fieldName] = $ordered;
    }
};