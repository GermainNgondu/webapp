@props(['field'])

<flux:label for="{{ $field['name'] }}">{{ $field['label'] }} @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif</flux:label>