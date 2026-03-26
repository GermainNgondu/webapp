<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Features\Media\Domain\Models\MediaLibrary;
use App\Features\Media\Actions\UploadMedia;
use App\Features\Media\Actions\GetMediaAction;
use Livewire\Attributes\{On, Layout};

new #[Layout('admin::layouts.admin')] class extends Component
{
    use WithFileUploads;
    use WithPagination;


    public $search = '';
    public $filterType = '';
    public $uploads = [];

    // On récupère une bibliothèque par défaut ou spécifique
    public function getLibraryProperty()
    {
        return MediaLibrary::firstOrCreate(['name' => 'all','slug'=> 'all']);
    }

    public function updatedUploads()
    {
        $this->validate(['uploads.*' => 'file|max:20480']); // 20MB

        foreach ($this->uploads as $file) {
            UploadMedia::run($this->library, $file);
        }

        $this->uploads = [];
        $this->dispatch('notify', message: 'Média importé avec succès');
    }

    #[On('media-imported')]
    public function refresh()
    {
        // Livewire rafraîchira automatiquement le rendu car les données ont changé
    }

    public function render()
    {
        return $this->view([
            'medias' => GetMediaAction::run()
        ]);
    }
};
?>

<div class="space-y-8">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">Gestionnaire de Médias</flux:heading>
        
        <div class="flex items-center gap-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Rechercher..." />
            
            <flux:modal.trigger name="media-uploader-modal">
                <flux:button variant="primary" icon="plus" as="label" for="file-upload" class="cursor-pointer">
                    Importer
                    <input id="file-upload" type="file" wire:model="uploads" multiple class="hidden">
                </flux:button>                
            </flux:modal.trigger>

        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($medias as $media)
            <flux:card class="relative group p-0 overflow-hidden cursor-pointer hover:ring-2 hover:ring-primary-500 transition-all">
                <div class="aspect-square flex items-center justify-center bg-zinc-100 dark:bg-zinc-900">
                    @if(str_contains($media->type->value, 'image'))
                        <img src="{{ $media->url }}" class="object-cover w-full h-full">
                    @else
                        <flux:icon.document class="size-10 text-zinc-400" />
                    @endif
                </div>
                
                <div class="p-2 border-t border-zinc-200 dark:border-zinc-700">
                    <p class="text-xs truncate font-medium">{{ $media->name }}</p>
                    <p class="text-[10px] text-zinc-500">{{ $media->human_readable_size }}</p>
                </div>

                {{-- Overlay d'actions --}}
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-opacity">
                    <flux:button size="sm" icon="eye" variant="ghost" />
                    <flux:button size="sm" icon="trash" variant="danger" />
                </div>
            </flux:card>
        @endforeach
    </div>
    <livewire:features::media.uploader />
</div>