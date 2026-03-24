{{-- resources/views/components/dataform/footer.blade.php --}}
@props(['target' => 'save','saveLabel'=> 'save', 'backUrl' => null])

<div 
    x-data="{ isDirty: false }"
    @change.window="isDirty = true"
    class="sticky bottom-0 z-20 mt-8 -mx-6 px-6 py-4  backdrop-blur-md"
>
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <div class="flex items-center gap-2">
            <template x-if="isDirty">
                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-xs font-medium uppercase tracking-wider">Modifications non enregistrées</span>
                </div>
            </template>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            @if ($backUrl)
                <flux:button 
                    href="{{ $backUrl }}" 
                    variant="ghost"
                    class="cursor-pointer"
                >
                    Annuler
                </flux:button>
            @endif

            <flux:button 
                type="submit" 
                variant="primary" 
                wire:click="{{ $target }}"
                wire:loading.attr="disabled"
                class="min-w-[120px] cursor-pointer"
            >
                {{-- Affichage du loader pendant la sauvegarde --}}
                <span wire:loading.remove wire:target="{{ $target }}">
                    {{ ucfirst($saveLabel) }}
                </span>
                
                <span wire:loading wire:target="{{ $target }}" class="flex items-center gap-2">
                    <flux:icon name="arrow-path" class="h-4 w-4 animate-spin" />
                    Traitement...
                </span>
            </flux:button>
        </div>
    </div>
</div>