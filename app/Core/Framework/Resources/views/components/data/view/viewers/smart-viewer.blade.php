@props(['item', 'value' => null])

@php
    $val = $value ?? $item->url ?? null;
    $extension = strtolower(pathinfo($val, PATHINFO_EXTENSION));
    $mime = $item->mime_type ?? '';

    $component = match(true) {
        // Tableaux & Collections
        is_array($val) && isset($val[0]['label']) => 'core::data.view.viewers.list-viewer',
        is_array($val) && count($val) > 0 && is_array($val[0]) => 'core::data.view.viewers.table-viewer',
        is_array($val) && count($val) > 1 => 'core::data.view.viewers.gallery-viewer',

        // Fichiers spécifiques
        $extension === 'pdf' => 'core::data.view.viewers.pdf-viewer',
        in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'pptx']) => 'core::data.view.viewers.office-viewer',
        in_array($extension, ['php', 'js', 'json', 'txt', 'md']) => 'core::data.view.viewers.code-viewer',
        
        // Médias classiques
        str_contains($mime, 'image') => 'core::data.view.viewers.media-viewer',
        str_contains($mime, 'video') || str_contains($mime, 'audio') => 'core::data.view.viewers.media-viewer',

        default => 'core::data.view.viewers.media-viewer'
    };
@endphp

<x-dynamic-component :component="$component" :item="$item" :value="$val" />