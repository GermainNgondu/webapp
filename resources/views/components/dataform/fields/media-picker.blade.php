@props(['field','model'])

<flux:field {{ $attributes }}>
    
    <x-dataform.fields.label :field="$field" />

    <div class="mt-2 flex items-center gap-4 p-3 border border-zinc-200 dark:border-zinc-800 rounded-2xl bg-zinc-50/50 dark:bg-zinc-950/50 transition-all">
        
        {{-- 1. Zone de prévisualisation --}}
        <div class="size-20 rounded-xl overflow-hidden bg-zinc-200 dark:bg-zinc-800 flex shrink-0 items-center justify-center border border-zinc-200 dark:border-zinc-700 shadow-sm">
            @if($model)
                @php 
                    // On récupère le média via son ID (stocké dans le model du DataForm)
                    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($model); 
                @endphp

                @if($media)
                    @if(str_starts_with($media->mime_type, 'image/'))
                        <img src="{{ $media->getUrl() }}" class="object-cover size-full" alt="{{ $media->name }}">
                    @else
                        {{-- Icône pour les fichiers non-images ou vidéos --}}
                        <div class="flex flex-col items-center gap-1">
                            <flux:icon.document-text class="size-8 text-zinc-400" />
                            <span class="text-[10px] font-bold uppercase text-zinc-500">{{ $media->extension }}</span>
                        </div>
                    @endif
                @else
                    {{-- Si l'ID existe mais le média est introuvable --}}
                    <flux:icon.exclamation-triangle class="size-8 text-amber-500" />
                @endif
            @else
                {{-- Placeholder si rien n'est sélectionné --}}
                <flux:icon.photo class="size-8 text-zinc-400/50" />
            @endif
        </div>

        {{-- 2. Informations sur le média --}}
        <div class="flex-1 min-w-0">
            @php 
                // C'est ICI que la variable $media est créée.
                // On cherche dans la table 'media' de Spatie l'entrée correspondant à l'ID.
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($model); 
            @endphp
            @if($model && isset($media) && $media)
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-zinc-900 dark:text-white truncate" title="{{ $media->name }}">
                        {{ $media->name }}
                    </span>
                    <span class="text-xs text-zinc-500">
                        {{ $media->human_readable_size }} • {{ strtoupper($media->extension) }}
                    </span>
                    
                    @if($media->getCustomProperty('is_video'))
                        <div class="flex items-center gap-1 mt-1">
                            <flux:badge color="zinc" size="sm" inset="top bottom">Vidéo</flux:badge>
                            <span class="text-[10px] text-zinc-400">{{ ucfirst($media->getCustomProperty('video_provider')) }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-col">
                    <span class="text-sm text-zinc-500 italic">Aucun média sélectionné</span>
                    <span class="text-xs text-zinc-400">Cliquez sur "Choisir" pour explorer la bibliothèque</span>
                </div>
            @endif
        </div>

        {{-- 3. Actions --}}
        <div class="flex items-center gap-2">
            @if($model)
                {{-- Bouton pour vider la sélection --}}
                <flux:button 
                    variant="ghost" 
                    size="sm" 
                    icon="trash" 
                    inset="top bottom"
                    {{-- On utilise $set de Livewire pour remettre la propriété à null --}}
                    wire:click="$set('{{ $attributes->get('wire:model') }}', null)" 
                    class="text-zinc-400 hover:text-red-500 cursor-pointer"
                />
            @endif
            
            {{-- Bouton pour ouvrir la modale de sélection --}}
            <flux:button 
                variant="filled" 
                size="sm" 
                icon="plus"
                {{-- On dispatch l'événement qui sera écouté par MediaSelectorModal --}}
                wire:click="$dispatch('open-media-picker', { property: '{{ $attributes->get('wire:model') }}' })"
                class="cursor-pointer"
            >
                Choisir
            </flux:button>
        </div>
    </div>

    @if($field['description'])
        <flux:description class="mt-2">{{ $field['description'] }}</flux:description>
    @endif

    <flux:error :name="$attributes->get('wire:model')" />
</flux:field>