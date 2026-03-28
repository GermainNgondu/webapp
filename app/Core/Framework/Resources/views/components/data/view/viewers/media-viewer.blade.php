@props(['item', 'value'])

<div class="w-full rounded-xl bg-zinc-900 overflow-hidden flex items-center justify-center min-h-[300px] shadow-inner">
    @php
        $isYoutube = str_contains($value, 'youtube.com') || str_contains($value, 'youtu.be');
    @endphp

    @if($isYoutube)
        <iframe src="https://www.youtube.com/embed/{{ Str::afterLast($value, '/') }}" class="aspect-video w-full" allowfullscreen></iframe>
    @elseif(str_contains($item->mime_type, 'video'))
        <video controls class="w-full max-h-[500px]"><source src="{{ $value }}" type="{{ $item->mime_type }}"></video>
    @elseif(str_contains($item->mime_type, 'audio'))
        <div class="p-12 w-full"><audio controls class="w-full"><source src="{{ $value }}" type="{{ $item->mime_type }}"></audio></div>
    @elseif(str_contains($item->mime_type, 'image'))
        <img src="{{ $value }}" class="max-h-[600px] w-full object-contain">
    @else
        <div class="text-center p-12">
            <x-heroicon-o-document class="size-16 text-zinc-700 mx-auto mb-4" />
            <flux:button href="{{ $value }}" target="_blank" icon="arrow-down-tray" variant="ghost">Télécharger</flux:button>
        </div>
    @endif
</div>