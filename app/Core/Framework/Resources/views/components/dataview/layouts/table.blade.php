@props(['items', 'schema', 'actions' => []])

<flux:table container:class="max-h-[calc(100vh-150px)]">
    <flux:table.columns sticky class="bg-white dark:bg-zinc-900">
        @foreach($schema as $field => $config)
            <flux:table.column 
                :sortable="$config['sortable'] ?? false"
                :direction="$this->sort === $field ? 'asc' : ($this->sort === '-'.$field ? 'desc' : null)"
                :wire:click="($config['sortable'] ?? false) ? 'sortBy(\''.$field.'\')' : ''"
                :class="($config['sortable'] ?? false) ? 'cursor-pointer' : ''"
                >
                {{ $config['label'] }}
            </flux:table.column>
        @endforeach
        <flux:table.column class="text-right">Actions</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach($items as $item)
            <flux:table.row :key="'row-'.$item->id">
                @foreach($schema as $field => $config)
                    <flux:table.cell>
                        @if($config['component'])
                            <x-dynamic-component :component="$config['component']" :value="$item->$field" />
                        @else
                            {{ $item->$field }}
                        @endif
                    </flux:table.cell>
                @endforeach
                <flux:table.cell>
                    <x-core::dataview.actions.row :actions="$actions['row'] ?? []" :item="$item" />
                </flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>