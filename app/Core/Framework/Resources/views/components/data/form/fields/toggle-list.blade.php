@props(['field'])

@php
    $name = "form." . $field['name'];
    $options = $field['options'] ?? [];
@endphp

<flux:field {{ $attributes }}>
    
    <x-core::data.form.fields.label :field="$field" />

    @if(!empty($field['description']))
        <flux:description>{{ $field['description'] }}</flux:description>
    @endif

    <div 
        x-data="{ 
            selected: @entangle($name).fallback([]),
            toggle(value) {
                if (this.selected.includes(value)) {
                    this.selected = this.selected.filter(i => i !== value);
                } else {
                    this.selected.push(value);
                }
            }
        }" 
        class="flex flex-wrap gap-2 mt-2"
    >
        @foreach($options as $value => $label)
            <button
                type="button"
                @click="toggle('{{ $value }}')"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200"
                :class="selected.includes('{{ $value }}') 
                    ? 'bg-zinc-600 border-zinc-600 text-white shadow-sm ring-1 ring-zinc-600' 
                    : 'bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 hover:border-zinc-300 dark:hover:border-zinc-700'"
                :disabled="{{ $field['readonly'] ? 'true' : 'false' }}"
            >
                {{-- Petite icône check si sélectionné --}}
                <template x-if="selected.includes('{{ $value }}')">
                    <flux:icon name="check" variant="mini" class="h-4 w-4" />
                </template>
                
                {{ $label }}
            </button>
        @endforeach
    </div>

    <flux:error :name="$name" />
</flux:field>