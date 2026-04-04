<?php

use Livewire\Component;
use App\Core\Framework\Support\Data\Insight\Traits\HasWidget;

new class extends Component
{
    use HasWidget;
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
                    <flux:dropdown>
                        
                        <flux:button icon:trailing="ellipsis-vertical" variant="ghost" size="sm" class="cursor-pointer" />

                        <flux:menu>
                            
                            <flux:menu.item 
                                icon="refresh-ccw" 
                                wire:click="$refresh" 
                                wire:island="{{ 'data-widget-'.$property }}" 
                                class="cursor-pointer">
                                {{ucfirst(__('refresh')) }}
                            </flux:menu.item>
                            <flux:menu.item 
                                icon="pencil-square" 
                                wire:click="edit('{{ $widget['_insight_id'] }}','{{ $widget['_uuid'] }}')" 
                                wire:island="{{ 'data-widget-'.$property }}" 
                                class="cursor-pointer">
                                {{ucfirst(__('edit')) }}
                            </flux:menu.item>
                            <flux:menu.item 
                                icon="trash" 
                                wire:click="delete('{{ $widget['_insight_id'] }}','{{ $widget['_uuid'] }}')"
                                wire:confirm="{{ __('Are you sure you want to delete this widget?') }}"
                                wire:island="{{ 'data-widget-'.$property }}" 
                                class="cursor-pointer">
                                {{ucfirst(__('delete')) }}
                            </flux:menu.item>
                            
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>            
        @endif


        <div class="flex-1 flex flex-col justify-center">

            @island(defer: true, name: 'data-widget-'.$property)
                @placeholder
                    <div class="animate-pulse space-y-2">
                        <div class="h-8 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                    </div>
                @endplaceholder

                <div wire:loading>
                    <flux:icon.loading />
                </div>

                <div wire:loading.remove>
                    <x-dynamic-component 
                        :component="'core::data.insight.types.' . $type.'-type'" 
                        :data="$this->data" 
                        :config="$this->widgetConfig" 
                    />
                </div>

            @endisland
        </div>
    </flux:card>
</div>