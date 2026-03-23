@props(['field'])

@php
    $blocks = data_get($this->form, $field['name'], []);
    $isReadOnly = $field['readonly'] ?? false;
    $allowedBlocks = $field['allowedBlocks'] ?? [];
@endphp

<div x-data="{ 
    openBlockId: null,
    deletingIndex: null,
    fieldName: '{{ $field['name'] }}',
    initSortable() {
        if ({{ $isReadOnly ? 'true' : 'false' }}) return;

        new Sortable($refs.blockList, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: (evt) => {
                let newOrder = Array.from($refs.blockList.children).map(el => el.dataset.index);
                
                // Reset DOM to let Livewire handle it
                const item = evt.item;
                const parent = item.parentNode;
                if (evt.newIndex > evt.oldIndex) {
                    parent.insertBefore(item, parent.children[evt.oldIndex]);
                } else {
                    parent.insertBefore(item, parent.children[evt.oldIndex].nextSibling);
                }

                $wire.reorderBlocks(this.fieldName, newOrder);
            }
        });
    }
}" x-init="initSortable()" {{ $attributes }}>
    
    <x-dataform.fields.label :field="$field" />

    <div x-ref="blockList" class="space-y-4">
        @foreach($blocks as $index => $block)
            @php
                $blockType = $block['type'] ?? 'unknown';
                $blockClass = $block['class'] ?? null;
                $blockConfig = collect($allowedBlocks)->firstWhere('class', $blockClass);
                $schema = $blockConfig['schema'] ?? [];
                $title = $blockType;
            @endphp

            <div wire:key="block-{{ $block['id'] ?? $index }}" data-index="{{ $index }}"
                :class="openBlockId === '{{ $index }}' ? 'z-30' : 'z-0'"
                class="relative bg-white dark:bg-zinc-900 border border-zinc-200 rounded-xl shadow-sm transition-all">
                
                <div class="flex items-center justify-between p-3 bg-zinc-50/50 dark:bg-zinc-800/50 border-b">
                    <div class="flex items-center gap-3 flex-1">
                        @if(!$isReadOnly)
                            <div class="drag-handle cursor-grab text-zinc-400 p-1">
                                <flux:icon name="bars-4" variant="mini" class="h-4 w-4" />
                            </div>
                        @endif
                        <div @click="openBlockId = (openBlockId === '{{ $index }}' ? null : '{{ $index }}')" class="flex items-center gap-3 cursor-pointer">
                            <flux:icon name="chevron-right" variant="mini" class="h-4 w-4 transition-transform" ::class="openBlockId === '{{ $index }}' ? 'rotate-90' : ''" />
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">{{ $title }}</span>
                        </div>
                    </div>
                    @if(!$isReadOnly)
                        <flux:button variant="ghost" size="xs" icon="trash" type="button" 
                            @click.stop="deletingIndex = '{{ $index }}'; $flux.modal('confirm-delete-block-{{ $field['name'] }}').show()"
                            class="text-zinc-400 hover:text-red-500 cursor-pointer size-4" />
                    @endif
                </div>

                <div x-show="openBlockId === '{{ $index }}'" x-collapse>
                    <div class="p-5 grid grid-cols-12 gap-5 bg-white dark:bg-zinc-900">
                        @foreach($schema as $sub)
                            @php 
                                $subFieldPath = array_merge($sub, [
                                    'name' => $field['name'] . '.' . $index . '.data.' . $sub['name'],
                                    'readonly' => $isReadOnly
                                ]);
                            @endphp
                            <div class="col-span-12 md:col-span-{{ $sub['colSpan'] }}" wire:key="block-field-{{ $index }}-{{ $sub['name'] }}">
                                <x-dataform.dynamic-field :field="$subFieldPath" wire:key="field-{{ $index }}-{{ $sub['name'] }}"/>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(!$isReadOnly && count($allowedBlocks) > 0)
        <div class="mt-6">
            <flux:dropdown>
                <flux:button variant="ghost" icon="plus" class="w-full border-2 border-dashed cursor-pointer">
                    {{ $field['placeholder'] }}
                </flux:button>

                <flux:menu>
                    @foreach($allowedBlocks as $availableBlock)
                        <flux:menu.item class="cursor-pointer" wire:click="addBlock('{{ $field['name'] }}', '{{ str_replace('\\', '\\\\', $availableBlock['class']) }}')">
                            {{ $availableBlock['type'] }}
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>
        </div>
    @endif

    <flux:modal name="confirm-delete-block-{{ $field['name'] }}" class="min-w-88">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Supprimer ce bloc ?</flux:heading>
                <flux:subheading>
                    Cette action est irréversible. Toutes les données de ce bloc seront perdues.
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Annuler</flux:button>
                </flux:modal.close>

                <flux:button variant="danger"  class="cursor-pointer"
                    @click="$wire.removeBlock(fieldName, deletingIndex); $flux.modal('confirm-delete-block-{{ $field['name'] }}').close()">
                    Confirmer la suppression
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
