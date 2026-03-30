<div x-show="tab === 'local'" x-cloak class="space-y-4">

    <div 
        x-data="{
            dropzone: null,
            init() {
                this.dropzone = new window.Dropzone($refs.dzContainer, {
                    url: '/', // Non utilisé
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 20,
                    maxFilesize: 50, // 50MB
                    acceptedFiles: 'image/*,application/pdf,video/*',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Retirer',
                    init: function() {
                        this.on('addedfiles', (files) => {
                            // On passe les fichiers à Livewire
                            @this.uploadMultiple('uploads', files, 
                                (uploadedNames) => {

                                }, 
                                (error) => {
                                    console.error('Erreur upload:', error);
                                }
                            );
                        });
                        this.on('removedfile', (file) => {
                            @this.removeUpload('uploads', file.name);
                        });
                    }
                });
            }
        }"
    >
        {{-- Zone Dropzone --}}
        <div x-ref="dzContainer" class="dropzone p-12 dark:border-white">
            <div class="dz-message">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="p-4 bg-white dark:bg-zinc-800 rounded-full shadow-sm cursor-pointer">
                        <flux:icon.arrow-up-tray class="size-8 text-zinc-500 dark:text-white" />
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">
                            Glissez-déposez vos fichiers
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LISTE DES ÉLÉMENTS--}}
        @if ($uploads)
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1 py-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-zinc-400">File d'attente</span>
                    <flux:button variant="ghost" size="sm" wire:click="$set('uploads', [])" class="cursor-pointer">Tout effacer</flux:button>
                </div>

                <div class="grid grid-cols-1 gap-2 max-h-[200px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach ($uploads as $index => $file)
                        <div wire:key="temp-file-{{ $file->getFilename() }}" class="flex items-center gap-4 p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm group">
                            {{-- Thumbnail ou Icon --}}
                            <div class="size-12 rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-800 flex shrink-0 items-center justify-center">
                                @if (str_starts_with($file->getMimeType(), 'image/'))
                                    <img src="{{ $file->temporaryUrl() }}" class="object-cover size-full">
                                @else
                                    <flux:icon.document class="size-6 text-zinc-400" />
                                @endif
                            </div>

                            {{-- Infos fichier --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                    {{ $file->getClientOriginalName() }}
                                </p>
                                <p class="text-xs text-zinc-500">
                                    {{ Number::fileSize($file->getSize()) }} • <span class="uppercase">{{ $file->getClientOriginalExtension() }}</span>
                                </p>
                            </div>

                            {{-- Action : Supprimer de la liste --}}
                            <flux:button 
                                icon="trash" 
                                variant="ghost" 
                                size="sm" 
                                wire:click="suppr('{{ $file->getFilename() }}')"
                                class=" text-zinc-400 hover:text-red-500 cursor-pointer"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
</div>