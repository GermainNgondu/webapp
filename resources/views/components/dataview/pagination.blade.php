@props(['items'])
<div class="mt-8 pt-6">
        <div class="flex items-center justify-between">
            {{-- Sélecteur de quantité --}}
            <div class="flex items-center gap-2">
                <flux:label class="hidden sm:block">Afficher</flux:label>
                <flux:select wire:model.live="perPage" size="sm" class="w-20">
                    <flux:select.option value="10">10</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                    <flux:select.option value="100">100</flux:select.option>
                </flux:select>
                <flux:text size="sm">résultats</flux:text>
            </div>

            {{-- Liens de pagination --}}
            {{-- Livewire détecte automatiquement le paginateur et affiche les liens --}}
            <div>
                {{ $items->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
</div>