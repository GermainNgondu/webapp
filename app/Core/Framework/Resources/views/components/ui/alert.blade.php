@props(['message','type' => 'info'])

@php
    $color = match($type){
        'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/10 dark:border-blue-900/30 dark:text-blue-400',
        'success' => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/10 dark:border-green-900/30 dark:text-green-400',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/10 dark:border-yellow-900/30 dark:text-yellow-400',
        'error' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/10 dark:border-red-900/30 dark:text-red-400',
    };
    $icon =  match($type){
        'info' => 'info',
        'success' => 'check',
        'warning' => 'exclamation-triangle',
        'error' => 'circle-x',
    };
@endphp
<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-transition 
    class="mb-6 p-4 rounded-xl border flex items-start gap-3 {{ $color }}"
>
    {{-- Icône --}}
    <div class="shrink-0">
        <flux:icon :name="$icon" variant="mini" class="h-5 w-5" />
    </div>

    {{-- Message --}}
    <div class="flex-1 text-sm font-medium">
        {{ $message }}
    </div>

    {{-- Bouton de fermeture --}}
    <button 
        type="button" 
        @click="show = false" 
        class="shrink-0 transition-colors cursor-pointer"
    >
        <flux:icon name="x-mark" variant="mini" class="h-5 w-5" />
    </button>
</div>
