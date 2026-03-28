@props([
    'view'=> 'table', 
    'items', 
    'schema', 
    'availableViews' => [],
    'resource'=> null
])

<div 
    x-data="{ 
        selected: $wire.entangle('selected'),
        pageIds: {{ json_encode($items->pluck('id')->toArray()) }},
        toggleAll() {
            this.selected = this.selected.length === this.pageIds.length ? [] : [...this.pageIds];
        }
    }"
    x-on:clear-selection.window="selected = []"
    class="mt-5 mb-5">
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
                <x-core::data.view.parts.search/>
                <div wire:loading wire:target="handleAction, showItem, updateItemStatus">
                    <flux:icon.loading />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-core::data.view.parts.filters :schema="$this->filterSchema" />
                <x-core::data.view.actions.global :actions="$this->globalActions"/>
            </div>
                
        </div>        
    </div>

    <div class="relative">
        <x-core::data.view.parts.skeleton :view="$view" :schema="$this->schema" />

        <div wire:loading.remove  
            wire:target.except="handleAction, handleBulkAction, selected, quickCreate, saveQuickItem,showItem,updateItemStatus">

            <div class="mt-4">
                <x-dynamic-component 
                    :component="'core::data.view.layouts.' . $view" 
                    :items="$items" 
                    :schema="$schema" 
                    :actions="$this->actions"
                    :resource="$resource"
                />
            </div>        
        </div>        
    </div>


    <div wire:loading.remove  wire:target.except="handleAction">
        <x-core::data.view.parts.pagination :items="$items" />
    </div>
    <x-core::data.view.actions.bulk-bar 
            :actions="$this->actions['bulk'] ?? []" 
            :selected="$this->selected"
        />

    <x-core::data.view.show :item="$this->activeItem" :schema="$this->detailSchema" :mode="$this->mode" />

</div>