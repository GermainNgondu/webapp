@props(['layout'])
@php $brand = $layout->getBrand(); @endphp

<flux:header sticky class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:brand :href="route($brand->homeRoute)" :logo="$brand->logoUrl" :name="$brand->name" class="max-lg:hidden dark:hidden" />
    <flux:brand :href="route($brand->homeRoute)" :logo="$brand->darkModeLogoUrl" :name="$brand->name" class="max-lg:hidden! hidden dark:flex" />

    <flux:navbar class="-mb-px max-lg:hidden">
        @foreach($layout->getPrimary() as $item)
            <x-admin::menu-item :item="$item" mode="header" />
        @endforeach
    </flux:navbar>

    <flux:spacer />

     <flux:navbar class="me-4">
        @foreach($layout->getSecondary() as $item)
            <flux:navbar.item
                :icon="$item->icon" 
                :href="route($item->route)" 
                :badge="$item->badge"
                :label="$item->label"
                wire:navigate
                class="cursor-pointer max-lg:hidden"
            />
        @endforeach
    </flux:navbar>
    <flux:dropdown position="top" align="start">
        <flux:profile :name="auth()->user()->name" :avatar="auth()->user()->avatar_url ?? ''" />
        <flux:menu>
            @foreach($layout->getUserMenu() as $item)
                <flux:menu.item :icon="$item->icon" :href="route($item->route)">{{ $item->label }}</flux:menu.item>
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
<flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
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

    <flux:sidebar.spacer />

    <flux:sidebar.nav>
        @foreach($layout->getSecondary() as $item)
            <x-admin::menu-item :item="$item" mode="sidebar" />
        @endforeach
    </flux:sidebar.nav>

</flux:sidebar>

<flux:main>
    <div class="max-w-7xl mx-auto">
        {{ $slot }}
    </div>
</flux:main>