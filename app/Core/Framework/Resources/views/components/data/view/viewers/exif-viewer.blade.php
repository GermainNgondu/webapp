@props(['item'])

<div class="grid grid-cols-1 gap-4">
    <div class="rounded-xl overflow-hidden bg-zinc-100 flex items-center justify-center p-4">
        <img src="{{ $item->url }}" class="max-h-[300px] shadow-lg rounded" />
    </div>

    <div class="grid grid-cols-3 gap-2">
        @php $exif = $item->custom_properties['exif'] ?? []; @endphp
        
        <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-100 text-center">
            <p class="text-[10px] uppercase text-zinc-400">ISO</p>
            <p class="text-sm font-bold">{{ $exif['iso'] ?? 'N/A' }}</p>
        </div>
        <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-100 text-center">
            <p class="text-[10px] uppercase text-zinc-400">Aperture</p>
            <p class="text-sm font-bold">{{ $exif['aperture'] ?? 'N/A' }}</p>
        </div>
        <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-100 text-center">
            <p class="text-[10px] uppercase text-zinc-400">Shutter</p>
            <p class="text-sm font-bold">{{ $exif['shutter_speed'] ?? 'N/A' }}</p>
        </div>
    </div>
</div>