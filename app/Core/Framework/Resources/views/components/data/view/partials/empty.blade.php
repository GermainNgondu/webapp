@props([
    'message' => 'Aucun élément trouvé',
    'icon' => 'inbox'
])
<div class="flex flex-col items-center justify-center py-20 text-zinc-400">
    <flux:icon :icon="$icon" variant="outline" class="mb-4 size-12 opacity-20" />
    <flux:text>{{ $message }}</flux:text>
</div>