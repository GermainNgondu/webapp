@props(['field'])

@if($field['type'] === 'hidden')
    <x-core::data.form.fields.hidden :field="$field" />
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
                    <x-core::data.form.fields.checkbox :field="$field" {{ $attributes }} />
                @break
                @case('select')
                    <x-core::data.form.fields.select :field="$field" {{ $attributes }} />
                    @break
                @case('repeater')
                    <x-core::data.form.fields.repeater :field="$field" {{ $attributes }} />
                    @break
                @case('toggle')
                    <x-core::data.form.fields.toggle :field="$field" {{ $attributes }} />
                    @break
                @case('date')
                    <x-core::data.form.fields.date :field="$field" {{ $attributes }} />
                    @break
                @case('password')
                    <x-core::data.form.fields.password :field="$field" {{ $attributes }} />
                    @break
                @case('richtext')
                    <x-core::data.form.fields.richtext :field="$field" {{ $attributes }} />
                    @break
                @case('media-picker')
                    
                    @php $model = "form.".$field['name']; @endphp

                    <x-core::data.form.fields.media-picker 
                        :field="$field"
                        :model="$this->getPropertyValue($model)"
                        wire:model="{{ $model }}"
                    />
                    @break
                @case('blocks')
                    <x-core::data.form.fields.blocks :field="$field" {{ $attributes }} />
                    @break
                @default
                    <x-core::data.form.fields.text :field="$field" {{ $attributes }} />
            @endswitch 

    </div>
    
@endif
