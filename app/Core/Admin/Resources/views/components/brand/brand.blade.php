@props(['brand', 'mode' => 'sidebar', 'showName' => true])

<a href="{{ route($brand->homeRoute) }}" {{ $attributes->class(['flex items-center space-x-2']) }}>
    @if($brand->logoUrl)
        <img src="{{ $brand->logoUrl }}" alt="{{ $brand->name }}" class="h-8 w-auto">
    @endif
    @if($showName)
        <span class="font-semibold text-zinc-900 dark:text-white truncate">{{ $brand->name }}</span>
    @endif
</a>