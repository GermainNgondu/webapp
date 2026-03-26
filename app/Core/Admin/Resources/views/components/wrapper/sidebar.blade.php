@props(['layout'])
@php $brand = $layout->getBrand(); @endphp

<flux:sidebar sticky :collapsible="true" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 md:w-[220px]">

    <flux:sidebar.header>
        <flux:sidebar.brand
            :href="route($brand->homeRoute)"
            :logo="$brand->logoUrl"
            :logo:dark="$brand->darkModeLogoUrl"
            :name="$brand->name"
        />
        <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        @foreach($layout->getPrimary() as $item)
            <x-admin::menu-item :item="$item" mode="sidebar" />
        @endforeach
    </flux:sidebar.nav>


    <flux:spacer />

    <flux:sidebar.nav>
        @foreach($layout->getSecondary() as $item)
            <x-admin::menu-item :item="$item" mode="sidebar" />
        @endforeach
    </flux:sidebar.nav>

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:sidebar.profile :name="auth()->user()->name" :avatar="auth()->user()->avatar_url ?? ''" />
        <flux:menu>
            @foreach($layout->getUserMenu() as $item)
                <flux:menu.item :icon="$item->icon" :href="route($item->route)" wire:navigate>{{ $item->label }}</flux:menu.item>
            @endforeach
            <flux:menu.item icon="sun-moon">
                <x-admin::theme-switch format="switch"/>
            </flux:menu.item>            
            <flux:menu.separator />
            <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('admin.users.logout')" class="capitalize">
                {{ __('logout') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />
    <flux:dropdown position="top" align="start">
        <flux:profile :name="auth()->user()->name" :avatar="auth()->user()->avatar_url ?? ''" />
        <flux:menu>
            @foreach($layout->getUserMenu() as $item)
                <flux:menu.item :icon="$item->icon" :href="route($item->route)" wire:navigate>{{ $item->label }}</flux:menu.item>
            @endforeach
            <flux:menu.item icon="sun-moon">
                <x-admin::theme-switch format="switch"/>
            </flux:menu.item>            
            <flux:menu.separator />
            <flux:menu.item icon="arrow-right-start-on-rectangle" :href="route('admin.users.logout')" class="capitalize">
                {{ __('logout') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>