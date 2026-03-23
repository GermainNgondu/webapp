@props(['actions', 'item', 'grid'=> false])

@if ($actions && $item)

    <div x-data="{ open: false, position: { top: 0, left: 0 } }" class="relative inline-block text-left">
                    
                    {{-- Bouton-déclencheur avec un ID unique pour le calcul --}}
                    <button 
                        id="trigger-{{ $item->id }}"
                        type="button" 
                        {{-- Au clic : on bascule, on calcule la position du déclencheur, et on met à jour 'position' --}}
                        x-on:click="
                            open = ! open; 
                            if(open) {
                                const rect = $el.getBoundingClientRect(); 
                                {{-- On ajoute le scroll vertical de la page pour une position absolue correcte --}}
                                position = { top: (rect.bottom + window.scrollY) + 'px', left: rect.left + 'px' };
                            }
                        " 
                        x-on:click.away="open = false"
                        class="cursor-pointer p-1 rounded-md text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 transition focus:outline-none"
                    >
                        {{-- Icône Ellipsis --}}
                        <flux:icon :name="$grid ? 'ellipsis-vertical' : 'ellipsis-horizontal'" class="size-5" />
                    </button>
                    
                    {{-- LE MENU DROPDOWN, TÉLÉPORTÉ À LA FIN DU BODY --}}
                    <template x-teleport="body">
                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            {{-- On z-50 pour être au-dessus de tout --}}
                            class="absolute z-50 w-48 rounded-xl bg-white p-2 shadow-2xl border border-zinc-200 flex flex-col gap-1 focus:outline-none"
                            {{-- On lie les styles de positionnement calculés en JS --}}
                            :style="'top: ' + position.top + '; left: ' + position.left + ';'"
                            style="display: none;" {{-- Empêche le flash au chargement --}}
                        >
                            {{-- Boucle sur tes actions de ligne --}}
                            @foreach($actions as $action)
                                <button 
                                    type="button"
                                    x-on:click="open = false" {{-- Ferme le menu après le clic --}}
                                    {{-- Ternaire pour la couleur rouge si c'est 'delete' --}}
                                    class="cursor-pointer flex w-full items-center gap-2 rounded-md p-2 text-sm text-left transition hover:bg-zinc-100
                                           {{ $action['color'] === 'red' ? 'text-red-600 hover:bg-red-50' : 'text-zinc-700' }}"
                                    
                                    {{-- L'appel Livewire dynamique --}}
                                    wire:click="handleAction('{{ $action['name'] }}', '{{ $item->id }}')"
                                    
                                    {{-- L'INTERPRÉTATION DU @IF MARCHE PARFAITEMENT ICI --}}
                                    @if($action['confirm'])
                                        wire:confirm="{{ $action['confirm'] }}"
                                    @endif
                                >
                                    {{-- Icône --}}
                                    @if($action['icon'])
                                        <flux:icon :name="$action['icon']" class="size-4" />
                                    @endif
                                    
                                    {{-- Label --}}
                                    {{ $action['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </template>
    </div>
@endif
