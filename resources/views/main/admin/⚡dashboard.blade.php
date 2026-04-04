<?php

use Flux\Flux;
use App\Core\Admin\Domain\Data\Insights\{InsightData,InsightCreateWidgetData,InsightEditWidgetData};
use App\Core\Admin\Support\Traits\HasDashboard;
use Livewire\Attributes\{Layout, Lazy};
use Livewire\Component;

new #[Lazy, Layout('admin::layouts.admin')] class extends Component
{
    use HasDashboard;

    public string $widgetDataClass;
    public string $widgetEditDataClass;

    public function mount()
    {
        $this->dataClass = InsightData::class;
        $this->widgetDataClass = InsightCreateWidgetData::class;
        $this->widgetEditDataClass = InsightEditWidgetData::class;
    }
};
?>

@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder
@php 
    
    $insight = $this->insight; 

    if($insight)
    {
        $items = collect($this->insights)->whereNotIn('id',$insight['id'])->all();

        $defaultData = ['insight_id'=> $insight['id']];        
    }
    
@endphp

<div>
    @if($insight)
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <flux:heading size="xl" level="1">{{ ucfirst($insight['name']) }}</flux:heading>
                <flux:subheading>{{ ucfirst($insight['description']) }}</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <div wire:loading> <flux:icon.loading /> </div>

                <flux:modal.trigger name="form-create-insight-widget">
                    <flux:button icon="plus" variant="primary" size="sm" class="cursor-pointer">
                        {{ ucfirst(__('widget')) }}
                    </flux:button>
                </flux:modal.trigger>

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down" size="sm" class="cursor-pointer">
                        {{ ucfirst($insight['name']) }}
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item icon="plus" wire:click="openFormModal('form-create-insight')" class="cursor-pointer">
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
                <flux:dropdown>
                    <flux:button icon:trailing="ellipsis-vertical" size="sm"  variant="ghost" class="cursor-pointer" />

                    <flux:menu>
                        <flux:menu.item 
                                icon="pencil-square" 
                                wire:click="openFormModal('form-edit-insight')" 
                                class="cursor-pointer">
                                {{ucfirst(__('edit')) }}
                        </flux:menu.item>
                        <flux:menu.item 
                            icon="trash" 
                            wire:click="deleteInsight('{{ $insight['uuid'] }}')"
                            wire:confirm="{{ __('Are you sure you want to delete this insight?') }}"
                            class="cursor-pointer">
                            {{ucfirst(__('delete')) }}
                        </flux:menu.item>

                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <x-core::data.insight.view :widgets="$this->widgets"/>

        <x-core::ui.modal mode="slideover" name="form-create-insight-widget" :title="__('widget')">
            <livewire:form :dataClass="$widgetDataClass" key="form-create-insight-widget-{{ $insight['id'] }}" :data="$defaultData"/>
        </x-core::ui.modal>

        <x-core::ui.modal name="form-edit-insight-widget" :title="__('widget')">
            @if ($widget)
               <livewire:form :dataClass="$widgetEditDataClass" key="widget-{{ $widget['uuid'] }}" :data="$widget"/> 
            @endif
        </x-core::ui.modal>

        <x-core::ui.modal name="form-edit-insight" :title="__('insight')">
            <livewire:form :dataClass="$dataClass" key="form-insight-{{ $insight['id'] }}" :data="$insight->toArray()"/>
        </x-core::ui.modal>

    @else
        <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
            <div class="flex flex-col items-center gap-2">
                <flux:icon name="chart-no-axes-combined" class="size-12" />
                <flux:heading size="lg">{{ ucfirst(__('no_dashboard')) }}</flux:heading>
                <flux:modal.trigger name="form-insight">
                    <flux:button icon="plus" variant="primary" size="sm" class="capitalize cursor-pointer">
                        {{ __('add') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    @endif

    <x-core::ui.modal name="form-create-insight" :title="__('insight')">
        <livewire:form :dataClass="$dataClass" key="form-insight"/>
    </x-core::ui.modal>
</div>