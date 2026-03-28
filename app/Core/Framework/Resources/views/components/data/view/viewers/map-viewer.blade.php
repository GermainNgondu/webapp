@props(['item'])

<div class="w-full rounded-xl overflow-hidden border border-zinc-200 shadow-sm">
    @php
        $lat = $item->custom_properties['location']['lat'] ?? 48.8566;
        $lng = $item->custom_properties['location']['lng'] ?? 2.3522;
    @endphp
    
    {{-- Utilisation d'un embed OpenStreetMap (Gratuit et sans clé API) --}}
    <iframe 
        width="100%" 
        height="300" 
        frameborder="0" 
        scrolling="no" 
        marginheight="0" 
        marginwidth="0" 
        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lng-0.01 }}%2C{{ $lat-0.01 }}%2C{{ $lng+0.01 }}%2C{{ $lat+0.01 }}&amp;layer=mapnik&amp;marker={{ $lat }}%2C{{ $lng }}"
        class="grayscale contrast-125"
    ></iframe>
    
    <div class="bg-white p-3 flex items-center justify-between border-t border-zinc-100">
        <div class="flex items-center gap-2">
            <x-heroicon-s-map-pin class="size-4 text-rose-500" />
            <span class="text-xs font-medium text-zinc-600">{{ $lat }}, {{ $lng }}</span>
        </div>
        <flux:button size="xs" variant="ghost" href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" target="_blank">Ouvrir dans Maps</flux:button>
    </div>
</div>