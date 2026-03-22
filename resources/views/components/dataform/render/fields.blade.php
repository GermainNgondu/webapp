@props(['fields'])

<div class="grid grid-cols-12 gap-6 pb-8">
    @foreach($fields as $field)
        @if(($field['type'] ?? '') === 'hidden')
            <x-dataform.dynamic-field :field="$field" />
        @else
            <div class="col-span-12 md:col-span-{{ $field['colSpan'] }}">
                @if(isset(${"slot_" . $field['name']}))
                    {{ ${"slot_" . $field['name']} }}
                @else
                    <x-dataform.dynamic-field :field="$field" />
                @endif

            </div>
        @endif
    @endforeach
</div>