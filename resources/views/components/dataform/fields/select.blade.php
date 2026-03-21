@props(['field'])

@php
    $options = $field['options'] ?? [];
    $name = "form." . $field['name'];
    $isReadOnly = $field['readonly'] ?? false;
    $isMultiple = $field['multiple'] ?? false;
    $lazy = $field['lazy'] ?? null;
    $hasCondition = isset($field['visibleIf']);
    $modifier = $hasCondition ? '.live.debounce.250ms' : '';
@endphp


<flux:field {{ $attributes }}>

    <x-dataform.fields.label :field="$field" />

    <div x-data="{
            open: false,
            search: '',
            value: @entangle($name){{ $modifier }},
            options: {{ json_encode($options) }},
            multiple: {{ $isMultiple ? 'true' : 'false' }},

            {{-- Logique Lazy Loading --}}
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

            async resetAndSearch() {
                if (!@js($lazy)) return;
                this.options = {};
                this.page = 1;
                this.hasMore = true;
                await this.loadMore();
            },

            {{-- Logique d'origine préservée --}}
            clear() {
                this.value = this.multiple ? [] : null;
                this.search = '';
                this.open = false;
            },

            toggle(key) {
                if (this.multiple) {
                    if (!Array.isArray(this.value)) this.value = [];
                    const index = this.value.indexOf(key);
                    if (index > -1) {
                        this.value.splice(index, 1);
                    } else {
                        this.value.push(key);
                    }
                } else {
                    this.value = key;
                    this.open = false;
                }
            },

            isSelected(key) {
                if (this.multiple) {
                    return Array.isArray(this.value) && this.value.includes(key);
                }
                return this.value === key;
            },

            get hasValue() {
                if (this.multiple) return Array.isArray(this.value) && this.value.length > 0;
                return this.value !== null && this.value !== '';
            },

            get filteredOptions() {
                if (@js($lazy)) return this.options;
                
                if (this.search === '') return this.options;
                return Object.keys(this.options)
                    .filter(key => this.options[key].toLowerCase().includes(this.search.toLowerCase()))
                    .reduce((obj, key) => {
                        obj[key] = this.options[key];
                        return obj;
                    }, {});
            },

            get selectedLabel() {
                if (this.multiple) return '';
                return this.options[this.value] || 'Sélectionner...';
            }
         }"
         x-init="@js($lazy) && $watch('search', () => resetAndSearch())"
         class="relative w-full">

        <div class="relative group">
            <button type="button"
                @click="!{{ $isReadOnly ? 'true' : 'false' }} && (open = !open)"
                :disabled="{{ $isReadOnly ? 'true' : 'false' }}"
                :class="open ? 'ring-2 ring-zinc-500 border-zinc-500' : 'border-zinc-200 dark:border-zinc-700'"
                class="w-full flex items-center justify-between px-3 py-2 bg-white dark:bg-zinc-800 border rounded-lg shadow-sm text-sm text-left transition-all disabled:opacity-50 disabled:bg-zinc-50 min-h-[42px]">

                <div class="flex flex-wrap gap-1 pr-6">
                    {{-- Affichage Multi-select (Tags) avec Dark Mode --}}
                    <template x-if="multiple && hasValue">
                        <template x-for="val in value" :key="val">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-zinc-50 dark:bg-zinc-900/30 text-zinc-700 dark:text-zinc-300 text-xs font-medium rounded-md border border-zinc-100 dark:border-zinc-800">
                                <span x-text="options[val]"></span>
                                @if(!$isReadOnly)
                                    <span @click.stop="toggle(val)" class="hover:text-zinc-900 dark:hover:text-zinc-100 cursor-pointer">
                                        <flux:icon name="x-mark" variant="mini" class="h-3 w-3" />
                                    </span>
                                @endif
                            </span>
                        </template>
                    </template>
                    {{-- Affichage Single-select ou Placeholder --}}
                    <template x-if="!multiple || !hasValue">
                        <span :class="hasValue ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-400'" x-text="selectedLabel"></span>
                    </template>
                </div>

                <div class="absolute right-3 flex items-center gap-2">
                    @if(!$isReadOnly)
                        <div x-show="hasValue" x-cloak @click.stop="clear()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer transition-colors p-0.5">
                            <flux:icon name="x-mark" variant="mini" class="h-4 w-4" />
                        </div>
                    @endif
                    <flux:icon name="chevrons-up-down" variant="mini" class="h-4 w-4 text-zinc-400" />
                </div>
            </button>
        </div>

        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak
             class="absolute z-50 mt-2 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl overflow-hidden">
            
            <div class="p-2 border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="relative">
                    <flux:icon name="magnifying-glass" variant="mini" class="absolute left-2.5 top-2.5 h-4 w-4 text-zinc-400" />
                    <input type="text"
                           x-model.debounce.500ms="search"
                           placeholder="Rechercher..."
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md focus:ring-1 focus:ring-zinc-500 outline-none text-zinc-900 dark:text-zinc-100">
                </div>
            </div>

            <ul class="max-h-60 overflow-y-auto p-1 scrollbar-hide">
                <template x-for="(label, key) in filteredOptions" :key="key">
                    <li>
                        <button type="button"
                                @click="toggle(key)"
                                :class="isSelected(key) ? 'bg-zinc-50 text-zinc-700 dark:bg-zinc-900/30 dark:text-zinc-300' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700/50'"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm flex items-center justify-between transition-colors">
                            <span x-text="label"></span>
                            <template x-if="isSelected(key)">
                                <flux:icon name="check" variant="mini" class="h-4 w-4" />
                            </template>
                        </button>
                    </li>
                </template>

                {{-- ÉLÉMENT SENTINELLE POUR LE LAZY LOADING --}}
                @if($lazy)
                    <div x-intersect="loadMore()" class="p-2 flex justify-center">
                        <template x-if="loading">
                            <flux:icon name="arrow-path" class="animate-spin h-4 w-4 text-zinc-400" />
                        </template>
                    </div>
                @endif

                <li x-show="Object.keys(filteredOptions).length === 0 && !loading" class="px-3 py-4 text-center text-xs text-zinc-500 italic">
                    Aucun résultat trouvé
                </li>
            </ul>
        </div>
    </div>

    <flux:error name="{{ $name }}" />
</flux:field>