@props(['fields'])

<div class="grid grid-cols-12 gap-x-6 gap-y-4">
    @foreach($fields as $field)
        
        @if($field['type'] === 'section_header')
            {{-- Rendu de la Section --}}
            <div class="col-span-12 mt-6 mb-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                <div class="flex items-center gap-2">
                    @if($field['icon'])
                        <flux:icon :name="$field['icon']" variant="mini" class="text-zinc-400" />
                    @endif
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white">
                        {{ $field['title'] }}
                    </h3>
                </div>
                @if($field['description'])
                    <p class="text-sm text-zinc-500 mt-2">{{ $field['description'] }}</p>
                @endif
            </div>

        @elseif(($field['type'] ?? '') === 'hidden')
            <x-core::data.form.dynamic-field :field="$field" />
            
        @else
            <div class="col-span-12 md:col-span-{{ $field['colSpan'] ?? 12 }}">
                <x-core::data.form.dynamic-field :field="$field" />
            </div>
        @endif

    @endforeach
</div>