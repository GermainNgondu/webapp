@php($layout = app(\App\Core\Framework\Managers\LayoutManager::class))

<flux:header sticky class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 px-6">
    <flux:sidebar.toggle class="lg:hidden" />

    <flux:navbar class="max-lg:hidden">
        <x-admin::brand mode="header" :brand="$layout->getBrand()" />
        <flux:separator vertical variant="subtle" class="mx-2" />
        
        @foreach($layout->getPrimary() as $item)
            <x-admin::menu-item :item="$item" mode="header" />
        @endforeach
    </flux:navbar>

    <flux:spacer />

    <div class="flex items-center space-x-4">
        <flux:navbar class="max-lg:hidden">
             @foreach($layout->getSecondary() as $item)
                <x-admin::menu-item :item="$item" mode="header" />
            @endforeach
        </flux:navbar>

        <flux:dropdown>
            <flux:profile :name="auth()->user()->name" :avatar="auth()->user()->avatar_url ?? ''" />
            <flux:menu>
                 @foreach($layout->getUserMenu() as $item)
                    <flux:menu.item :icon="$item->icon" :href="route($item->route)">{{ $item->label }}</flux:menu.item>
                @endforeach
                <flux:menu.separator />
                <flux:menu.item :icon="arrow-right-start-on-rectangle" :href="route('admin.logout')" class="capitalize">
                    {{ __('logout') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>
</flux:header>

<flux:main>
    <div class="max-w-7xl mx-auto py-8">
        {{ $slot }}
    </div>
</flux:main>