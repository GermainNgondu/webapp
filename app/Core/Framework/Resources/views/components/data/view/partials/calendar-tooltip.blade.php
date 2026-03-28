<div class="p-4 w-72 space-y-3 font-sans">
    {{-- 1. Image dynamique (si présente dans l'item) --}}
    @if(isset($item->url) || isset($item->thumbnail))
        <div class="relative w-full h-32 bg-zinc-100 rounded-lg overflow-hidden border border-zinc-100 shadow-inner">
            <img src="{{ $item->url ?? $item->thumbnail }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
    @endif

    {{-- 2. Contenu Textuel via #[Quick] --}}
    <div class="space-y-1">
        <p class="text-[13px] font-bold text-zinc-900 leading-tight">
            {{ $item->{$calendar['label']} }}
        </p>
        
        @if($calendar['description'] && $item->{$calendar['description']})
            <p class="text-[11px] text-zinc-500 line-clamp-3 leading-relaxed">
                {{ $item->{$calendar['description']} }}
            </p>
        @else
            <p class="text-[11px] text-zinc-400 italic">Aucune description disponible.</p>
        @endif
    </div>

    {{-- 3. Footer : Statut via #[KanbanGroup] --}}
    <div class="flex items-center justify-between pt-3 border-t border-zinc-100">
        @php 
            $statusField = $kanban['field'] ?? 'status';
            $statusLabel = $kanban['options'][$item->{$statusField}] ?? $item->{$statusField} ?? 'Info';
        @endphp

        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-zinc-100 text-zinc-600 uppercase tracking-wider">
            {{ $statusLabel }}
        </span>
    </div>
</div>