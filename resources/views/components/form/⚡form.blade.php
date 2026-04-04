<?php

use Livewire\Component;
use App\Core\Framework\Support\Data\Form\Traits\HasForm;
use Livewire\Attributes\Lazy;


new #[Lazy] class extends Component
{
   use HasForm;
};
?>

<div class="space-y-6">

    {{-- Header --}}
    @error('form_global')
        <x-core::ui.alert :message="$message" type="error"/>
    @enderror
    
    <div class="flex flex-col items-center gap-4">
        @if($config['icon'])
            <flux:icon :name="$config['icon']" class="h-6 w-6 text-zinc-600" />
        @endif
        <div>
            <flux:heading size="xl">{{ $config['title'] ?? '' }}</flux:heading>
            @if($config['description']) <flux:subheading>{{ $config['description'] }}</flux:subheading> @endif
        </div>
    </div>

    @placeholder
        <div class="flex items-center justify-center min-h-[150px]">
            <flux:icon.loading />
        </div>
    @endplaceholder

    <form  wire:submit.prevent="save" {{ $attributes }} class="mt-2">

        @csrf
        @if($layout === 'accordion')
            <x-core::data.form.layouts.accordion :sections="$builder" />
        @elseif($layout === 'tabs')
            <x-core::data.form.layouts.tabs :tabs="$builder" />
        @elseif($layout=== 'simple')
            <x-core::data.form.layouts.simple :fields="$builder" />
        @elseif($layout === 'wizard')
            <x-core::data.form.layouts.wizard :steps="$builder" />
        @endif
    
        {{-- Footer/Action --}}
        @if($config['layout'] !== 'wizard')
            <div class="flex justify-end gap-3 mt-8">
                <div class="flex items-center gap-3 justify-between">
                    @if($config['cancel'])
                        <flux:button variant="filled" wire:click="cancel" class="cursor-pointer capitalize">
                            {{ __('cancel') }}
                        </flux:button>
                    @endif
                    <flux:button variant="primary" wire:click="save" class="cursor-pointer" :icon="$config['saveIcon']">
                        {{ ucfirst($config['saveLabel']) }}
                    </flux:button>
                </div>

            </div>
        @endif
    </form>
</div>