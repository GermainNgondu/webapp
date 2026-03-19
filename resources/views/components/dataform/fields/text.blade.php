@props(['field'])

<flux:field {{ $attributes }}>
    
    <x-dataform.fields.label :field="$field" />
    
    <flux:input 
        wire:model="form.{{ $field['name'] }}" 
        :disabled="$field['readonly'] ?? false"
        type="{{ $field['type'] ?? 'text' }}" 
    />

    <flux:error name="form.{{ $field['name'] }}" />
</flux:field>