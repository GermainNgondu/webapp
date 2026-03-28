@props(['view', 'schema' => []])

<div wire:loading.delay.short wire:target.except="handleAction, handleBulkAction, selected" class="w-full animate-pulse">
    @if($view === 'table')
        {{-- Skeleton TABLE --}}
        <div class="border border-zinc-200 rounded-xl overflow-hidden bg-white">
            <div class="bg-zinc-50 h-10 border-b border-zinc-200"></div> {{-- Header --}}
            @foreach(range(1, 5) as $i)
                <div class="flex items-center p-4 border-b border-zinc-100 gap-4">
                    @foreach(range(1, count($schema) ?: 4) as $j)
                        <div class="h-4 bg-zinc-100 rounded flex-1"></div>
                    @endforeach
                    <div class="h-4 bg-zinc-100 rounded w-10"></div> {{-- Actions --}}
                </div>
            @endforeach
        </div>

    @elseif($view === 'grid')
        {{-- Skeleton GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(range(1, 6) as $i)
                <div class="rounded-xl border border-zinc-200 bg-white overflow-hidden">
                    <div class="aspect-video bg-zinc-100"></div> {{-- Image --}}
                    <div class="p-4 space-y-3">
                        <div class="h-5 bg-zinc-100 rounded w-3/4"></div> {{-- Title --}}
                        <div class="h-3 bg-zinc-100 rounded w-1/2"></div> {{-- Subtitle --}}
                        <div class="pt-4 flex justify-between">
                            <div class="h-3 bg-zinc-100 rounded w-20"></div>
                            <div class="h-3 bg-zinc-100 rounded w-8"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif($view === 'kanban')
        {{-- Skeleton KANBAN --}}
        <div class="flex gap-4 overflow-x-auto pb-4">
            @foreach(range(1, 3) as $col)
                <div class="shrink-0 w-80 bg-zinc-50 p-4 rounded-xl border border-zinc-200">
                    <div class="h-4 bg-zinc-200 rounded w-1/3 mb-6"></div>
                    <div class="space-y-3">
                        @foreach(range(1, 4) as $card)
                            <div class="p-4 bg-white border border-zinc-200 rounded-lg shadow-sm space-y-2">
                                <div class="h-3 bg-zinc-100 rounded w-full"></div>
                                <div class="h-3 bg-zinc-100 rounded w-2/3"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>