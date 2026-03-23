<?php

use App\Core\Framework\Support\DataView\Traits\HasDataView;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Core\Installer\Data\ClientData;
use App\Core\Installer\Http\Actions\GetClientDataAction;

new #[Layout('layouts::admin')] class extends Component {
    
    use HasDataView;

    // On définit simplement les classes à utiliser
    protected function getDataClass(): string { return ClientData::class; }
    protected function getActionClass(): string { return GetClientDataAction::class; }

    public function render()
    {
        return $this->view([
            'items' => $this->getRowsProperty()
        ]);
    }
};