<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Features\Users\Domain\Data\Auth\LoginData;

new #[Layout('admin::layouts.auth')] class extends Component
{
    public function mount()
    {
        $this->dataClass = LoginData::class;
    }
};
?>

<div>
    <livewire:form :dataClass="$this->dataClass" />
</div>

