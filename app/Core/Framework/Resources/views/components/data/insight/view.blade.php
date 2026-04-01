@props([
    'widgets' => [],
    'sortable' => true,
])
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
        <div>
            <flux:heading size="xl" level="1">Tableau de bord</flux:heading>
            <flux:subheading>Analyse dynamique et configuration personnalisée</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:select wire:model.live="filters.period" class="w-48">
                <flux:select.option value="last_7_days">7 derniers jours</flux:select.option>
                <flux:select.option value="last_30_days">30 derniers jours</flux:select.option>
                <flux:select.option value="year_to_date">Année en cours</flux:select.option>
            </flux:select>

            <flux:modal.trigger name="create-insight">
                <flux:button icon="plus" variant="primary">Ajouter</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
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
        class="grid grid-cols-12 gap-x-6 gap-y-4"
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
</div>
