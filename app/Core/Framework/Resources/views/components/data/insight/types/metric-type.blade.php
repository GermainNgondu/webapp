@props(['data', 'config'])
<div class="flex items-baseline gap-2">
    <span class="text-3xl font-bold">{{ $data }}</span>
    @if($config['suffix']) <span class="text-sm text-gray-400">{{ $config['suffix'] }}</span> @endif
</div>