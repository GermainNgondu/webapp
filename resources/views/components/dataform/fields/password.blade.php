@props(['field'])

@php
    $name = "form." . $field['name'];
    $isReadOnly = $field['readonly'] ?? false;
    $options = $field['options'] ?? [];

    // Configuration par défaut extraite des options du champ
    $config = [
        'showStrength' => $options['showStrength'] ?? false,
        'minLength'    => $options['minLength'] ?? 8,
        'useUpper'     => $options['useUpper'] ?? true,
        'useNumbers'   => $options['useNumbers'] ?? true,
        'useSpecial'   => $options['useSpecial'] ?? true,
    ];
@endphp

<flux:field {{ $attributes }}>
    
    <x-dataform.fields.label :field="$field" />

    <div x-data="{
        show: false,
        value: @entangle($name),
        config: @js($config),
        
        get strength() {
            if (!this.value) return 0;
            let score = 0;
            if (this.value.length >= this.config.minLength) score++;
            if (this.config.useUpper && /[A-Z]/.test(this.value)) score++;
            if (this.config.useNumbers && /[0-9]/.test(this.value)) score++;
            if (this.config.useSpecial && /[^A-Za-z0-9]/.test(this.value)) score++;
            return score;
        },

        get maxScore() {
            return 1 + (this.config.useUpper ? 1 : 0) + (this.config.useNumbers ? 1 : 0) + (this.config.useSpecial ? 1 : 0);
        },

        get strengthColor() {
            let percent = (this.strength / this.maxScore) * 100;
            if (percent <= 25) return 'bg-red-500';
            if (percent <= 50) return 'bg-orange-500';
            if (percent <= 75) return 'bg-yellow-500';
            return 'bg-green-500';
        }
    }" class="space-y-3">
        
        <div class="relative">
            <flux:input 
                x-model="value"
                ::type="show ? 'text' : 'password'"
                :disabled="$isReadOnly"
                placeholder="••••••••"
            >
                <x-slot name="iconTrailing">
                    <button type="button" @click="show = !show" class="flex items-center justify-center text-zinc-400 hover:text-zinc-600 focus:outline-none pr-1">
                        {{-- Icône Eye (Afficher) --}}
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        {{-- Icône Eye-Slash (Cacher) --}}
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </x-slot>
            </flux:input>
        </div>

        {{-- Barre de force --}}
        <div x-show="config.showStrength && value && !@js($isReadOnly)" x-transition>
            <div class="flex gap-1.5 h-1.5 mt-2">
                {{-- On génère physiquement les segments pour éviter le template x-for qui peut bugger ici --}}
                <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 1 ? strengthColor : 'bg-zinc-100 dark:bg-zinc-800'"></div>
                <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 2 ? strengthColor : 'bg-zinc-100 dark:bg-zinc-800'"></div>
                <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 3 ? strengthColor : 'bg-zinc-100 dark:bg-zinc-800'"></div>
                <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 4 ? strengthColor : 'bg-zinc-100 dark:bg-zinc-800'"></div>
            </div>
            <p class="text-[10px] uppercase font-bold tracking-widest mt-2 text-zinc-500">
                Sécurité : <span :class="strength === maxScore ? 'text-green-600' : ''" x-text="['Insuffisant', 'Faible', 'Moyen', 'Bon', 'Fort'][strength]"></span>
            </p>
        </div>
    </div>

    <flux:error :name="$name" />
</flux:field>