@props(['field'])

@php
    // Mapping des types vers vos composants dans le dossier fields/
    $component = match ($field['type'] ?? 'text') {
        'select'   => 'dataform.fields.select',
        'textarea' => 'dataform.fields.textarea',
        'toggle', 'switch', 'boolean' => 'dataform.fields.toggle',
        'checkbox' => 'dataform.fields.checkbox',
        'date'     => 'dataform.fields.date',
        'repeater' => 'dataform.fields.repeater',
        default    => 'dataform.fields.text',
    };
@endphp

<x-dynamic-component :component="$component" :field="$field" {{ $attributes }}/>