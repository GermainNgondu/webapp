<div x-show="tab === 'url'" x-cloak class="space-y-6" x-data="{ 
    url: @entangle('url'),
    video: null,
    updatePreview(val) {
        // Logique simplifiée pour Alpine pour détecter les vidéos
        if (val.includes('youtube.com') || val.includes('youtu.be')) this.video = 'youtube';
        else if (val.includes('vimeo.com')) this.video = 'vimeo';
        else if (val.includes('dailymotion.com') || val.includes('dai.ly')) this.video = 'dailymotion';
        else this.video = null;
    },
    getEmbedUrl() {
        if (!this.url) return '';
        // Extraction rapide de l'ID pour le preview (version simplifiée JS)
        let id = '';
        if (this.video === 'youtube') id = this.url.split('v=')[1]?.split('&')[0] || this.url.split('/').pop();
        if (this.video === 'vimeo') id = this.url.split('/').pop();
        if (this.video === 'dailymotion') id = this.url.split('/').pop().split('_')[0];
        
        const embeds = {
            youtube: 'https://www.youtube.com/embed/',
            vimeo: 'https://player.vimeo.com/video/',
            dailymotion: 'https://www.dailymotion.com/embed/video/'
        };
        return embeds[this.video] + id;
    }
}">
    <flux:field>
        <flux:label>URL du média (Image ou Vidéo)</flux:label>
        <flux:input 
            wire:model.live.debounce.500ms="url" 
            x-on:input="updatePreview($event.target.value)"
            placeholder="YouTube, Vimeo, Dailymotion ou lien direct image" 
            class="mt-5"
        />
    </flux:field>

    {{-- Preview Dynamique --}}
    <div x-show="url" x-transition class="aspect-video max-w-4xl m-auto overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-2xl bg-black flex items-center justify-center relative shadow-inner">
        
        {{-- Cas 1 : Vidéo (Iframe) --}}
        <template x-if="video">
            <iframe :src="getEmbedUrl()" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
        </template>

        {{-- Cas 2 : Image --}}
        <template x-if="!video">
            <img :src="url" x-on:error="isValidUrl = false" class="max-w-full max-h-full object-contain" />
        </template>
    </div>
</div>