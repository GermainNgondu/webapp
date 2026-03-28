@props(['item'])

<div class="relative w-full rounded-xl bg-[#1e1e1e] p-1 shadow-2xl">
    <div class="flex items-center justify-between px-4 py-2 border-b border-white/10">
        <span class="text-[10px] font-mono text-zinc-400 uppercase tracking-widest">{{ pathinfo($item->url, PATHINFO_EXTENSION) }}</span>
        <flux:button variant="ghost" size="xs" icon="clipboard" x-on:click="navigator.clipboard.writeText($el.nextElementSibling.innerText)" class="text-zinc-400 hover:text-white" />
    </div>
    <pre class="p-4 overflow-auto max-h-[400px] text-sm font-mono leading-relaxed text-emerald-400">
        <code>{{ file_get_contents($item->url) ?? 'Impossible de lire le contenu' }}</code>
    </pre>
</div>