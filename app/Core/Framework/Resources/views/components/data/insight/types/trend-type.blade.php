@props(['data', 'config'])

<div class="flex items-center gap-2 {{ $data >= 0 ? 'text-green-600' : 'text-red-600' }}">
    <span class="text-2xl font-bold">{{ $data }}%</span>
    <flux:icon :name="$data >= 0 ? 'trending-up' : 'trending-down'" variant="micro" />
</div>