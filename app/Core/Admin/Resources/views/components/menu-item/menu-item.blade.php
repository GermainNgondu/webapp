@props(['item', 'mode'])

@if($item->children && $item->children->count() > 0)
    @if($mode === 'sidebar')
        <flux:sidebar.group :label="$item->label" :icon="$item->icon">
            @foreach($item->children as $child)
                <flux:sidebar.item :href="route($child->route)" class="cursor-pointer" wire:navigate>
                    {{ $child->label }}
                </flux:sidebar.item>
            @endforeach
        </flux:sidebar.group>
    @else
        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon-trailing="chevron-down">{{ $item->label }}</flux:navbar.item>
            <flux:navmenu>
                @foreach($item->children as $child)
                    <flux:navmenu.item :href="route($child->route)" class="cursor-pointer" wire:navigate>{{ $child->label }}</flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>
    @endif
@else

    @if($mode === 'sidebar')
    <flux:sidebar.item
        icon="{{$item->icon}}" 
        href="{{route($item->route)}}" 
        badge="{{$item->badge}}"
        wire:navigate
        class="cursor-pointer"
    >
        {{ $item->label }}
    </flux:sidebar.item>
    @else
    <flux:navbar.item

        icon="{{$item->icon}}" 
        href="{{route($item->route)}}" 
        badge="{{$item->badge}}"
        wire:navigate
        class="cursor-pointer"
    >
        {{ $item->label }}
    </flux:navbar.item>
    @endif
@endif