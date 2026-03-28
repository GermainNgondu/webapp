@props(['value'])

<div class="w-full rounded-xl border bg-zinc-50 overflow-hidden">
    <div class="p-3 border-b flex justify-between items-center bg-white">
        <span class="text-xs font-semibold text-zinc-500">Aperçu PDF</span>
        <flux:button href="{{ $value }}" target="_blank" size="xs" icon="arrow-top-right-on-square" variant="ghost">Plein écran</flux:button>
    </div>
    <iframe src="{{ $value }}#toolbar=0" class="w-full h-[500px]"></iframe>
</div>