@error('form_global')
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition 
        class="mb-6 p-4 rounded-xl border flex items-start gap-3 bg-red-50 border-red-200 text-red-800 dark:bg-red-900/10 dark:border-red-900/30 dark:text-red-400"
    >
        {{-- Icône --}}
        <div class="shrink-0">
            <flux:icon name="exclamation-triangle" variant="mini" class="h-5 w-5" />
        </div>

        {{-- Message --}}
        <div class="flex-1 text-sm font-medium">
            {{ $message }}
        </div>

        {{-- Bouton de fermeture --}}
        <button 
            type="button" 
            @click="show = false" 
            class="shrink-0 text-red-500 hover:text-red-700 dark:hover:text-red-300 transition-colors"
        >
            <flux:icon name="x-mark" variant="mini" class="h-5 w-5" />
        </button>
    </div>
@enderror