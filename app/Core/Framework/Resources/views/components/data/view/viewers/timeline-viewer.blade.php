@props(['item'])

<div class="space-y-6 relative before:absolute before:inset-y-0 before:left-4 before:w-px before:bg-zinc-100">
    @foreach($item->activities ?? [] as $log)
        <div class="relative pl-10">
            <div class="absolute left-2 top-1 size-4 rounded-full bg-white border-2 border-zinc-200 ring-4 ring-white"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-zinc-900">{{ $log['description'] }}</p>
                    <p class="text-xs text-zinc-500">{{ $log['user'] }}</p>
                </div>
                <time class="text-[10px] text-zinc-400 uppercase font-medium">{{ $log['date'] }}</time>
            </div>
        </div>
    @endforeach
</div>