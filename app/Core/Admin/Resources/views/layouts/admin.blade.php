@props(['title' => null])
@php($layout = app(\App\Core\Framework\Managers\LayoutManager::class))

<!DOCTYPE html>
<html lang="fr" class="h-full bg-white dark:bg-zinc-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? $title . ' - ' . $layout->getBrand()->name : $layout->getBrand()->name }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen antialiased font-sans">
        
        @if($currentLayout === 'sidebar')
            <x-admin::layouts.sidebar-wrapper>
                {{ $slot }}
            </x-admin::layouts.sidebar-wrapper>
        @else
            <x-admin::layouts.header-wrapper>
                {{ $slot }}
            </x-admin::layouts.header-wrapper>
        @endif
        <x-notification />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>        
        @fluxScripts
        @livewireScripts
    </body>
</html>