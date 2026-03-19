{{-- resources/views/components/dataform/dynamic-field.blade.php --}}
@props(['field'])

@php
    $visibleIf = $field['visibleIf'] ?? null;
    $xShow = 'true';

    if ($visibleIf) {
        $targetField = $visibleIf['field'];
        $expectedValue = json_encode($visibleIf['value']);
        
        // Le nom du champ (ex: contacts.temp_abc.type)
        $path = $field['name']; 
        $parts = explode('.', $path);
        
        if (count($parts) > 1) {
            // On est dans un repeater : on cible le champ frère dans la même ligne
            array_pop($parts); 
            $parentPath = implode('.', $parts);
            $fullTarget = "form.{$parentPath}.{$targetField}";
        } else {
            // On est à la racine du formulaire
            $fullTarget = "form.{$targetField}";
        }

        // AJOUT DE $wire : Crucial pour éviter l'erreur "form is not defined"
        $xShow = "\$wire.{$fullTarget} == {$expectedValue}";
    }
@endphp

<div x-show="{{ $xShow }}" x-cloak x-transition>
    @switch($field['type'])
        @case('select')
            <x-dataform.fields.select :field="$field" {{ $attributes }} />
            @break

        @case('repeater')
            <x-dataform.fields.repeater :field="$field" {{ $attributes }} />
            @break

        @case('toggle')
            <x-dataform.fields.toggle :field="$field" {{ $attributes }} />
            @break

        @default
            <x-dataform.fields.text :field="$field" {{ $attributes }} />
    @endswitch
</div>