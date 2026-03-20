@props(['field'])

@isset($field['badge'])
    <flux:label for="{{ $field['name'] }}" badge="{{ $field['badge'] }}">
        {{ $field['label'] }} 
        @if($field['required'] ?? false) 
            <span class="text-red-500">*</span> 
        @endif
    </flux:label>   
@else
    <flux:label for="{{ $field['name'] }}">
        {{ $field['label'] }} 
        @if($field['required'] ?? false) 
            <span class="text-red-500">*</span> 
        @endif
    </flux:label>    
@endisset
