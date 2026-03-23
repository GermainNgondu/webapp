<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @fluxAppearance
        @livewireStyles
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
        
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">


        <flux:header sticky container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="max-lg:hidden dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." class="max-lg:hidden! hidden dark:flex" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="/" wire:navigate>Home</flux:navbar.item>
                <flux:navbar.item icon="newspaper"  href="/posts" wire:navigate>Posts</flux:navbar.item>
                <flux:navbar.item icon="images"  href="/media" wire:navigate>Media</flux:navbar.item>
            </flux:navbar>
            <flux:spacer />
            <flux:dropdown x-data align="end">
                <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                    <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
                    <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
                </flux:button>

                <flux:menu>
                    <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
                    <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
                    <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">System</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            <flux:dropdown position="top" align="start">
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
                <flux:menu>
                    <flux:menu.item>
                        @auth
                        {{ auth()->user()->name }}
                        @forelse(auth()->user()->getRoleNames() as $role)
                                    <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-900/30 text-zinc-700 dark:text-zinc-300 text-[10px] font-black uppercase tracking-wider">
                                        {{ $role }}
                                    </span>
                                @empty
                                    <span class="px-2 py-0.5 rounded-md bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-[10px] font-black uppercase tracking-wider">
                                        Aucun rôle (Standard)
                                    </span>
                                @endforelse
                        @endauth
                    </flux:menu.item>
                    <flux:menu.item href="/login-admin">Admin</flux:menu.item>
                    <flux:menu.item href="/login-compta">Compta</flux:menu.item>
                    <flux:menu.item href="/login-simple">Simple</flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout-test">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:header>


        <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="#"
                    logo="https://fluxui.dev/img/demo/logo.png"
                    logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                    name="Acme Inc."
                />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="/" wire:navigate>Home</flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="/media" wire:navigate>Media</flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>
        
        <flux:main container>
            {{ $slot }}
        </flux:main>

        <livewire:features::media.browser/>
        <x-notification />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>        
        @fluxScripts
        @livewireScripts

    </body>
</html>
