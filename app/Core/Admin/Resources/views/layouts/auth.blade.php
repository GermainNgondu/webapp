@props(['title' => null])
@php($layout = app(\App\Core\Framework\Support\Managers\LayoutManager::class))

<!DOCTYPE html>
<html lang="fr" class="h-full bg-zinc-50 dark:bg-zinc-950">

    @includeIf('admin::partials.head')

    <body class="h-full antialiased font-sans">
        <div class="flex items-center justify-end gap-3 py-5">
            <x-admin::theme-switch.theme-switch/>
            <div></div>
        </div>
        <div class="flex flex-col justify-center py-10 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md flex flex-col items-center">
                <x-admin::brand :brand="$layout->getBrand()" :showName="false" class="mb-6"/>
            </div>
            <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-[480px]">
                <flux:card class="px-6 py-10 sm:px-12 border-zinc-200 dark:border-zinc-800">
                    {{ $slot }}
                </flux:card>
                <p class="mt-10 text-center text-sm text-zinc-500">
                    &copy; {{ date('Y') }} {{ $layout->getBrand()->name }}
                </p>
            </div>
        </div>
        <x-core::ui.notification />     
        @fluxScripts
        @livewireScripts
    </body>
</html>