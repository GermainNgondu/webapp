@props(['items', 'schema', 'actions' => []])

@php
    $grid = \App\Core\Framework\Support\DataView\Services\LayoutDiscovery::getGridSchema($this->getDataClass());
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($items as $item)
        <flux:card wire:key="grid-item-{{ $item->id }}" class="p-0 overflow-hidden flex flex-col h-full hover:border-zinc-500 transition-colors">
            
            {{-- 1. Section IMAGE --}}
            @if(isset($grid['image']))
                <div class="relative aspect-video w-full overflow-hidden bg-zinc-800 border-b border-zinc-800">
                    @if($item->{$grid['image']['field']})
                        <img 
                            src="{{ $item->{$grid['image']['field']} }}" 
                            alt="Cover" 
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                        >
                    @else
                        {{-- Placeholder si pas d'image --}}
                        <div class="flex items-center justify-center h-full">
                            <flux:icon icon="photo" variant="outline" class="text-zinc-600 size-10" />
                        </div>
                    @endif

                </div>
            @endif

            {{-- 2. Section CONTENU (avec padding maintenant que la carte est en p-0) --}}
            <div class="p-4 flex flex-col flex-1">
                <div class="mb-2 flex items-center justify-between">
                    <div>
                        @if(isset($grid['title']))
                            <flux:heading size="lg">{{ $item->{$grid['title']['field']} }}</flux:heading>
                        @endif
                        
                        @if(isset($grid['subtitle']))
                            <flux:text color="zinc" size="sm">{{ $item->{$grid['subtitle']['field']} }}</flux:text>
                        @endif                        
                    </div>

                    <x-core::dataview.actions.row :actions="$actions['row'] ?? []" :item="$item" :grid="true" />
                </div>

                @if(isset($grid['description']))
                    <div class="flex-1 mt-4">
                        <flux:text size="sm" class="line-clamp-2">
                            {{ $item->{$grid['description']['field']} }}
                        </flux:text>
                    </div>
                @endif

                <flux:separator variant="subtle" class="my-4" />

                {{-- Footer --}}
                <div class="flex justify-between items-center mt-auto">
                    @if(isset($grid['footer']))
                            <div class="flex items-center gap-2 text-zinc-400">
                                @if($grid['footer']['icon'])
                                    <flux:icon :icon="$grid['footer']['icon']" variant="micro" />
                                @endif
                                <flux:text size="xs">{{ $item->{$grid['footer']['field']} }}</flux:text>
                            </div>
                    @endif
                    
                </div>
            </div>
        </flux:card>
    @endforeach
</div>