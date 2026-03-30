@props([
    'widgets' => [],
    'sortable' => true,
])

<div 
    {{-- Initialisation de Sortable via Alpine.js --}}
    x-data="{
        init() {
            if (@js($sortable)) {
                new Sortable($el, {
                    group: 'insights',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    handle: '[data-sort-handle]', {{-- On limite le drag au bouton handle --}}
                    onEnd: (evt) => {
                        {{-- On récupère le nouvel ordre des propriétés --}}
                        let order = Array.from(evt.to.children).map(el => el.getAttribute('data-id'));
                        {{-- On envoie l'événement à Livewire pour sauvegarder --}}
                        $wire.updateWidgetOrder(order);
                    }
                });
            }
        }
    }"
    class="grid grid-cols-1 md:grid-cols-12 gap-6"
>
    @foreach($widgets as $widget)
        <div 
            data-id="{{ $widget['property'] }}"
            class="col-span-12 md:col-span-{{ $widget['config']['colSpan'] ?? 12 }}"
        >
            @livewire('insight.widget', ['widget' => $widget], key($widget['property']))
        </div>
    @endforeach
</div>