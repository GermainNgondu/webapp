{{-- resources/views/components/data.form/fields/date.blade.php --}}
@props(['field'])

@php
    $name = "form." . $field['name'];
    $options = $field['options'] ?? [];
    
    $mode = $options['mode'] ?? 'date'; 
    $format = $options['format'] ?? ($mode === 'datetime' ? 'Y-m-d H:i' : ($mode === 'time' ? 'H:i' : 'Y-m-d'));
    
    $enableTime = in_array($mode, ['datetime', 'time']) ? 'true' : 'false';
    $noCalendar = ($mode === 'time') ? 'true' : 'false';
    $isReadOnly = $field['readonly'] ?? false;
@endphp

<flux:field {{ $attributes }}>

    <x-core::data.form.fields.label :field="$field" />

    <div 
        x-data="{
            value: @entangle($name),
            instance: null,
            init() {
                this.instance = flatpickr($refs.input, {
                    enableTime: {{ $enableTime }},
                    noCalendar: {{ $noCalendar }},
                    dateFormat: '{{ $format }}',
                    time_24hr: true,
                    locale: 'fr',
                    allowInput: true,
                    disableMobile: true,
                    {{-- On initialise avec la valeur actuelle si elle existe --}}
                    defaultDate: this.value || null,
                    onChange: (selectedDates, dateStr) => {
                        this.value = dateStr;
                    }
                });

                $watch('value', val => {
                    if (this.instance && val !== this.instance.currentStr) {
                        this.instance.setDate(val, false);
                    }
                });
            }
        }"
        class="relative"
        wire:ignore {{-- Crucial pour ne pas que Livewire détruise l'instance Flatpickr --}}
    >
        <flux:input 
            x-ref="input"
            {{-- ATTENTION : On ne met PAS de :value="$name" ici --}}
            :disabled="$isReadOnly"
            placeholder="Sélectionner..."
            icon="calendar"
        />
    </div>

    <flux:error name="{{ $name }}" />
</flux:field>