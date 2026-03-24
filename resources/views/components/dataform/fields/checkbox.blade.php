{{-- resources/views/components/dataform/fields/checkbox.blade.php --}}
@props(['field'])

@php
    $name = "form." . $field['name'];
@endphp

<flux:field>
    <div class="flex items-start">
        <flux:checkbox 
            wire:model="{{ $name }}" 
            :label="$field['label']" 
            :description="$field['description'] ?? null"
            :disabled="$field['readonly'] ?? false"
            {{-- On s'assure que la checkbox prend toute la largeur si nécessaire --}}
            class="cursor-pointer"
        />
    </div>

    {{-- Affichage automatique de l'erreur de validation --}}
    <flux:error :name="$name" />
</flux:field>