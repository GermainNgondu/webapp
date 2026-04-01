@props(['data', 'config'])

<flux:heading size="lg">{{ $config['label'] }}</flux:heading>

<flux:text class="mt-2 mb-4">
    {{ $config['description'] }}
</flux:text>