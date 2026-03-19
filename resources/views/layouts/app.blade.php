<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @fluxAppearance
        @livewireStyles
        
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @auth
            <div class="max-w-3xl mx-auto py-4">
                <div class="flex items-center gap-4">
                    {{-- Avatar factice ou initiales --}}
                    <div class="h-10 w-10 rounded-full bg-zinc-600 flex items-center justify-center text-white font-bold shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-zinc-900 dark:text-zinc-100">
                            Bonjour, {{ auth()->user()->name }}
                        </p>
                        <div class="flex gap-1.5 mt-0.5">
                            @forelse(auth()->user()->getRoleNames() as $role)
                                <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-900/30 text-zinc-700 dark:text-zinc-300 text-[10px] font-black uppercase tracking-wider">
                                    {{ $role }}
                                </span>
                            @empty
                                <span class="px-2 py-0.5 rounded-md bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-[10px] font-black uppercase tracking-wider">
                                    Aucun rôle (Standard)
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>                
            </div>

        @endauth
        <div class="max-w-3xl mx-auto py-4 flex justify-between items-center">
            @if(app()->environment('local'))
                <div class="flex gap-2 z-999 bg-white p-2 rounded-lg border border-zinc-200 dark:text-zinc-300 dark:bg-zinc-900/30">
                    <span class="text-xs font-bold self-center mr-2">Test Login:</span>
                    <flux:button size="xs" variant="ghost" href="/login-admin">Admin</flux:button>
                    <flux:button size="xs" variant="ghost" href="/login-compta">Compta</flux:button>
                    <flux:button size="xs" variant="ghost" href="/login-simple">Simple</flux:button>
                    @auth
                        <flux:button size="xs" variant="ghost" href="/logout-test" class="text-red-500">Log out</flux:button>
                    @endauth
                </div>
            @endif
        

            <div class=" max-w-md">
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                </flux:radio.group>            
            </div>            
        </div>


        
        {{ $slot }}

        <x-notification />
        @fluxScripts
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    </body>
</html>
