@props(['items', 'schema'])
@php
    $config = \App\Core\Framework\Support\Data\View\Services\LayoutDiscovery::getKanbanConfig($this->resource::listData());
    $groups = $config['options'] ?? [];
    $field = $config['field'];
    $groupedItems = $items->groupBy($field);
@endphp

<div class="flex overflow-x-auto pb-8 gap-6 items-start scrollbar-hide px-2">
    @foreach($groups as $statusValue => $statusLabel)
        <div 
            class="shrink-0 w-80 flex flex-col rounded-2xl bg-zinc-50/50 border border-zinc-100 p-4 min-h-[600px]"
            x-data="{ 
                init() {
                    new Sortable($refs.list, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-40',
                        onEnd: (evt) => {
                            if (evt.from !== evt.to) {
                                $wire.updateItemStatus(evt.item.dataset.id, evt.to.dataset.status);
                            }
                        }
                    });
                } 
            }"
        >
            {{-- Header de colonne --}}
            <div class="flex items-center justify-between mb-4 px-1">
                <flux:heading size="sm" class="font-bold uppercase tracking-widest text-zinc-500">{{ $statusLabel }}</flux:heading>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-zinc-200 text-zinc-600">{{ $groupedItems->get($statusValue)?->count() ?? 0 }}</span>
            </div>

            {{-- Zone de Drop --}}
            <div x-ref="list" data-status="{{ $statusValue }}" class="flex-1 space-y-3">
                @foreach($groupedItems->get($statusValue) ?? [] as $item)
                    <div 
                        data-id="{{ $item->id }}" 
                        @click="$wire.showItem('{{ $item->id }}')"
                        class="bg-white border border-zinc-200 p-4 rounded-xl shadow-sm hover:shadow-md cursor-grab active:cursor-grabbing transition-all"
                    >
                        <div class="flex flex-col gap-2">
                            <span class="text-[10px] font-mono text-zinc-400">#{{ $item->id }}</span>
                            <p class="text-sm font-semibold leading-tight text-zinc-800">{{ $item->file_name }}</p>
                            @if(isset($item->url) && str_contains($item->mime_type ?? '', 'image'))
                                <img src="{{ $item->url }}" class="w-full h-24 object-cover rounded-lg mt-1 pointer-events-none">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Quick Add Button --}}
            <button wire:click="quickCreate('{{ $statusValue }}')" class="mt-4 w-full py-2 border border-dashed border-zinc-200 rounded-lg text-xs text-zinc-400 hover:bg-zinc-100 transition-colors">+ Ajouter</button>
        </div>
    @endforeach
</div>