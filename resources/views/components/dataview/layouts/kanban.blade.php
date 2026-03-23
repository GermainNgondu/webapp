@props(['items', 'schema'])

@php
    // Logique simplifiée de groupement par statut
    $grouped = $items->groupBy('status');
@endphp

<div class="flex gap-4 overflow-x-auto pb-4">
    @foreach(['A faire', 'En cours', 'Terminé'] as $status)
        <div class="flex-shrink-0 w-80 bg-zinc-900/50 p-4 rounded-xl border border-zinc-800">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="sm" class="uppercase tracking-widest">{{ $status }}</flux:heading>
                <flux:badge size="sm" variant="pill">{{ $grouped->get($status)?->count() ?? 0 }}</flux:badge>
            </div>

            <div class="space-y-3">
                @foreach($grouped->get($status, []) as $item)
                    <div class="p-3 bg-zinc-800 border border-zinc-700 rounded-lg shadow-sm cursor-grab active:cursor-grabbing">
                        <flux:text class="font-medium">{{ $item->name }}</flux:text>
                        <flux:text size="xs" color="zinc" class="mt-1">{{ $item->client_name }}</flux:text>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>