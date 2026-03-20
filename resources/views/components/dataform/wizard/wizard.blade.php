@props(['steps'])

<div x-data="{
    currentStep: 0,
    steps: {{ json_encode($steps) }},
    loading: false,

    async next() {
        if (this.currentStep >= this.steps.length - 1) return;

        this.loading = true;
        try {
            // On demande à Livewire de valider uniquement les champs de l'étape actuelle
            await $wire.validateStep(this.steps[this.currentStep].validation_fields);
            
            // Si c'est valide, on passe à la suite
            this.currentStep++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (error) {
            // Les erreurs sont gérées par Livewire et affichées par flux:error
        } finally {
            this.loading = false;
        }
    },

    previous() {
        if (this.currentStep > 0) this.currentStep--;
    }
}" class="w-full">

    <nav class="mb-12">
        <ul class="flex items-start gap-4">
            <template x-for="(step, index) in steps" :key="index">
                <li class="flex-1">
                    <div :class="index <= currentStep ? 'border-zinc-600' : 'border-zinc-200 dark:border-zinc-700'"
                         class="border-t-4 pt-4 transition-colors duration-500">
                        <span :class="index <= currentStep ? 'text-zinc-600' : 'text-zinc-500'" 
                              class="text-xs font-bold uppercase tracking-wider" 
                              x-text="'Étape ' + (index + 1)"></span>
                        <p class="font-medium dark:text-white mt-1" x-text="step.meta.name"></p>
                    </div>
                </li>
            </template>
        </ul>
    </nav>

    <div class="min-h-[400px]">
        <template x-for="(step, index) in steps" :key="index">
            <div x-show="currentStep === index" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="grid grid-cols-12 gap-6">
                
                <div class="col-span-12 mb-6">
                    <h2 class="text-2xl font-bold dark:text-white" x-text="step.meta.name"></h2>
                    <p class="text-zinc-500 dark:text-zinc-400" x-text="step.meta.description"></p>
                </div>

                <template x-for="field in step.fields" :key="field.name">
                    <div :class="'col-span-' + (field.colSpan || 12)">
                        <x-dataform.dynamic-field ::field="field" />
                    </div>
                </template>
            </div>
        </template>
    </div>

    <div class="mt-12 pt-6 border-t dark:border-zinc-800 flex justify-between items-center">
        <flux:button x-show="currentStep > 0" @click="previous()" variant="ghost" icon="chevron-left">
            Précédent
        </flux:button>
        <div x-show="currentStep === 0"></div>

        <div class="flex items-center gap-4">
            <flux:button x-show="currentStep < steps.length - 1" 
                         @click="next()" 
                         variant="primary" 
                         trailing-icon="chevron-right"
                         :loading="loading">
                Suivant
            </flux:button>

            <flux:button x-show="currentStep === steps.length - 1" 
                         type="submit" 
                         variant="primary" 
                         wire:click="save"
                         :loading="loading">
                Enregistrer le dossier
            </flux:button>
        </div>
    </div>
</div>