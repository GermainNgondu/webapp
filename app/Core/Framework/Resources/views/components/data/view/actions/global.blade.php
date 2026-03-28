@props(['actions'])

@foreach($actions as $action)
    @if ($action['label']  && !$action['icon'])
        <flux:button 
            :variant="$action['variant']"
            wire:click="handleAction('{{ $action['name'] }}')"
            class="cursor-pointer"
        >
            {{ $action['label'] }}
        </flux:button>
    @elseif ($action['icon'] && !$action['label'])
        <flux:button 
            :icon="$action['icon']" 
            :variant="$action['variant']"
            wire:click="handleAction('{{ $action['name'] }}')"
            class="cursor-pointer"
        />
    @else
        <flux:button 
            :icon="$action['icon']"
            :variant="$action['variant']"
            wire:click="handleAction('{{ $action['name'] }}')"
            class="cursor-pointer"
        >
            {{ $action['label'] }}
        </flux:button>
    @endif
@endforeach