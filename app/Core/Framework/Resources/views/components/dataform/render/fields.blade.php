@props(['fields'])

<div class="grid grid-cols-12 gap-6 pb-8">
    @foreach($fields as $field)
        @if(($field['type'] ?? '') === 'hidden')
            <x-core::dataform.dynamic-field :field="$field" />
        @else
            <div class="col-span-12 md:col-span-{{ $field['colSpan'] }}">
                @if(isset(${"slot_" . $field['name']}))
                    {{ ${"slot_" . $field['name']} }}
                @else
                    <x-core::dataform.dynamic-field :field="$field" />
                @endif

            </div>
        @endif
    @endforeach
</div>