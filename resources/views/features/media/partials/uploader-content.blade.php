        <div class="flex h-[550px] " x-data="{ 
                tab: 'local',
                url: @entangle('url'),
                previewUrl: '',
                isValidUrl: false,
                updateUrlPreview(val) {
                    if (val.match(/\.(jpeg|jpg|gif|png|webp|svg)$/) != null || val.startsWith('http')) {
                        this.previewUrl = val;
                        this.isValidUrl = true;
                    } else {
                        this.previewUrl = '';
                        this.isValidUrl = false;
                    }
                }
            }" >
            {{-- Sidebar (Panneau de gauche) --}}
            <div class="w-56 border-r border-zinc-200 dark:border-zinc-800  p-4 flex flex-col">
                <div class="mb-8 px-2">
                    <flux:heading size="lg">Gestionnaire d'importation</flux:heading>
                </div>

                <nav class="space-y-1 flex-1">
                    <button 
                        @click="tab = 'local'"
                        :class="tab === 'local' ? 'bg-zinc-200/50 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800/50'"
                        class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                    >
                        <flux:icon.computer-desktop variant="micro" />
                        Fichiers locaux
                    </button>

                    <button 
                        @click="tab = 'url'"
                        :class="tab === 'url' ? 'bg-zinc-200/50 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800/50'"
                        class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                    >
                        <flux:icon.link variant="micro" />
                        Lien distant / Vidéo
                    </button>
                </nav>

                {{-- Aide / Info en bas de sidebar --}}
                <div class="p-3 bg-primary-50 dark:bg-primary-950/20 rounded-xl">
                    <p class="text-[10px] text-primary-700 dark:text-primary-400 leading-relaxed">
                        
                    </p>
                </div>
            </div> 
            {{-- Contenu (Panneau de droite) --}}
            <div class="flex-1 flex flex-col min-w-0">

                <div class="flex-1 overflow-y-auto p-8 pb-5 mt-5">
                    {{-- Source URL --}}
                    @includeIf('features.media.partials.uploader-tab-url')
                    {{-- Source Locale --}}
                    @include('features.media.partials.uploader-tab-local')
                </div>

            </div>  
            
            <div class="shrink-0 py-4 backdrop-blur-md flex justify-end items-center px-8 sticky bottom-0 z-10">

                    <div class="flex gap-3">
                        {{-- Bouton pour le local --}}
                        <template x-if="tab === 'local'">
                            <flux:button wire:click="uploadLocal" variant="primary" :disabled="!$uploads" class="cursor-pointer">
                                Importer les fichiers ({{ count($uploads) }})
                            </flux:button>
                        </template>

                        {{-- Bouton pour l'URL --}}
                        <template x-if="tab === 'url'">
                            <flux:button wire:click="uploadUrl" variant="primary" x-bind:disabled="!isValidUrl" class="cursor-pointer">
                                Confirmer l'URL
                            </flux:button>
                        </template>
                    </div>
            </div>         
        </div>