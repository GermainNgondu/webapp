@props([
    'name',
    'title',
    'dismissible'=> false,
    'size'=> 'lg', 
    'mode'=> 'modal', 
    'variant'=> 'default',
    'position'=> 'right', 
    'fixedWidth'=> 83
])

@if ($mode === 'slideover')
    <flux:modal 
        :name="$name" 
        :position="$position" 
        :dismissible="$dismissible"
        :variant="$variant"
        {{$attributes->class(['w-full','md:w-'.$size])}}
        flyout
    >

        <div class="space-y-6">
            @includeIf('core::components.ui.modal.includes.modal-header')
            {{ $slot }}
            <x-slot name="footer" class="flex items-center justify-end gap-2">
                @includeIf('core::components.ui.modal.includes.modal-footer-close')
            </x-slot>
        </div>

    </flux:modal>

@else
    <flux:modal 
        :name="$name" 
        :variant="$variant" 
        :dismissible="$dismissible"
        :position="$position" 
        {{$attributes->class(['w-full','md:w-'.$size])}}
    >
        <div class="space-y-6">
            @includeIf('core::components.ui.modal.includes.modal-header')
            {{ $slot }}
            <x-slot name="footer" class="flex items-center justify-end gap-2">
                @includeIf('core::components.ui.modal.includes.modal-footer-close')
            </x-slot>
        </div>

    </flux:modal>   
@endif