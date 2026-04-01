@props(['field'])

@php
    $modelName = "form." . $field['name'];
    $wireModel = isset($field['visibleIf']) 
        ? 'wire:model.live.debounce.250ms' 
        : 'wire:model';
    $type = $field['type'] ?? 'text' 
@endphp
<flux:field {{ $attributes }}>
    
    <x-core::data.form.fields.label :field="$field" />
    
    @if($type == 'textarea')
    <flux:textarea 
        {{ $attributes->merge([$wireModel => $modelName]) }}
        :disabled="$field['readonly'] ?? false"
    />
    @else
    <flux:input 
        {{ $attributes->merge([$wireModel => $modelName]) }}
        :disabled="$field['readonly'] ?? false"
        :type="$type"
        rows="auto"
    />
    @endif
    
    @isset($field['description'])
    <flux:description>{{$field['description']}}</flux:description>
    @endisset
    <flux:error name="form.{{ $field['name'] }}" />
    
</flux:field>