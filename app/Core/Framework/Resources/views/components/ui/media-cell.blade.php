@props(['value', 'item'])

<div class="flex items-center gap-3">
    {{-- La miniature carrée --}}
    <div class="size-9 rounded-lg overflow-hidden bg-zinc-100 border border-zinc-200 shrink-0">
        @php 
            $isImage = str_contains($item->mime_type ?? '', 'image');
        @endphp

        @if($isImage && isset($item->url))
            <img src="{{ $item->url }}" class="size-full object-cover" alt="{{ $value }}">
        @else
            <div class="size-full flex items-center justify-center text-zinc-400 bg-zinc-50">
                <flux:icon name="document" class="size-5" />
            </div>
        @endif
    </div>
    <div class="flex flex-col min-w-0">
        <span class="text-sm font-medium text-zinc-800 truncate">
            {{ $value }}
        </span>
    </div>
</div>