@props(['item', 'mode'])

@if($item->children && $item->children->count() > 0)
    @if($mode === 'sidebar')
        <flux:navlist.group :label="$item->label" :icon="$item->icon">
            @foreach($item->children as $child)
                <flux:navlist.item :href="route($child->route)" :current="request()->routeIs($child->route)" wire:navigate>
                    {{ $child->label }}
                </flux:navlist.item>
            @endforeach
        </flux:navlist.group>
    @else
        <flux:dropdown>
            <flux:navbar.item icon-trailing="chevron-down">{{ $item->label }}</flux:navbar.item>
            <flux:menu>
                @foreach($item->children as $child)
                    <flux:menu.item :href="route($child->route)" wire:navigate>{{ $child->label }}</flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>
    @endif
@else
    @php($component = $mode === 'sidebar' ? 'flux:navlist.item' : 'flux:navbar.item')
    <{{ $component }} 
        :icon="$item->icon" 
        :href="route($item->route)" 
        :current="request()->routeIs($item->route)"
        :badge="$item->badge"
        wire:navigate
    >
        {{ $item->label }}
    </{{ $component }}>
@endif