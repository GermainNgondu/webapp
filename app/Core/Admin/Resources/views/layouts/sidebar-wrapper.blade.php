@php($layout = app(\App\Core\Framework\Managers\LayoutManager::class))

<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800">
    <flux:sidebar.toggle class="lg:hidden" />

    <x-admin::brand mode="sidebar" :brand="$layout->getBrand()" />

    <flux:input variant="filled" icon="magnifying-glass" placeholder="Rechercher..." class="my-4" />

    <flux:navlist variant="pill">
        @foreach($layout->getPrimary() as $item)
            <x-admin::menu-item :item="$item" mode="sidebar" />
        @endforeach
    </flux:navlist>

    <flux:spacer />

    <flux:navlist variant="pill">
        @foreach($layout->getSecondary() as $item)
            <x-admin::menu-item :item="$item" mode="sidebar" />
        @endforeach
    </flux:navlist>

    <flux:separator class="my-4" />

    <flux:dropdown>
        <flux:profile :name="auth()->user()->name" :avatar="auth()->user()->avatar_url ?? ''" />
        <flux:menu>
            @foreach($layout->getUserMenu() as $item)
                <flux:menu.item :icon="$item->icon" :href="route($item->route)" wire:navigate>{{ $item->label }}</flux:menu.item>
            @endforeach
            <flux:menu.separator />
            <flux:menu.item :icon="arrow-right-start-on-rectangle" :href="route('admin.logout')" class="capitalize">
                {{ __('logout') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<flux:main>
    {{ $slot }}
</flux:main>