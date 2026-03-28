@props(['items', 'schema'])

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div 
    x-data="{
        map: null,
        markerObjects: {},
        isLive: @entangle('isLive'),
        
        initMap() {
            this.map = L.map($refs.map).setView([46.2, 2.2], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);
            this.updateMarkers(@js($this->mapMarkers));
        },

        updateMarkers(newMarkers) {
            newMarkers.forEach(marker => {
                let exists = this.markerObjects[marker.id];
                
                // Style dynamique : Pulsation si Live, Simple si Statique
                let iconHtml = this.isLive 
                    ? `<div class='relative'><span class='absolute h-3 w-3 rounded-full bg-zinc-500 animate-ping opacity-75'></span><span class='relative rounded-full h-3 w-3 bg-zinc-600 border-2 border-white shadow-sm'></span></div>`
                    : `<div class='h-3 w-3 rounded-full bg-zinc-500 border-2 border-white shadow-sm'></div>`;

                let icon = L.divIcon({ className: 'custom-marker', html: iconHtml, iconSize: [12, 12] });

                if (exists) {
                    exists.setLatLng([marker.lat, marker.lng]);
                    exists.setIcon(icon);
                } else {
                    this.markerObjects[marker.id] = L.marker([marker.lat, marker.lng], { icon: icon })
                        .addTo(this.map)
                        .bindPopup(`
                            <div class='text-center'>
                                ${marker.preview ? `<img src='${marker.preview}' class='w-20 h-12 object-cover rounded mb-1 mx-auto'>` : ''}
                                <p class='text-[10px] font-bold'>${marker.label}</p>
                                <button onclick='window.Livewire.find(\"{{ $_instance->getId() }}\").showItem(\"${marker.id}\")' class='text-[9px] text-zinc-600 font-bold uppercase mt-1'>Détails</button>
                            </div>
                        `);
                }
            });
        }
    }"
    x-init="initMap()"
    x-effect="updateMarkers(@js($this->mapMarkers))"
    class="relative w-full h-[70vh] rounded-2xl overflow-hidden border border-zinc-200 shadow-inner"
>
    {{-- Polling Livewire conditionnel --}}
    @if($isLive)
        <div wire:poll.5s class="hidden"></div>
    @endif

    {{-- Controls Overlay --}}
    <div class="absolute top-4 right-4 z-1000 flex flex-col gap-2">
        <flux:button 
            wire:click="toggleLive" 
            :variant="$isLive ? 'filled' : 'white'" 
            size="sm"
            class="{{ $isLive ? 'animate-pulse' : '' }}"
        >
            <div class="flex items-center gap-2">
                <span @class(['size-2 rounded-full', 'bg-green-500' => $isLive, 'bg-zinc-300' => !$isLive])></span>
                <span class="text-[10px] font-bold uppercase tracking-widest">{{ $isLive ? 'Live Tracking' : 'Statique' }}</span>
            </div>
        </flux:button>
        
        <flux:button icon="viewfinder-circle" size="sm" variant="white" x-on:click="map.fitBounds(Object.values(markerObjects).map(m => m.getLatLng()))" />
    </div>

    <div x-ref="map" class="w-full h-full z-0"></div>
</div>