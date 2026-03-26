{{-- resources/views/components/dataform/dynamic-field.blade.php --}}
@props(['field'])

@if($field['type'] === 'hidden')
    <x-core::dataform.fields.hidden :field="$field" />
@else   

    @php
        $visibleIf = $field['visibleIf'] ?? null;
        $xShow = 'true';

        if ($visibleIf) {
            $targetField = $visibleIf['field'];
            $expectedValue = $visibleIf['value'];
            $operator = $visibleIf['operator'] ?? '=';
            
            $path = $field['name']; 
            $parts = explode('.', $path);
            if (count($parts) > 1) {
                array_pop($parts); 
                $parentPath = implode('.', $parts);
                $fullTarget = "\$wire.form.{$parentPath}.{$targetField}";
            } else {
                $fullTarget = "\$wire.form.{$targetField}";
            }

            $jsonVal = json_encode($expectedValue);

            $xShow = match ($operator) {
                '='      => "{$fullTarget} == {$jsonVal}",
                '!='     => "{$fullTarget} != {$jsonVal}",
                '>'      => "{$fullTarget} > {$jsonVal}",
                '<'      => "{$fullTarget} < {$jsonVal}",
                'in'     => "{$jsonVal}.includes({$fullTarget})",
                'not_in' => "!{$jsonVal}.includes({$fullTarget})",
                default  => "{$fullTarget} == {$jsonVal}",
            };
        }
    @endphp

    <div x-show="{{ $xShow }}" x-cloak x-transition>

            @switch($field['type'])
                @case('checkbox')
                    <x-core::dataform.fields.checkbox :field="$field" {{ $attributes }} />
                @break
                @case('select')
                    <x-core::dataform.fields.select :field="$field" {{ $attributes }} />
                    @break
                @case('repeater')
                    <x-core::dataform.fields.repeater :field="$field" {{ $attributes }} />
                    @break
                @case('toggle')
                    <x-core::dataform.fields.toggle :field="$field" {{ $attributes }} />
                    @break
                @case('date')
                    <x-core::dataform.fields.date :field="$field" {{ $attributes }} />
                    @break
                @case('password')
                    <x-core::dataform.fields.password :field="$field" {{ $attributes }} />
                    @break
                @case('richtext')
                    <x-core::dataform.fields.richtext :field="$field" {{ $attributes }} />
                    @break
                @case('media-picker')
                    
                    @php $model = "form.".$field['name']; @endphp

                    <x-core::dataform.fields.media-picker 
                        :field="$field"
                        :model="$this->getPropertyValue($model)"
                        wire:model="{{ $model }}"
                    />
                    @break
                @case('blocks')
                    <x-core::dataform.fields.blocks :field="$field" {{ $attributes }} />
                    @break
                @default
                    <x-core::dataform.fields.text :field="$field" {{ $attributes }} />
            @endswitch 

    </div>
    
@endif
