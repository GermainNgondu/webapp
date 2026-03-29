@php
    $items = $this->items;
    $actions = $this->getRowActions;
    /** * On utilise le resolve() optimisé qui scanne la classe une seule fois
     * et met tout en cache statique/persistant.
     */
    $discovery = \App\Core\Framework\Support\Data\View\Services\LayoutDiscovery::resolve($this->getDataClass('list'));
    
    $mapConfig = $discovery['map'];

    $label =$mapConfig['label'];
    $title =$mapConfig['title'] ?? '';
    $lat = $mapConfig['lat'] ?? 'lat';
    $lng = $mapConfig['lng'] ?? 'lng';
@endphp

<div 
    {{-- Polling intelligent : s'active uniquement en mode Live --}}
    @if($this->isLive) wire:poll.5s @endif 
    class="relative group w-full h-[650px] bg-zinc-50 rounded-3xl border border-zinc-200 shadow-sm overflow-hidden"
    x-data="{
        map: null,
        markers: [],
        
        init() {
            this.$nextTick(() => {
                this.setupMap();
            });
        },

        setupMap() {

            const firstItem = @js($items->first());
            const center = [
                firstItem?.{{ $lat }} ?? 46.2276, 
                firstItem?.{{ $lng }} ?? 2.2137
            ];

            this.map = L.map($refs.mapContainer, {
                zoomControl: false,
                scrollWheelZoom: true
            }).setView(center, 12);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            // Positionnement des contrôles de zoom en bas à droite
            L.control.zoom({ position: 'bottomright' }).addTo(this.map);

            this.updateMarkers();
        },

        updateMarkers() {
            if (!this.map) return;

            if (this.markers.length > 0) {
                this.markers.forEach(m => {
                    if (m) this.map.removeLayer(m);
                });
                this.markers = [];
            }

            const data = @js($items->items());

            data.forEach(item => {
                if (item.{{ $lat }} && item.{{ $lng }}) {
                    // Création d'un icône personnalisé (Point bleu style Transit)
                    const customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div class='flex items-center group'>
                        <div class='size-3 bg-blue-600 border-2 border-white rounded-full shadow-lg z-10'></div>
                        
                        <div class='ml-2 px-2 py-0.5 bg-white/90 backdrop-blur-sm border border-zinc-200 rounded-md shadow-sm whitespace-nowrap'>
                            <span class='text-[9px] font-bold text-zinc-800 uppercase tracking-tight'>
                                ${item.{{ $title }}}
                            </span>
                        </div>
                    </div>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });

                    const marker = L.marker([item.{{ $lat }}, item.{{ $lng }}], { icon: customIcon })
                        .addTo(this.map)
                        .bindPopup(`
                            <div class='p-3 min-w-48 font-sans'>
                                <p class='text-xs font-bold text-zinc-900 mb-1'>${item.{{ $label }}}</p>
                                <p class='text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1'>${item.{{ $title }}}</p>

                                <p class='text-xs text-zinc-500 mb-3'>Statut: <span class='capitalize'>${item.status.replace('_', ' ')}</span></p>
                                
                               
                            </div>
                        `, {
                            className: 'custom-leaflet-popup'
                        });
                    
                    this.markers.push(marker);
                }
            });
        }
    }"
    {{-- On observe les changements de données Livewire pour bouger les points sans recharger la carte --}}
    
>
    {{-- Barre de contrôle supérieure --}}
    <div class="absolute top-6 left-6 right-6 z-1000 flex justify-between items-center pointer-events-none">
        <div class="flex items-center gap-3 bg-white/90 backdrop-blur-md p-2 pl-4 rounded-2xl border border-zinc-200 shadow-xl pointer-events-auto">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-tighter">Flux Logistique</span>
                <span class="text-xs font-bold text-zinc-900">{{ $items->total() }} points actifs</span>
            </div>
            <div class="h-8 w-px bg-zinc-200 mx-2"></div>
            <flux:button 
                wire:click="toggleLive" 
                :variant="$this->isLive ? 'primary' : 'ghost'" 
                size="sm" 
                :icon="$this->isLive ? 'stop' : 'bolt'"
                class="relative overflow-hidden"
            >
                @if($this->isLive)
                    <span class="absolute inset-0 bg-blue-400/20 animate-pulse"></span>
                @endif
                {{ $this->isLive ? 'MODE LIVE' : 'ACTIVER LIVE' }}
            </flux:button>
        </div>
    </div>

    {{-- Overlay de chargement discret --}}
    <div wire:loading class="absolute inset-0 bg-white/40 backdrop-blur-[1px] z-999 flex items-center justify-center pointer-events-none transition-opacity">
        <flux:icon.arrow-path class="size-6 text-blue-600 animate-spin" />
    </div>

    {{-- Conteneur de la carte (wire:ignore est crucial ici) --}}
    <div x-ref="mapContainer" wire:ignore class="w-full h-full z-0"></div>

    <style>
        .custom-leaflet-popup .leaflet-popup-content-wrapper {
            @apply rounded-2xl border-none shadow-2xl p-0 overflow-hidden;
        }
        .custom-leaflet-popup .leaflet-popup-content {
            margin: 0 !important;
            width: auto !important;
        }
        .custom-leaflet-popup .leaflet-popup-tip {
            @apply shadow-none;
        }
        .leaflet-container {
            font-family: inherit;
        }
    </style>
</div>