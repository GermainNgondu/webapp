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
        @if($field['required'] ?? false) 
            <span class="text-red-500">*</span> 
        @endif
    </div>
    <flux:error :name="$name" />
</flux:field>