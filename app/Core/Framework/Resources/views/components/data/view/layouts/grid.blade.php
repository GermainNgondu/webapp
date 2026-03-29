@php
    use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;
    /** @var LayoutDiscovery $discovery */
    $discovery = app(LayoutDiscovery::class);
    
    // On récupère la configuration de la grille depuis la classe Data
    $grid = $discovery::getGridSchema($this->getDataClass());
    $items = $this->items;
    $actions = $this->getRowActions;
@endphp

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @foreach($items as $item)

        <flux:card 
            wire:key="grid-item-{{ $item->id }}" 
            ::class="selected.includes('{{ $item->id }}') ? 'ring-2 ring-zinc-500 border-transparent' : 'border-zinc-200'"
            class="relative group flex flex-col overflow-hidden p-0 transition-shadow hover:shadow-md">
            <div 
                class="absolute top-2 left-2 z-20 transition-opacity cursor-pointer"
                :class="selected.includes('{{ $item->id }}') ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
            >
                <flux:checkbox 
                    x-model="selected" 
                    value="{{ $item->id }}" 
                    class="shadow-sm bg-white"
                />
            </div>
            {{-- 1. Section IMAGE --}}
            @if(isset($grid['image']))
                <div class="relative aspect-video w-full overflow-hidden bg-zinc-100 border-b border-zinc-200 flex items-center justify-center">
                    @php 
                        $imageUrl = $item->{$grid['image']['field']};
                        // On vérifie si c'est une image (via le mime_type s'il existe dans l'item)
                        $isImage = str_contains($item->mime_type ?? '', 'image') || 
                                collect(['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])->contains(pathinfo($imageUrl, PATHINFO_EXTENSION));
                    @endphp
                    
                    @if($imageUrl && $isImage)
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="Preview" 
                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-110"
                        >
                    @else
                        {{-- PLACEHOLDER DYNAMIQUE POUR DOCUMENTS --}}
                        <div class="flex flex-col items-center gap-2 text-zinc-400">
                            @php
                                // On choisit une icône plus grande pour le centre
                                $icon = match(true) {
                                    str_contains($item->mime_type ?? '', 'pdf') => 'document-text',
                                    str_contains($item->mime_type ?? '', 'video') => 'video-camera',
                                    str_contains($item->mime_type ?? '', 'zip') => 'archive',
                                    default => 'document',
                                };
                            @endphp
                            <flux:icon :name="$icon" class="size-12 opacity-50 stroke-[1.5]" />
                            
                            @if(!$isImage)
                                <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">
                                    {{ pathinfo($imageUrl, PATHINFO_EXTENSION) ?: 'DOC' }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Badge (ton composant media-type-badge) --}}
                    @if(isset($grid['badge']))
                        <div class="absolute right-2 top-2">
                            <x-core::ui.media-type-badge :value="$item->{$grid['badge']['field']}" />
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
                    @if(count($actions ?? []) > 0)
                         <x-core::data.view.actions.row :actions="$actions" :item="$item" :grid="true"/>
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
    <x-core::data.view.parts.empty />
@endif