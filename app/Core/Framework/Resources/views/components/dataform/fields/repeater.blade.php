@props(['field'])

@php
    $rows = data_get($this->form, $field['name'], []);
    $isReadOnly = $field['readonly'] ?? false;
@endphp

<div x-data="{ 
    openRowId: null,
    deletingId: null,
    fieldName: '{{ $field['name'] }}',
    initSortable() {
        if ({{ $isReadOnly ? 'true' : 'false' }}) return;

        new Sortable($refs.list, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: (evt) => {
                // IMPORTANT : On récupère l'ordre via data-id
                let newOrder = Array.from($refs.list.children).map(el => el.dataset.id);
                
                // TECHNIQUE DE SURVIE : On annule le mouvement physique 
                // pour que Livewire puisse faire son propre 'Morph' proprement
                const item = evt.item;
                const parent = item.parentNode;
                if (evt.newIndex > evt.oldIndex) {
                    parent.insertBefore(item, parent.children[evt.oldIndex]);
                } else {
                    parent.insertBefore(item, parent.children[evt.oldIndex].nextSibling);
                }

                $wire.reorderRepeaterRow(this.fieldName, newOrder);
            }
        });
    }
}" x-init="initSortable()">

    @if($errors->any())
        <div class="mt-2 mb-2 text-sm flex items-center justify-between p-2 bg-red-500 text-white dark:bg-red-300 dark:text-red-50 rounded-md">
            {{-- Feedback en bas du bouton --}}
            
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            
        </div>
    @endif
    
    <flux:label class="font-bold">{{ $field['label'] }}</flux:label>

    <div x-ref="list" class="space-y-3 mt-4">

        @foreach($rows as $id => $row)
            @php
                $isComplete = collect($field['schema'])->where('required', true)->every(fn($f) => !empty(data_get($row, $f['name'])));
                $title = data_get($row, $field['titleKey']) ?: ($field['addLabel'] ?? 'Élément');
            @endphp

            <div wire:key="row-{{ $id }}" data-id="{{ $id }}"
                :class="openRowId === '{{ $id }}' ? 'z-30' : 'z-0'"
                class="relative bg-white dark:bg-zinc-900 border {{ $isComplete ? 'border-zinc-200' : 'border-orange-300 bg-orange-50/5' }} rounded-xl shadow-sm transition-all">
                
                <div class="flex items-center justify-between p-3 bg-zinc-50/50 dark:bg-zinc-800/50 border-b">
                    <div class="flex items-center gap-3 flex-1">
                        @if(!$isReadOnly)
                            <div class="drag-handle cursor-grab text-zinc-400 p-1"><flux:icon name="bars-4" variant="mini" class="h-4 w-4" /></div>
                        @endif
                        <div @click="openRowId = (openRowId === '{{ $id }}' ? null : '{{ $id }}')" class="flex items-center gap-3 cursor-pointer">
                            <flux:icon name="chevron-right" variant="mini" class="h-4 w-4 transition-transform" ::class="openRowId === '{{ $id }}' ? 'rotate-90' : ''" />
                            <div class="h-2 w-2 rounded-full {{ $isComplete ? 'bg-emerald-500' : 'bg-orange-500 animate-pulse' }}"></div>
                            <span class="text-sm font-bold {{ $isComplete ? 'text-zinc-700' : 'text-orange-700' }}">{{ $title }}</span>
                        </div>
                    </div>
                    @if(!$isReadOnly)
                        <flux:button variant="ghost" size="xs" icon="trash" type="button" @click.stop="deletingId = '{{ $id }}'; $flux.modal('confirm-delete-{{ $field['name'] }}').show()"
                                    class="text-zinc-400 hover:text-red-500 cursor-pointer size-4" />
                    @endif
                </div>

                <div x-show="openRowId === '{{ $id }}'" x-collapse>
                    <div @class(['p-5 grid grid-cols-12 gap-5 bg-white dark:bg-zinc-900'])>
                        @foreach($field['schema'] as $sub)
                            @php 
                                $subFieldPath = array_merge($sub, [
                                    'name' => $field['name'] . '.' . $id . '.' . $sub['name'],
                                    'readonly' => $isReadOnly
                                ]);
                            @endphp
                            <div class="col-span-12 md:col-span-{{ $sub['colSpan'] }}" wire:key="wrapper-{{ $id }}-{{ $sub['name'] }}">
                                <x-core::dataform.dynamic-field :field="$subFieldPath" wire:key="field-{{ $id }}-{{ $sub['name'] }}"/>
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <flux:modal name="confirm-delete-{{ $field['name'] }}" class="min-w-88">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Supprimer cet élément ?</flux:heading>
                <flux:subheading>
                    Cette action est irréversible. Toutes les données de cette ligne seront perdues.
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                {{-- Bouton Annuler : ferme juste la modale --}}
                <flux:modal.close>
                    <flux:button variant="ghost">Annuler</flux:button>
                </flux:modal.close>

                {{-- Bouton Confirmer : appelle Livewire avec l'ID stocké dans Alpine --}}
                <flux:button variant="danger" 
                    @click="$wire.removeRepeaterRow(fieldName, deletingId); $flux.modal('confirm-delete-{{ $field['name'] }}').hide()">
                    Confirmer la suppression
                </flux:button>
            </div>
        </div>
    </flux:modal>

    @if(!$isReadOnly)
        <flux:button type="button" variant="ghost" icon="plus" class="w-full border-2 border-dashed cursor-pointer mt-5"
            wire:click="addRepeaterRow('{{ $field['name'] }}', '{{ str_replace('\\', '\\\\', $field['dataClass']) }}')">
            {{ $field['addLabel'] }}
        </flux:button>
    @endif
</div>