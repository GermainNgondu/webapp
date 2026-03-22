@props(['field'])

<flux:field {{ $attributes }}>
    <div class="flex items-center justify-between">
        <x-dataform.fields.label :field="$field" />
        <flux:switch wire:model="form.{{ $field['name'] }}" />
    </div>
    <flux:error name="form.{{ $field['name'] }}" />
</flux:field>