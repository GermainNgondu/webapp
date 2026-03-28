@props(['title' => null])
@php($layout = app(\App\Core\Framework\Support\Managers\LayoutManager::class))

<!DOCTYPE html>
<html lang="fr" class="h-full bg-white dark:bg-zinc-900">


    @includeIf('admin::partials.head')

    <body class="min-h-screen antialiased font-sans">
        
        @if($layout->getCurrentLayout() === 'sidebar')
            <x-admin::wrapper.sidebar :layout="$layout">
                {{ $slot }}
            </x-admin::wrapper.sidebar>
        @else
            <x-admin::wrapper.header :layout="$layout">
                {{ $slot }}
            </x-admin::wrapper.header>
        @endif
        <x-core::ui.notification />        
        @fluxScripts
        @livewireScripts
    </body>
</html>