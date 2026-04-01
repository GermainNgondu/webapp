<?php

use Flux\Flux;
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
        Flux::modal('form-insight')->close();
    }

    public function openFormModal()
    {
        Flux::modal('form-insight')->show();
    }
};
?>

@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder


<div>
    @php 
    
        $insight = $this->insight; 
        $items = collect($this->insights)->whereNotIn('id',$insight['id'])->all();
        
    @endphp

    @if($insight)
    
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <flux:heading size="xl" level="1">{{ ucfirst($insight['name']) }}</flux:heading>
                <flux:subheading>{{ ucfirst($insight['description']) }}</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <div wire:loading>
                    <flux:icon.loading />
                </div>
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">{{ ucfirst($insight['name']) }}</flux:button>

                    <flux:menu>
                        <flux:menu.item icon="plus" wire:click="openFormModal" class="cursor-pointer">
                            {{ucfirst(__('add')) }}
                        </flux:menu.item>

                        <flux:menu.separator />

                        @foreach ($items as $item)

                            <flux:menu.item wire:click="changeInsight('{{ $item['uuid'] }}')" class="cursor-pointer">
                                {{ ucfirst($item['name']) }}
                            </flux:menu.item>

                        @endforeach
                        
                    </flux:menu>
                </flux:dropdown>                
                <flux:modal.trigger name="insight-manager">
                    <flux:button icon="settings" variant="ghost" class="cursor-pointer"/>
                </flux:modal.trigger>
            </div>
        </div>
        <x-core::data.insight.view :widgets="$this->insight->widgets"/>

    @else
        <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
            <div class="flex flex-col items-center gap-2">
                <flux:icon name="chart-no-axes-combined" class="size-12" />
                <flux:heading size="lg">Aucun tableau de bord</flux:heading>
                <flux:modal.trigger name="form-insight">
                    <flux:button icon="plus" variant="primary" size="sm" class="capitalize cursor-pointer">
                        {{ __('add') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    @endif

    <x-core::ui.modal name="form-insight" :title="__('insight')">
         <livewire:form :dataClass="$this->dataClass" />
    </x-core::ui.modal>
</div>