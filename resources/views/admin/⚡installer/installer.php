<?php

use App\Core\Framework\Support\DataForm\Services\{AccordionFormService,FormService,SimpleFormService,TabsFormService,WizardFormService};
use App\Core\Framework\Support\DataForm\Traits\HasDynamicForm;
use App\Core\Installer\Data\{ClientData,InstallData, PostData,SettingsData,ProductData};
use App\Models\Client;
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
        $this->dataClass = PostData::class;
        $this->empty('post');
    }
    
    public function builder(string $target)
    {

        return match ($target) {
            'accordion' => AccordionFormService::init()->build(SettingsData::class),
            'tabs' => TabsFormService::init()->build(ClientData::class),
            'wizard' => WizardFormService::init()->build(ClientData::class),
            default => SimpleFormService::init()->build($this->dataClass),
        };
    }

    public function save()
    {  
        // Nettoyage récursif selon les permissions Spatie
        $safeData = FormService::init()->secureData($this->dataClass,$this->form);

        // Validation Spatie Data
        $data = $this->validateData($this->dataClass, $safeData);

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

        $this->dispatch('form-saved');
    }

    public function empty(string $model)
    {
        $this->form = match ($model) {
            'settings' => SettingsData::empty(),
            'client' => ClientData::empty(),
            'install' => InstallData::empty(),
            'product'=> ProductData::empty(),
            'post'=> PostData::empty(),
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
};