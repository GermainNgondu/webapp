@props(['value']) {{-- Array d'URLs --}}

<div x-data="{ active: 0, total: {{ count($value) }} }" class="relative group w-full aspect-video rounded-xl overflow-hidden bg-zinc-900">
    {{-- Slides --}}
    @foreach($value as $index => $url)
        <div x-show="active === {{ $index }}" x-transition.opacity.duration.500ms class="absolute inset-0">
            <img src="{{ $url }}" class="w-full h-full object-contain">
        </div>
    @endforeach

    {{-- Controls --}}
    <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity">
        <button @click="active = active === 0 ? total - 1 : active - 1" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/80">
            <x-heroicon-m-chevron-left class="size-5" />
        </button>
        <button @click="active = active === total - 1 ? 0 : active + 1" class="p-2 rounded-full bg-black/50 text-white hover:bg-black/80">
            <x-heroicon-m-chevron-right class="size-5" />
        </button>
    </div>

    {{-- Indicators --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
        @foreach($value as $index => $url)
            <button @click="active = {{ $index }}" :class="active === {{ $index }} ? 'bg-white w-4' : 'bg-white/40 w-1.5'" class="h-1.5 rounded-full transition-all"></button>
        @endforeach
    </div>
</div>