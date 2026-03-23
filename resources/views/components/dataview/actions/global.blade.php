@props(['actions'])

@foreach($actions as $action)
        <flux:button 
            :icon="$action['icon']" 
            :variant="$action['variant']"
            wire:click="handleAction('{{ $action['name'] }}')"
            class="cursor-pointer"
        >
            {{ $action['label'] }}
        </flux:button>
@endforeach