<?php

use App\Core\Admin\Domain\Data\InsightData;
use App\Core\Admin\Support\Traits\HasDashboard;
use Livewire\Attributes\{Layout, Lazy, On};
use Livewire\Component;

new #[Lazy, Layout('admin::layouts.admin')] class extends Component
{
    use HasDashboard;

    public function mount()
    {
        $this->dataClass = InsightData::class;
    }

    #[On('form_saved')]
    public function refreshInsights(): void
    {

    }
};
?>

@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder
<div>

    @if($this->insight)
        <x-core::data.insight.view :widgets="$this->insight->widgets"/>
    @else
        <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
            <div class="flex flex-col items-center gap-2">
                <flux:icon name="chart-no-axes-combined" class="size-12" />
                <flux:heading size="lg">Aucun tableau de bord</flux:heading>
                <flux:modal.trigger name="add-insight">
                    <flux:button icon="plus" variant="primary" size="sm" class="capitalize cursor-pointer">
                        {{ __('add') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    @endif

    <x-core::ui.modal name="add-insight" :title="__('insight')">
         <livewire:form :dataClass="$this->dataClass" />
    </x-core::ui.modal>
</div>