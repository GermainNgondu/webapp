@props(['item', 'schema'])

<div class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
    {{-- Header du Détail --}}
    <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
        <div class="flex items-center gap-4">
            <flux:heading size="xl">{{ $item->title ?? 'Détails' }}</flux:heading>
        </div>
    </div>


    <div class="grid grid-cols-12 gap-x-6 gap-y-4">

        @foreach($schema as $section => $fields)
            @if ($section)
                <div class="col-span-12 mt-6 mb-2 border-b border-zinc-100 dark:border-zinc-800 pb-2">
                    <flux:heading size="lg" class="mb-4 border-b pb-2">{{ $section }}</flux:heading>
                </div>                
            @endif

            @foreach($fields as $field)
                <div class="col-span-12 md:col-span-{{ $field['colSpan'] ?? 12 }}">

                    <div @class([
                        'inline-flex gap-3 items-center' => $field['inline'] ?? false,
                    ])>
                        <dt class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ $field['label'] }}</dt>
                        <dd @class([
                            'text-sm text-zinc-900',
                            'mt-1' => !($field['inline']),
                        ])>
                            @if($field['component'])
                                <x-dynamic-component :component="$field['component']" :item="$item" :value="$item->{$field['field']}" />
                            @else
                                {{ $item->{$field['field']} ?? '-' }}
                            @endif
                        </dd>
                    </div>
                </div> 
            @endforeach

        @endforeach
    </div>
</div>