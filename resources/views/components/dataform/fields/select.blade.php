{{-- resources/views/components/dataform/fields/select.blade.php --}}
@props(['field'])

@php
    $options = $field['options'] ?? [];
    $name = "form." . $field['name'];
    $isReadOnly = $field['readonly'] ?? false;
    $isMultiple = $field['multiple'] ?? false;
    $lazy = $field['lazy'] ?? null; {{-- On récupère la config lazy --}}
@endphp

<flux:field>
    <flux:label>{{ $field['label'] }}</flux:label>

    <div x-data="{
            open: false,
            search: '',
            value: @entangle($name),
            options: {{ json_encode($options) }},
            multiple: {{ $isMultiple ? 'true' : 'false' }},
            
            {{-- Propriétés pour le Lazy Loading --}}
            page: 1,
            hasMore: {{ $lazy ? 'true' : 'false' }},
            loading: false,

            async loadMore() {
                if (this.loading || !this.hasMore || !@js($lazy)) return;
                this.loading = true;

                let result = await $wire.searchLazyOptions(
                    '{{ $lazy['model'] ?? '' }}',
                    '{{ $lazy['labelColumn'] ?? '' }}',
                    '{{ $lazy['valueColumn'] ?? '' }}',
                    this.search,
                    this.page
                );

                this.options = { ...this.options, ...result.data };
                this.hasMore = result.hasMore;
                this.page++;
                this.loading = false;
            },

            {{-- On réinitialise si la recherche change --}}
            async resetAndSearch() {
                if (!@js($lazy)) return;
                this.options = {};
                this.page = 1;
                this.hasMore = true;
                await this.loadMore();
            },

            clear() {
                this.value = this.multiple ? [] : null;
                this.search = '';
                this.open = false;
            },

            toggle(key) {
                if (this.multiple) {
                    if (!Array.isArray(this.value)) this.value = [];
                    const index = this.value.indexOf(key);
                    if (index > -1) { this.value.splice(index, 1); } 
                    else { this.value.push(key); }
                } else {
                    this.value = key;
                    this.open = false;
                }
            },

            isSelected(key) {
                if (this.multiple) return Array.isArray(this.value) && this.value.includes(key);
                return this.value === key;
            },

            get hasValue() {
                if (this.multiple) return Array.isArray(this.value) && this.value.length > 0;
                return this.value !== null && this.value !== '';
            },

            get filteredOptions() {
                {{-- Si lazy, on ne filtre pas en JS, on laisse le serveur faire --}}
                if (@js($lazy)) return this.options;
                
                if (this.search === '') return this.options;
                return Object.keys(this.options)
                    .filter(key => this.options[key].toLowerCase().includes(this.search.toLowerCase()))
                    .reduce((obj, key) => { obj[key] = this.options[key]; return obj; }, {});
            },

            get selectedLabel() {
                if (this.multiple) return '';
                return this.options[this.value] || 'Sélectionner...';
            }
         }"
         {{-- On surveille le changement de recherche pour le lazy --}}
         x-init="$watch('search', () => resetAndSearch())"
         class="relative w-full">

        <div class="relative group">
            <button type="button"
                @click="!{{ $isReadOnly ? 'true' : 'false' }} && (open = !open)"
                class="w-full flex items-center justify-between px-3 py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm text-sm text-left min-h-[42px]">
                
                <div class="flex flex-wrap gap-1 pr-6">
                    <template x-if="multiple && hasValue">
                        <template x-for="val in value" :key="val">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-md border border-indigo-100">
                                <span x-text="options[val]"></span>
                                <span @click.stop="toggle(val)" class="hover:text-indigo-900 cursor-pointer">
                                    <flux:icon name="x-mark" variant="mini" class="h-3 w-3" />
                                </span>
                            </span>
                        </template>
                    </template>
                    <template x-if="!multiple || !hasValue">
                        <span :class="hasValue ? 'text-zinc-900' : 'text-zinc-400'" x-text="selectedLabel"></span>
                    </template>
                </div>

                <div class="absolute right-3 flex items-center gap-2">
                    <div x-show="hasValue" @click.stop="clear()" class="text-zinc-400 hover:text-zinc-600 cursor-pointer">
                        <flux:icon name="x-mark" variant="mini" class="h-4 w-4" />
                    </div>
                    <flux:icon name="chevrons-up-down" variant="mini" class="h-4 w-4 text-zinc-400" />
                </div>
            </button>
        </div>

        <div x-show="open" @click.away="open = false" x-cloak class="absolute z-50 mt-2 w-full bg-white dark:bg-zinc-800 border border-zinc-200 rounded-xl shadow-xl overflow-hidden">
            
            <div class="p-2 border-b border-zinc-100 bg-zinc-50/50">
                <input type="text" x-model.debounce.500ms="search" placeholder="Rechercher..." class="w-full pl-3 pr-3 py-1.5 text-xs border rounded-md outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <ul class="max-h-60 overflow-y-auto p-1 scrollbar-hide">
                <template x-for="(label, key) in filteredOptions" :key="key">
                    <li>
                        <button type="button" @click="toggle(key)" class="w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-zinc-50 flex items-center justify-between">
                            <span x-text="label"></span>
                            <template x-if="isSelected(key)">
                                <flux:icon name="check" variant="mini" class="h-4 w-4 text-indigo-600" />
                            </template>
                        </button>
                    </li>
                </template>

                {{-- ÉLÉMENT SENTINELLE : C'est ici que la magie opère --}}
                @if($lazy)
                    <div x-intersect="loadMore()" class="p-2 flex justify-center">
                        <template x-if="loading">
                            <flux:icon name="arrow-path" class="animate-spin h-4 w-4 text-zinc-400" />
                        </template>
                    </div>
                @endif

                <li x-show="Object.keys(filteredOptions).length === 0 && !loading" class="px-3 py-4 text-center text-xs text-zinc-500 italic">
                    Aucun résultat
                </li>
            </ul>
        </div>
    </div>
</flux:field>