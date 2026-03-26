@props(['items', 'schema'])

@php
    /** @var \App\Core\Framework\Support\DataView\Services\LayoutDiscovery $discovery */
    $discovery = app(\App\Core\Framework\Support\DataView\Services\LayoutDiscovery::class);
    
    // On récupère la configuration de la grille depuis la classe Data
    $grid = $discovery::getGridSchema($this->getDataClass());
@endphp

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @foreach($items as $item)

        <flux:card wire:key="grid-item-{{ $item->id }}" class="flex flex-col overflow-hidden p-0 transition-shadow hover:shadow-md">
            
            {{-- 1. Section IMAGE --}}
            @if(isset($grid['image']))
                <div class="relative aspect-video w-full overflow-hidden bg-zinc-100 border-b border-zinc-200">
                    @php $imageUrl = $item->{$grid['image']['field']}; @endphp
                    
                    @if($imageUrl)
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="Preview" 
                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-110"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center text-zinc-400">
                            <flux:icon icon="photo" variant="outline" class="size-10" />
                        </div>
                    @endif

                    {{-- Badge superposé sur l'image --}}
                    @if(isset($grid['badge']))
                        <div class="absolute right-2 top-2">
                            <x-dynamic-component 
                                :component="$grid['badge']['component'] ?? 'core::ui.media-type-badge'" 
                                :value="$item->{$grid['badge']['field']}"
                            />
                        </div>
                    @endif
                </div>
            @endif

            {{-- 2. Section CONTENU --}}
            <div class="flex flex-1 flex-col p-4">
                <div class="mb-3 flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        @if(isset($grid['title']))
                            <flux:heading size="lg" class="truncate" title="{{ $item->{$grid['title']['field']} }}">
                                {{ $item->{$grid['title']['field']} }}
                            </flux:heading>
                        @endif
                        
                        @if(isset($grid['subtitle']))
                            <flux:text color="zinc" size="sm" class="truncate">
                                {{ $item->{$grid['subtitle']['field']} }}
                            </flux:text>
                        @endif
                    </div>

                    {{-- ACTIONS (Dropdown avec Portail Alpine.js) --}}
                    @if(count($this->actions['row'] ?? []) > 0)
                        <div x-data="{ open: false, position: { top: 0, left: 0 } }">
                            <button 
                                type="button" 
                                x-on:click="
                                    open = ! open; 
                                    if(open) {
                                        const rect = $el.getBoundingClientRect(); 
                                        position = { top: (rect.bottom + window.scrollY) + 'px', left: (rect.right - 192 + window.scrollX) + 'px' };
                                    }
                                "
                                x-on:click.away="open = false"
                                class="cursor-pointer rounded-md p-1 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 focus:outline-none"
                            >
                                <flux:icon icon="ellipsis-vertical" variant="mini" />
                            </button>

                            <template x-teleport="body">
                                <div 
                                    x-show="open" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    class="absolute z-50 w-48 rounded-xl border border-zinc-200 bg-white p-1.5 shadow-2xl flex flex-col gap-0.5"
                                    :style="'top: ' + position.top + '; left: ' + position.left + ';'"
                                    style="display: none;"
                                >
                                    @foreach($this->actions['row'] as $action)
                                        <button 
                                            type="button"
                                            wire:click="handleAction('{{ $action['name'] }}', '{{ $item->id }}')"
                                            x-on:click="open = false"
                                            @if($action['confirm']) wire:confirm="{{ $action['confirm'] }}" @endif
                                            class="flex w-full items-center gap-2 rounded-lg px-2 py-1.5 text-left text-sm transition hover:bg-zinc-50 {{ $action['color'] === 'red' ? 'text-red-600 hover:bg-red-50' : 'text-zinc-700' }}"
                                        >
                                            <flux:icon :icon="$action['icon']" variant="mini" />
                                            {{ $action['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        </div>
                    @endif
                </div>

                {{-- Description avec limitation de lignes --}}
                @if(isset($grid['description']))
                    <div class="mb-4 flex-1">
                        <flux:text size="sm" class="line-clamp-2">
                            {{ $item->{$grid['description']['field']} }}
                        </flux:text>
                    </div>
                @endif

                {{-- FOOTER --}}
                @if(isset($grid['footer']))
                    <div class="mt-auto flex items-center justify-between border-t border-zinc-100 pt-3">
                        <div class="flex items-center gap-1.5 text-zinc-500">
                            @if($grid['footer']['icon'])
                                <flux:icon :icon="$grid['footer']['icon']" variant="micro" class="size-3" />
                            @endif
                            <flux:text size="xs">{{ $item->{$grid['footer']['field']} }}</flux:text>
                        </div>
                        
                        {{-- Bouton de détail rapide --}}
                        <flux:button icon="arrow-right" variant="ghost" size="sm" inset="right" />
                    </div>
                @endif
            </div>
        </flux:card>
    @endforeach
</div>

{{-- Message si vide --}}
@if($items->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-zinc-400">
        <flux:icon icon="inbox" variant="outline" class="mb-4 size-12 opacity-20" />
        <flux:text>Aucun élément trouvé</flux:text>
    </div>
@endif