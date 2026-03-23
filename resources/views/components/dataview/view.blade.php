@props(['view', 'items', 'schema', 'availableViews' => ['table', 'grid']])

<div class="mt-5 mb-5">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <flux:radio.group wire:model.live="view" variant="segmented">
                @foreach($availableViews as $v)
                    <flux:radio :value="$v" :icon="$this->getIconForView($v)" class="cursor-pointer" :title="ucfirst($v)" />
                @endforeach
            </flux:radio.group>
            <x-dataview.search/>
        </div>

        <div class="flex items-center gap-3">
            <x-dataview.filters :schema="$this->filterSchema" />
            <x-dataview.actions.global :actions="$this->globalActions"/>
        </div>
        
    </div>

    <div class="mt-4">
        <x-dynamic-component 
            :component="'dataview.layouts.' . $view" 
            :items="$items" 
            :schema="$schema" 
            :actions="$this->actions"
        />
    </div>
    <x-dataview.pagination :items="$items" />
</div>