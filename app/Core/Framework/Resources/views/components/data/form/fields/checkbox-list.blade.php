@props(['field'])

@php
    $name = "form." . $field['name'];
    $options = $field['options'] ?? [];
    $isInline = $field['options']['inline'] ?? false;
@endphp

<flux:field {{ $attributes }}>
    
    <x-data.form.fields.label :field="$field" />

    @if(!empty($field['description']))
        <flux:description class="mb-3">{{ $field['description'] }}</flux:description>
    @endif

    <div class="{{ $isInline ? 'flex flex-wrap gap-4' : 'space-y-2' }} mt-2">
        @foreach($options as $value => $label)
            @if($value !== 'inline')
                <flux:checkbox 
                    wire:model="{{ $name }}" 
                    value="{{ $value }}" 
                    label="{{ $label }}" 
                    :disabled="$field['readonly'] ?? false"
                />
            @endif
        @endforeach
    </div>

    <flux:error :name="$name" />
</flux:field>