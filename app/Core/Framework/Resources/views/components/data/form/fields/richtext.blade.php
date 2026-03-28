@props(['field'])

@php
    $name = "form." . $field['name'];
    $isReadOnly = $field['readonly'] ?? false;
@endphp

<flux:field {{ $attributes }}>
    
    <x-core::data.form.fields.label :field="$field" />

    <div 
        x-data="{
            value: @entangle($name),
            quill: null,
            fullScreen: false,
            init() {
                this.quill = new Quill($refs.editor, {
                    theme: 'snow',
                    readOnly: @js($isReadOnly),
                    placeholder: 'Commencez à rédiger votre contenu...',
                    modules: {
                        toolbar: $refs.toolbar,
                        history: { delay: 2000, maxStack: 500, userOnly: true }
                    }
                });

                if (this.value) {
                    this.quill.root.innerHTML = this.value;
                }

                this.quill.on('text-change', () => {
                    let html = this.quill.root.innerHTML;
                    if (html === '<p><br></p>') html = '';
                    this.value = html;
                });

                $watch('value', (newVal) => {
                    if (newVal !== this.quill.root.innerHTML) {
                        this.quill.root.innerHTML = newVal || '';
                    }
                });
            },
            undo() {
                if (!this.quill) return;
                try {
                    this.quill.history.undo();
                } catch (e) {
                    console.log(e);
                }
            },

            redo() {
                if (!this.quill) return;
                try {
                    this.quill.history.redo();
                } catch (e) {
                    console.log('Erreur redo évitée');
                }
            },
            toggleFullScreen() {
                this.fullScreen = !this.fullScreen;
                document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
            }
        }"
        @keydown.window.escape="if(fullScreen) toggleFullScreen()"
        :class="fullScreen ? 'fixed inset-0 z-999 bg-white dark:bg-zinc-800 flex flex-col p-4' : 'border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-800 relative'"
        wire:ignore
    >
        {{-- Barre d'outils Ultra-Complète --}}
        <div x-ref="toolbar" class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/50 border-0 p-2">
            <div class="flex-1">
                {{-- Groupe 1 : Historique & Format --}}
                <span class="ql-formats">
                    <select class="ql-header">
                        <option selected>Texte</option>
                        <option value="1">Titre 1</option>
                        <option value="2">Titre 2</option>
                        <option value="3">Titre 3</option>
                    </select>
                </span>

                {{-- Groupe 2 : Styles de caractère --}}
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-strike"></button>
                    <button class="ql-code"></button>
                    <button class="ql-blockquote"></button>
                    <select class="ql-color"></select>
                    <select class="ql-background"></select>
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-list" value="check"></button> {{-- Task List --}}
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                    <select class="ql-align"></select>
                    <button class="ql-link"></button>
                    <button class="ql-image"></button>
                    <button class="ql-video"></button>
                    <button class="ql-clean"></button>
                </span>
                <span class="ql-formats">
                    <button type="button" 
                        @mousedown.prevent="toggleFullScreen()" 
                        class="p-2 text-zinc-500 hover:text-zinc-600 transition-colors"
                        title="Plein écran (Echap pour quitter)"
                    >
                        {{-- Icône Expand (Agrandir) --}}
                        <svg x-show="!fullScreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                        {{-- Icône Shrink (Réduire) --}}
                        <svg x-show="fullScreen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4v5m0 0H4m5 0L4 4m11 0v5m0 0h5m-5 0l5-5M9 20v-5m0 0H4m5 0l-5 5m11 0l-5-5m5 5v-5m0 0h5" />
                        </svg>
                    </button>  
                </span>            
            </div>
        </div>

        {{-- Zone d'édition --}}
        <div x-ref="editor" 
            :class="fullScreen ? 'flex-1 overflow-y-auto' : 'min-h-[250px]'"
            class="border-0 dark:text-zinc-200 prose prose-sm dark:prose-invert max-w-none"
        ></div>
    </div>

    <flux:error :name="$name" />
</flux:field>

<style>
    .fixed .ql-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .fixed .ql-editor {
        flex: 1;
        overflow-y: auto;
    }
    
    /* Styles standards Quill */
    .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #f4f4f5 !important; }
    .dark .ql-toolbar.ql-snow { border-bottom-color: #27272a !important; }
    .ql-container.ql-snow { border: none !important; }
    /* Design System Integration */
    .ql-toolbar.ql-snow { 
        border: none !important; 
        border-bottom: 1px solid #f4f4f5 !important; 
    }
    .dark .ql-toolbar.ql-snow { 
        border-bottom-color: #27272a !important; 
    }
    
    
    /* Correction Icônes mode sombre */
    .dark .ql-snow .ql-stroke { stroke: #a1a1aa !important; }
    .dark .ql-snow .ql-fill { fill: #a1a1aa !important; }
    .dark .ql-snow .ql-picker { color: #a1a1aa !important; }
    .dark .ql-snow .ql-picker-options { background-color: #18181b !important; border-color: #27272a !important; }

    /* Rendu des listes de tâches */
    .ql-editor li[data-list="confirmed"] > .ql-ui::before,
    .ql-editor li[data-list="checked"] > .ql-ui::before {
        color: #4f46e5;
    }
</style>