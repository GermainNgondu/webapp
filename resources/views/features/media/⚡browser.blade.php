<?php

use Livewire\Component;
use Livewire\Attributes\{On, Lazy};
use App\Features\Media\Actions\GetMediaAction;


new #[Lazy]class extends Component
{
public $targetProperty; // La propriété du formulaire à mettre à jour
    public $isOpen = false;
    public $search = '';

    #[On('open-media-picker')]
    public function open($property)
    {
        $this->targetProperty = $property;
        $this->modal('media-selector-modal')->show();
    }

    public function selectMedia($mediaId)
    {
        // On met à jour la propriété dans le composant parent (le DataForm)
        $this->dispatch('media-selected', property: $this->targetProperty, id: $mediaId);
        $this->modal('media-selector-modal')->close();
    }

    public function render()
    {
        return $this->view([
            'medias' => GetMediaAction::run()->where('name', 'like', "%{$this->search}%")->paginate(12)
        ]);
    }
};
?>

<div> {{-- <-- On déclare et on lie la variable ici --}}
    <flux:modal name="media-selector-modal" class="md:w-7xl p-0">
        <div class="flex h-[600px] bg-white dark:bg-zinc-900" x-data="{ view: 'browse' }">
            {{-- Sidebar Mini --}}
            <div class="w-16 border-r border-zinc-200 dark:border-zinc-800 flex flex-col items-center py-4 gap-4">
                <button type="button" @click="view = 'browse'" :class="view === 'browse' ? 'text-primary-600 font-bold' : 'text-zinc-400 cursor-pointer' ">
                    <flux:icon.layout-grid variant="micro" />
                </button>
                <button type="button" @click="view = 'upload'" :class="view === 'upload' ? 'text-primary-600 font-bold' : 'text-zinc-400 cursor-pointer' ">
                    <flux:icon.plus variant="micro" />
                </button>
            </div>

            <div class="flex-1 flex flex-col min-w-0">
                {{-- Vue Sélection --}}
                <div x-show="view === 'browse'" class="flex-1 flex flex-col p-6 overflow-hidden">
                    <div class="flex justify-between items-center mb-6 mt-5">
                        <flux:heading size="lg">Sélectionner un média</flux:heading>
                        <flux:input wire:model.live="search" placeholder="Rechercher..." icon="magnifying-glass" size="sm" />
                    </div>

                    <div class="grid grid-cols-4 gap-4 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($medias as $media)
                            <div 
                                wire:click="selectMedia({{ $media->id }})"
                                class="group relative aspect-square rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden cursor-pointer hover:border-primary-500 transition-all shadow-sm"
                            >
                                <img src="{{ $media->getUrl() }}" class="object-cover size-full">
                                <div class="absolute inset-0 bg-primary-600/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <flux:button type="button" class="cursor-pointer" variant="primary" size="sm" icon="check" circle />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $medias->links() }}
                    </div>
                </div>

                {{-- Vue Upload --}}
                <div x-show="view === 'upload'" class="flex-1 overflow-hidden">
                    <livewire:features::media.uploader :browser="true"/>
                </div>
            </div>
        </div>
    </flux:modal>
</div>