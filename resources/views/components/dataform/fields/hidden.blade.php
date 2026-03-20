@props(['field'])

<input 
    type="hidden" 
    wire:model="form.{{ $field['name'] }}" 
    name="{{ $field['name'] }}"
>