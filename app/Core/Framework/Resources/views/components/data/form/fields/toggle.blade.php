@props(['field'])

<flux:field {{ $attributes }}>
    <div class="flex items-center justify-between">
        <x-core::data.form.fields.label :field="$field" />
        <flux:switch wire:model="form.{{ $field['name'] }}" />
    </div>
    <flux:error name="form.{{ $field['name'] }}" />
</flux:field>