@props(['view'=> null,'availableViews' => []])

@php
    $view ??= $this->view;
    $filters = $this->getAllFilters;
    $globalActions = $this->getGlobalActions;
    $bulkActions = $this->getBulkActions;
@endphp
<div 
    x-data="{ 
        selected: $wire.entangle('selected'),
        pageIds: {{ json_encode($this->items->pluck('id')->toArray()) }},
        toggleAll() {
            this.selected = this.selected.length === this.pageIds.length ? [] : [...this.pageIds];
        }
    }"
    x-on:clear-selection.window="selected = []"
    class="mt-5 mb-5">

    <div class="hidden">{{ $this->refresh }}</div>
    <div class="sticky top-0 z-30 bg-white dark:bg-zinc-900 py-2 -mt-4">
        <div class="md:flex justify-between items-center mb-6 sm:space-y-2">
            <div class="flex items-center gap-3">
                @if($availableViews)
                    <flux:radio.group wire:model.live="view" variant="segmented">
                        @foreach($availableViews as $v)
                            <flux:radio :value="$v" :icon="$this->getIconForView($v)" class="cursor-pointer" :title="ucfirst($v)" />
                        @endforeach
                    </flux:radio.group>
                @endif
                @if ($view == 'table' || $view == 'grid')
                <x-core::data.view.partials.search/>
                @endif
                <div wire:loading wire:target="handleAction, showItem, updateItemStatus, updateEventDates">
                    <flux:icon.loading />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-core::data.view.partials.filters :schema="$filters" />
                <x-core::data.view.actions.global :actions="$globalActions"/>
            </div>
                
        </div>        
    </div>

    <div class="relative">
        <x-core::data.view.partials.skeleton :view="$view" />

        <div wire:loading.remove  
            wire:target.except="handleAction, handleBulkAction, selected, 
            quickCreate, saveQuickItem,showItem,updateItemStatus, updateEventDates">

            <div class="mt-4">
                <x-dynamic-component :component="'core::data.view.layouts.' . $view" />
            </div>        
        </div>        
    </div>


    <div wire:loading.remove  wire:target.except="handleAction">
        @if ($view == 'table' || $view == 'grid')
            <x-core::data.view.partials.pagination :items="$this->items" />
        @endif
    </div>

    <x-core::data.view.actions.bulk-bar :actions="$bulkActions" :selected="$this->selected" />

    <x-core::data.view.show :item="$this->activeItem" :mode="$this->mode" />

</div>