@props(['field'])

@php
    $modelName = "form." . $field['name'];
    $wireModel = isset($field['visibleIf']) 
        ? 'wire:model.live.debounce.250ms' 
        : 'wire:model';
@endphp
<flux:field {{ $attributes }}>
    
    <x-core::data.form.fields.label :field="$field" />
    
    <flux:input 
        {{ $attributes->merge([$wireModel => $modelName]) }}
        :disabled="$field['readonly'] ?? false"
        type="{{ $field['type'] ?? 'text' }}"
    />
    
    @isset($field['description'])
    <flux:description>{{$field['description']}}</flux:description>
    @endisset
    <flux:error name="form.{{ $field['name'] }}" />
    
</flux:field>