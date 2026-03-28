@props(['value']) {{-- Array de strings ou d'objets {label, value} --}}

<ul class="divide-y divide-zinc-100 border rounded-xl overflow-hidden bg-white">
    @foreach($value as $item)
        <li class="px-4 py-3 flex items-center gap-3 text-sm">
            <x-heroicon-o-check-circle class="size-5 text-emerald-500 shrink-0" />
            <span class="font-medium text-zinc-700">
                {{ is_array($item) ? $item['label'] : $item }}
            </span>
            @if(is_array($item) && isset($item['value']))
                <span class="ml-auto text-zinc-400 font-mono text-xs">{{ $item['value'] }}</span>
            @endif
        </li>
    @endforeach
</ul>