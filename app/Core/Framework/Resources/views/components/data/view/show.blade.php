@props(['title','item', 'schema', 'mode'=> null])

@if ($mode === 'modal')
    <flux:modal name="item-detail" variant="side" class="w-full max-w-lg">
        @if($this->activeItem)
            <div class="space-y-6">
                <flux:heading size="lg">{{ $title ??'' }}</flux:heading>    
                <x-core::data.view.parts.item :item="$item" :schema="$schema" variant="compact" />
            </div>
        @endif
    </flux:modal>   
@elseif ($mode === 'slideover')
    <flux:modal name="item-detail" class="md:w-lg" flyout>
        @if($this->activeItem)
            <div class="space-y-6">
                <flux:heading size="lg">{{ $title ??'' }}</flux:heading>    
                <x-core::data.view.parts.item :item="$item" :schema="$schema"/>
            </div>
        @endif
    </flux:modal>
@else
    <div class="space-y-6">
        
        <flux:heading size="lg">{{ $title ?? '' }}</flux:heading>    
        <x-core::data.view.parts.item :item="$item" :schema="$schema" />

    </div>
@endif
