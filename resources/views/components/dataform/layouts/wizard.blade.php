{{-- resources/views/components/dataform/layouts/wizard.blade.php --}}
@props(['steps'])

<div 
    x-data="{ 
        currentStep: 0, 
        totalSteps: {{ count($steps) }},
        isSaving: false,

        async next() {
            if (this.currentStep < this.totalSteps - 1) {
                this.isSaving = true;
                
                // On appelle la méthode de sauvegarde partielle de Livewire
                // On attend que la sauvegarde soit réussie avant de changer d'étape
                let success = await $wire.saveStep(this.currentStep);
                
                this.isSaving = false;
                
                if (success) {
                    this.currentStep++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        },
        prev() { if (this.currentStep > 0) this.currentStep--; }
    }"
    class="space-y-8 mt-4"
>
    {{-- Stepper (Barre de progression) --}}
    <nav aria-label="Progress">
        <ol role="list" class="flex items-center">
            @foreach($steps as $index => $step)
                <li class="relative {{ !$loop->last ? 'flex-1' : '' }}">
                    <div class="flex items-center group">
                        {{-- Cercle de l'étape --}}
                        <div 
                           class="relative flex h-12 w-12 items-center justify-center transition-all duration-500"
                            :class="{
                                'border-zinc-600  scale-110': currentStep === {{ $index }},
                                'border-emerald-500 bg-emerald-500 rounded-full border-2': currentStep > {{ $index }},
                                'border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700': currentStep < {{ $index }}
                            }"
                        >
                            <template x-if="currentStep > {{ $index }}">
                                <flux:icon name="check" variant="mini" class="h-6 w-6 text-white" />
                            </template>
                            <template x-if="currentStep <= {{ $index }}">
                                <div :class="currentStep === {{ $index }} ? 'text-zinc-600' : 'text-zinc-400'">
                                    @if($step['icon'])
                                        <flux:icon :name="$step['icon']" variant="mini" class="h-6 w-6" />
                                    @else
                                        <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </template>

                            {{-- Tooltip / Label --}}
                            <div class="absolute -bottom-5 w-max text-center">
                                <span class="text-[11px] font-bold uppercase tracking-wider transition-colors truncate" :class="currentStep === {{ $index }} ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'">
                                    {{ $step['title'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Ligne de liaison --}}
                        @if(!$loop->last)
                            <div class="ml-4 h-0.5 w-full bg-zinc-200 dark:bg-zinc-700">
                                <div class="h-full bg-zinc-600 transition-all duration-500" :style="'width: ' + (currentStep > {{ $index }} ? '100%' : '0%')"></div>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>

    {{-- Zone de Contenu --}}
    <div class="relative min-h-[400px] mt-6 pt-8">
        {{-- Loader de sauvegarde intermédiaire --}}
        <div x-show="isSaving" x-cloak class="absolute mt-6 inset-0 z-10 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-[2px] flex items-center justify-center rounded-xl">
            <div class="flex flex-col items-center gap-3">
                <flux:icon name="arrow-path" class="h-8 w-8 animate-spin text-zinc-600" />
            </div>
        </div>
        @foreach($steps as $index => $step)
            <div 
                x-show="currentStep === {{ $index }}" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="space-y-6"
            >
                <div>
                    @if($step['description'])
                        <flux:subheading>{{ $step['description'] }}</flux:subheading>
                    @endif
                </div>

                <x-dataform.render.fields :fields="$step['fields']" />
            </div>
        @endforeach
    </div>

    {{-- Navigation --}}
    <div class="flex justify-between items-center pt-6 border-t dark:border-zinc-800">

        <flux:button 
            variant="ghost" 
            icon="chevron-left" 
            @click="prev()" 
            x-show="currentStep > 0"
            class="cursor-pointer"
        >
            Précédent
        </flux:button>
        
        <div class="ml-auto flex gap-3">
            <flux:button 
                variant="filled" 
                @click="next()" 
                x-show="currentStep < totalSteps - 1"
                ::disabled="isSaving"
                wire:loading.attr="disabled"
                wire:target="saveStep"
                class="min-w-[120px] cursor-pointer"
            >
                <span x-show="!isSaving">Continuer</span>
                
                <span x-show="isSaving" x-cloak class="flex items-center gap-2">
                    <flux:icon name="arrow-path" class="h-4 w-4 animate-spin" />
                    Validation...
                </span>
            </flux:button>

            <flux:button 
                type="submit" 
                variant="primary" 
                x-show="currentStep === totalSteps - 1"
                ::disabled="isSaving"
                wire:loading.attr="disabled"
                class="cursor-pointer"
            >
                <span wire:loading.remove wire:target="save">Finaliser et Enregistrer</span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <flux:icon name="arrow-path" class="h-4 w-4 animate-spin" />
                    Enregistrement...
                </span>
            </flux:button>
        </div>
    </div>
</div>