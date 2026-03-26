@props(['value','top'=> false])

@php

    $type = $value instanceof \UnitEnum ? $value->value : $value;

    // Définition des styles et icônes selon le type MIME
    // On utilise des tons Zinc/Gray pour un look pro, ou des couleurs subtiles
    [$icon, $bg, $text, $border, $label] = match (true) {
        str_contains($type, 'image')       => ['photo', 'bg-sky-50', 'text-sky-600', 'border-sky-200', 'Image'],
        str_contains($type, 'video')       => ['play-circle', 'bg-red-50', 'text-red-600', 'border-red-200', 'Vidéo'],
        str_contains($type, 'youtube')       => ['play-circle', 'bg-red-50', 'text-red-600', 'border-red-200', 'YouTube'],
        str_contains($type, 'vimeo')       => ['play-circle', 'bg-blue-50', 'text-blue-600', 'border-blue-200', 'Vimeo'],
        str_contains($type, 'dailymotion')       => ['play-circle', 'bg-zinc-50', 'text-zinc-600', 'border-zinc-200', 'Dailymotion'],
        str_contains($type, 'audio')       => ['music', 'bg-purple-50', 'text-purple-600', 'border-purple-200', 'Audio'],
        str_contains($type, 'document')         => ['document-text', 'bg-rose-50', 'text-rose-600', 'border-rose-200', 'Document'],
        str_contains($type, 'zip') || 
        str_contains($type, 'archive')  => ['archive', 'bg-amber-50', 'text-amber-600', 'border-amber-200', 'Archive'],
        default                            => ['document', 'bg-zinc-50', 'text-zinc-600', 'border-zinc-200', 'Fichier'],
    };
@endphp

<span @class([
    'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border text-[11px] font-medium tracking-wide uppercase',
    $bg, $text, $border
])>
    {{-- L'icône dynamique --}}
    <flux:icon :name="$icon" class="size-3.5 stroke-2" />
    
    {{-- Le label --}}
    <span>{{ $label }}</span>
</span>