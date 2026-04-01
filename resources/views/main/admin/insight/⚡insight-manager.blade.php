<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <x-flux::heading size="xl">{{  }}</x-flux::heading>
            <x-flux::subheading>Gérez vos vues personnalisées et analyses</x-flux::subheading>
        </div>

        <x-flux::button icon="plus" variant="primary" wire:click="createInsight">
            Nouveau Dashboard
        </x-flux::button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($this->insights as $insight)
            <x-flux::card class="flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <x-flux::heading size="lg">{{ $insight->name }}</x-flux::heading>
                        
                        {{-- Indicateur Favori --}}
                        <button wire:click="setFavorite({{ $insight->id }})">
                            <x-flux::icon 
                                name="star" 
                                variant="{{ $insight->is_favorite ? 'solid' : 'outline' }}" 
                                class="{{ $insight->is_favorite ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400' }} transition-colors" 
                            />
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                        {{ $insight->description ?? 'Aucune description' }}
                    </p>
                </div>

                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <x-flux::badge size="sm" variant="subtle">
                            {{ $insight->widgets_count ?? $insight->widgets()->count() }} widgets
                        </x-flux::badge>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-flux::button 
                            icon="eye" 
                            variant="subtle" 
                            size="sm" 
                            href="{{ route('admin.insights.show', $insight) }}" 
                        />
                        
                        <x-flux::dropdown>
                            <x-flux::button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <x-flux::menu>
                                <x-flux::menu.item icon="pencil-square">Modifier les infos</x-flux::menu.item>
                                <x-flux::menu.item 
                                    icon="trash" 
                                    variant="danger" 
                                    wire:click="deleteInsight({{ $insight->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce dashboard ?"
                                >
                                    Supprimer
                                </x-flux::menu.item>
                            </x-flux::menu>
                        </x-flux::dropdown>
                    </div>
                </div>
            </x-flux::card>
        @endforeach
    </div>
</div>