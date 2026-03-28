@props(['item'])

<div class="w-full rounded-xl overflow-hidden border border-zinc-200">
    {{-- On utilise l'URL publique de Microsoft Office Viewer --}}
    <iframe 
        src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode($item->url) }}" 
        class="w-full h-[600px]" 
        frameborder="0">
    </iframe>
</div>