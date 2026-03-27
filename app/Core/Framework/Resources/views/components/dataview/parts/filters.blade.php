@props(['schema'])

<div class="flex flex-wrap gap-4 items-end">
    @foreach($schema as $field => $config)
        <div class="w-full md:w-54 inline-flex mb-0.5 gap-2">
            <flux:label>{{ $config['label'] }}</flux:label>

            @if($config['type'] === 'text')
                <flux:input 
                    wire:model.live.debounce.400ms="filters.{{ $field }}" 
                    :placeholder="$config['placeholder']" 
                    size="sm" 
                />
            @elseif($config['type'] === 'select')
                <flux:select wire:model.live="filters.{{ $field }}" size="sm">
                    <flux:select.option value="">Tous</flux:select.option>
                    @foreach($config['options'] as $value => $label)
                        <flux:select.option :value="$value">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
            @elseif($config['type'] === 'date')
                <flux:input type="date" wire:model.live="filters.{{ $field }}" size="sm" />
            @endif
        </div>
    @endforeach

    @if(count(array_filter($this->filters)))
        <flux:button 
            wire:click="$set('filters', [])" 
            variant="ghost" 
            size="sm" 
            icon="x-mark"
            class="mb-0.5 cursor-pointer"
        >
            Effacer
        </flux:button>
    @endif
</div>