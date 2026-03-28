@props(['value'])

<div x-data="{ open: false, active: '' }" class="grid grid-cols-3 gap-3">
    @foreach($value as $url)
        <div @click="active = '{{ $url }}'; open = true" class="aspect-square rounded-lg overflow-hidden cursor-zoom-in border hover:ring-2 ring-blue-500 transition-all">
            <img src="{{ $url }}" class="w-full h-full object-cover">
        </div>
    @endforeach

    <template x-teleport="body">
        <div x-show="open" x-transition.opacity @click="open = false" @keydown.escape.window="open = false" 
             class="fixed inset-0 z-200 bg-zinc-950/95 flex items-center justify-center p-10">
            <img :src="active" class="max-w-full max-h-full rounded shadow-2xl">
            <button class="absolute top-10 right-10 text-white/50 hover:text-white"><x-heroicon-o-x-mark class="size-10"/></button>
        </div>
    </template>
</div>