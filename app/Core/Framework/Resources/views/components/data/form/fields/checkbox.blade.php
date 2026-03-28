{{-- resources/views/components/data.form/fields/checkbox.blade.php --}}
@props(['field'])

@php
    $name = "form." . $field['name'];
@endphp

<flux:field {{ $attributes }}>
    
    <div class="flex items-start">
        <flux:checkbox 
            wire:model="{{ $name }}" 
            :label="$field['label']" 
            :description="$field['description'] ?? null"
            :disabled="$field['readonly'] ?? false"
            class="cursor-pointer"
        />
    </div>
    <flux:error :name="$name" />
</flux:field>