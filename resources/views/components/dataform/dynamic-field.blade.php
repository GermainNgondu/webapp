{{-- resources/views/components/dataform/dynamic-field.blade.php --}}
@props(['field'])

@if($field['type'] === 'hidden')
    <x-dataform.fields.hidden :field="$field" />
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
                @case('select')
                    <x-dataform.fields.select :field="$field" {{ $attributes }} />
                    @break
                @case('repeater')
                    <x-dataform.fields.repeater :field="$field" {{ $attributes }} />
                    @break
                @case('toggle')
                    <x-dataform.fields.toggle :field="$field" {{ $attributes }} />
                    @break
                @case('date')
                    <x-dataform.fields.date :field="$field" {{ $attributes }} />
                    @break
                @case('password')
                    <x-dataform.fields.password :field="$field" {{ $attributes }} />
                    @break
                @case('richtext')
                    <x-dataform.fields.richtext :field="$field" {{ $attributes }} />
                    @break
                @case('media-picker')
                    
                    @php $model = "form.".$field['name']; @endphp

                    <x-dataform.fields.media-picker 
                        :field="$field"
                        :model="$this->getPropertyValue($model)"
                        wire:model="{{ $model }}"
                    />
                    @break
                @default
                    <x-dataform.fields.text :field="$field" {{ $attributes }} />
            @endswitch 

    </div>
    
@endif
