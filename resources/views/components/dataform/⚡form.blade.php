<?php

use Livewire\Component;
use App\Core\Framework\Support\DataForm\Traits\HasForm;
use Livewire\Attributes\Lazy;


new #[Lazy] class extends Component
{
   use HasForm;
};
?>

<div class="space-y-6">
    {{-- Header --}}
    <x-ui.alert :message="$errors->first('form_global')" type="error"/>
    
    <div class="flex flex-col items-center gap-4">
        @if($config['icon'])
            <flux:icon :name="$config['icon']" class="h-6 w-6 text-zinc-600" />
        @endif
        <div>
            <flux:heading size="xl">{{ $config['title'] }}</flux:heading>
            @if($config['description']) <flux:subheading>{{ $config['description'] }}</flux:subheading> @endif
        </div>
    </div>

    @placeholder
    <div class="flex items-center justify-center">
        <flux:icon.loading />
    </div>
    @endplaceholder
    <form  wire:submit.prevent="save" {{ $attributes }}>

        @csrf
        @if($layout === 'accordion')
            <x-dataform.layouts.accordion :sections="$builder" />
        @elseif($layout === 'tabs')
            <x-dataform.layouts.tabs :tabs="$builder" />
        @elseif($layout=== 'simple')
            <x-dataform.layouts.simple :fields="$builder" />
        @elseif($layout === 'wizard')
            <x-dataform.layouts.wizard :steps="$builder" />
        @endif
    
        {{-- Footer/Action --}}
        @if($config['layout'] !== 'wizard')
            <div class="flex justify-end gap-3 mt-8">
                <flux:button variant="primary" wire:click="save" class="w-full sm:w-auto cursor-pointer">
                    {{ $config['saveLabel'] }}
                </flux:button>
            </div>
        @endif
    </form>
</div>