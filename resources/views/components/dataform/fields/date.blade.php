@props(['field'])
<flux:field {{ $attributes }}>
    <x-dataform.fields.label :field="$field" />
    <flux:input type="date" wire:model.blur="form.{{ $field['name'] }}" />
    <flux:error name="form.{{ $field['name'] }}" />
</flux:field>