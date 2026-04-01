<?php

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public array $config;
    public string $property;
    public string $type;
    
    public $value = null; // Contiendra le résultat de l'action

    public function mount(array $widget)
    {
        $this->type = $widget['type'];
        $this->property = $widget['property'];
        $this->config = $widget['config'];
    }

    #[Computed]
    public function widgetConfig()
    {
        return $this->config;
    }

    #[Computed]
    public function data(): mixed
    {
        $action = $this->config['action'];

        if ($action && class_exists($action)) {
            return $action::run($this->property,$this->config);
        }

        return null;
    }
};
?>

<div class="h-auto">
    <flux:card class="relative h-full flex flex-col group">

        @if ($type != 'card')
            {{-- Header du Widget --}}
            <div class="flex items-center justify-between gap-2 mb-4">
                <div class="flex items-center gap-2">
                    @isset($config['icon'])
                        <flux:icon :name="$config['icon']" variant="mini" class="text-{{ $config['color'] }}-500" />
                    @endisset
                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $config['label'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div data-sort-handle class="p-1 cursor-move group-hover:opacity-100 transition-opacity text-gray-400">
                        <flux:icon.grip-vertical variant="micro" />
                    </div>
                    <flux:button 
                        icon="arrow-path" 
                        wire:click="$refresh" 
                        wire:island="data-insight-{{ $property }}" 
                        variant="ghost" 
                        size="sm" 
                        class="cursor-pointer"
                        title="refresh"
                    />
                </div>
            </div>            
        @endif


        <div class="flex-1 flex flex-col justify-center">

            @island(defer: true, name: 'data-insight-'.$property)
                @placeholder
                    <div class="animate-pulse space-y-2">
                        <div class="h-8 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                    </div>
                @endplaceholder
                <x-dynamic-component 
                    :component="'core::data.insight.types.' . $type.'-type'" 
                    :data="$this->data" 
                    :config="$this->widgetConfig" 
                />
            @endisland
        </div>
    </flux:card>
</div>