@props([
    'position' => 'top-right',
])

@php
    $positionClasses = match ($position) {
        'top-right'    => 'top-0 right-0 items-start justify-end',
        'top-left'     => 'top-0 left-0 items-start justify-start',
        'bottom-right' => 'bottom-0 right-0 items-end justify-end',
        'bottom-left'  => 'bottom-0 left-0 items-end justify-start',
        default        => 'top-0 right-0 items-start justify-end',
    };
@endphp

<div x-data="{ 
        show: false, 
        message: '', 
        variant: 'success',
        icon: 'check-circle'
    }"
    x-on:notify.window="
        show = true; 
        message = $event.detail.message; 
        variant = $event.detail.variant || 'success';
        icon = $event.detail.icon || (variant === 'success' ? 'check-circle' : (variant === 'error' ? 'exclamation-triangle' : 'information-circle'));
        setTimeout(() => show = false, 5000);
    "
    x-show="show"
    x-cloak
    class="fixed inset-0 flex px-4 py-6 pointer-events-none z-100 {{ $positionClasses }}"
>
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 {{ str_contains($position, 'right') ? 'translate-x-8' : '-translate-x-8' }}"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="max-w-sm w-full bg-white dark:bg-zinc-800 shadow-2xl rounded-xl border border-zinc-200 dark:border-zinc-700 pointer-events-auto overflow-hidden"
    >
        <div class="p-4">
            <div class="flex items-start">
                {{-- ICÔNE DYNAMIQUE FLUX --}}
                <div class="shrink-0" :class="{
                    'text-emerald-500': variant === 'success',
                    'text-red-500': variant === 'error',
                    'text-blue-500': variant === 'info'
                }">
                    <template x-if="icon === 'check-circle'">
                        <flux:icon name="check-circle" class="h-6 w-6" />
                    </template>
                    <template x-if="icon === 'exclamation-triangle'">
                        <flux:icon name="exclamation-triangle" class="h-6 w-6" />
                    </template>
                    <template x-if="icon === 'information-circle'">
                        <flux:icon name="information-circle" class="h-6 w-6" />
                    </template>
                </div>
                
                <div class="ml-3 w-0 flex-1">
                    <p x-text="message" class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"></p>
                </div>

                <div class="ml-4 shrink-0 flex">
                    {{-- BOUTON FERMER AVEC FLUX ICON --}}
                    <button @click="show = false" class="inline-flex text-zinc-400 hover:text-zinc-500 outline-none">
                        <flux:icon name="x-mark" variant="mini" class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>
        
        <div class="h-1 bg-zinc-100 dark:bg-zinc-700 w-full">
            <div class="h-1 transition-all duration-5000 ease-linear"
                 :class="variant === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
                 :style="show ? 'width: 100%' : 'width: 0%'"></div>
        </div>
    </div>
</div>