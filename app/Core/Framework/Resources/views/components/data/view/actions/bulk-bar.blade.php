@props(['actions'])

{{-- x-show au lieu de @if pour une transition fluide sans aller-retour serveur --}}
<div 
    x-show="selected.length > 0" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50"
    style="display: none;"
>
    <div class="bg-white dark:bg-zinc-800 rounded-full px-6 py-3 shadow-2xl flex items-center gap-6 border border-zinc-800">
        <span class="text-sm font-medium pr-6 border-r border-zinc-700">
            <span x-text="selected.length"></span> sélectionné(s)
        </span>

        <div class="flex items-center gap-2">
            @foreach($actions as $action)
               <flux:button 
                    size="sm" 
                    variant="filled"
                    class="cursor-pointer"
                    wire:loading.attr="disabled"
                    wire:target="handleBulkAction('{{ $action['name'] }}')"
                    x-on:click="$wire.handleBulkAction('{{ $action['name'] }}')"
                >
                    <div wire:loading wire:target="handleBulkAction('{{ $action['name'] }}')">
                        <flux:icon name="arrow-path" class="size-4 animate-spin mr-2" />
                    </div>
                    <div wire:loading.remove wire:target="handleBulkAction('{{ $action['name'] }}')">
                        <flux:icon :icon="$action['icon']" variant="mini" class="mr-2" />
                    </div>

                    {{ $action['label'] }}
                </flux:button>
            @endforeach
        </div>

        <flux:button size="sm" variant="ghost" icon="x-mark" class="cursor-pointer"  x-on:click="selected = []" />
    </div>
</div>