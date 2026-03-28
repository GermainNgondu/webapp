@props(['sections'])
@php
    $fieldMap = [];
    foreach ($sections as $index => $section) {
        foreach ($section['fields'] as $field) {
            $fieldMap['form.' . $field['name']] = $index;
        }
    }
@endphp
<div 
    x-data="{ 
        active: [],
        init() {
            let sectionsWithErrors = [];
            if (sectionsWithErrors.length > 0) {
                this.active = sectionsWithErrors;
            } else {
                this.active = [0];
            }
        },

        toggle(index) {
            if (this.active.includes(index)) {
                this.active = this.active.filter(i => i !== index);
            } else {
                this.active.push(index);
            }
        },

        expandAll() {
            this.active = @js(range(0, count($sections) - 1));
        },

        collapseAll() {
            this.active = [];
        }
    }" 
    class="space-y-4"
>
    @if($errors->any())
        <div class="mt-2 mb-2 text-sm flex items-center justify-between p-2 bg-red-200 text-red-800 dark:bg-red-300 dark:text-red-50 rounded-md">
          Il reste des champs obligatoires non remplis.
        </div>
    @endif
    {{-- Barre d'outils de l'accordéon --}}
    <div class="flex justify-between items-center px-1">
        <div>{{ $title ?? '' }}</div>
        <div class="flex items-center gap-2">
            <flux:button 
                variant="ghost" 
                size="xs" 
                icon="chevron-double-down" 
                @click="expandAll()"
                class="cursor-pointer"
            >
                Tout ouvrir
            </flux:button>
            <flux:separator vertical class="h-3" />
            <flux:button 
                variant="ghost" 
                size="xs" 
                icon="chevron-double-up" 
                @click="collapseAll()"
                class="cursor-pointer"
            >
                Tout fermer
            </flux:button>
        </div>
    </div>

    <div class="space-y-3">
        @foreach($sections as $index => $section)
            @php
                $fieldNames = collect($section['fields'])->map(fn($f) => 'form.' . $f['name'])->toArray();
                $hasError = false;
                foreach ($fieldNames as $name) {
                    if ($errors->has($name)) { $hasError = true; break; }
                }
            @endphp

            <div 
                class="group border rounded-xl overflow-hidden transition-all duration-300"
                :class="active.includes({{ $index }}) 
                    ? 'border-zinc-500 ring-1 ring-zinc-500/10 shadow-md bg-white dark:bg-zinc-900' 
                    : 'border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50'"
            >
                {{-- Header --}}
                <button 
                    type="button"
                    @click="toggle({{ $index }})"
                    class="w-full flex items-center justify-between p-4 text-left transition-colors cursor-pointer"
                >
                    <div class="flex items-center gap-3">
                        <div :class="active.includes({{ $index }}) ? 'text-zinc-600' : 'text-zinc-400'">
                            <flux:icon :name="$section['icon'] ?? 'stop'" variant="mini" />
                        </div>
                        
                        <span class="font-semibold text-sm" :class="active.includes({{ $index }}) ? 'text-zinc-900 dark:text-white' : 'text-zinc-500'">
                            {{ $section['title'] }}
                        </span>

                        @if($hasError)
                            <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                        @endif
                    </div>
                    
                    <flux:icon 
                        name="chevron-down" 
                        variant="mini" 
                        class="text-zinc-400 transition-transform duration-300"
                        ::class="active.includes({{ $index }}) ? 'rotate-180 text-zinc-500' : ''"
                    />
                </button>

                {{-- Contenu --}}
                <div 
                    x-show="active.includes({{ $index }})" 
                    x-collapse
                    x-cloak
                >
                    <div class="p-6 border-t border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                        <x-core::data.form.render.fields :fields="$section['fields']" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>