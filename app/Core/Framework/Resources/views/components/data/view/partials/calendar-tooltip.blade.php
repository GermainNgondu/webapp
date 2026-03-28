<div class="p-3 w-64 space-y-3 font-sans">
    @if(isset($item->url) && str_contains($item->mime_type ?? '', 'image'))
        <img src="{{ $item->url }}" class="w-full h-24 object-cover rounded-lg shadow-inner">
    @endif
    
    <div>
        <p class="text-xs font-bold text-zinc-900 leading-tight">{{ $item->title ?? $item->file_name }}</p>
        <p class="text-[10px] text-zinc-500 mt-1 line-clamp-2">{{ $item->description ?? 'Aucune description' }}</p>
    </div>

    <div class="flex items-center justify-between pt-2 border-t border-zinc-100">
        <span class="text-[9px] font-mono text-zinc-400">#{{ $item->id }}</span>
        <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-zinc-100 text-zinc-600 uppercase">
            {{ $item->status ?? 'Info' }}
        </span>
    </div>
</div>