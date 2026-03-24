@props(['brand', 'mode' => 'sidebar'])

<a href="{{ route($brand->homeRoute) }}" {{ $attributes->class(['flex items-center space-x-2']) }}>
    @if($brand->logoUrl)
        <img src="{{ $brand->logoUrl }}" alt="{{ $brand->name }}" class="h-8 w-auto">
    @else
        <div class="h-8 w-8 bg-zinc-800 dark:bg-white rounded flex items-center justify-center text-white dark:text-zinc-800 font-bold text-sm">
            {{ substr($brand->name, 0, 1) }}
        </div>
    @endif
    @if($mode === 'sidebar' || $attributes->has('show-name'))
        <span class="font-semibold text-zinc-900 dark:text-white truncate">{{ $brand->name }}</span>
    @endif
</a>