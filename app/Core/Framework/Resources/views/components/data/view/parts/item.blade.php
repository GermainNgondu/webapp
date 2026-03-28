@props(['item', 'schema'])

<div class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-300">
    {{-- Header du Détail --}}
    <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
        <div class="flex items-center gap-4">
            <flux:heading size="xl">{{ $item->file_name ?? 'Détails de l\'élément' }}</flux:heading>
        </div>
    </div>

    {{-- Contenu par Sections --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Colonne principale --}}
        <div class="md:col-span-2 space-y-6">
            @foreach($schema as $section => $fields)
                <flux:card>
                    <flux:heading size="lg" class="mb-4 border-b pb-2">{{ $section }}</flux:heading>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        @foreach($fields as $field)
                            <div>
                                <dt class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ $field['label'] }}</dt>
                                <dd class="mt-1 text-sm text-zinc-900">
                                    @if($field['component'])
                                        <x-dynamic-component :component="$field['component']" :value="$item->{$field['field']}" />
                                    @else
                                        {{ $item->{$field['field']} ?? '-' }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </flux:card>
            @endforeach
        </div>

        <div class="space-y-6">

             <flux:card class="p-0 overflow-hidden">
                <div class="aspect-square bg-zinc-100 flex items-center justify-center">
                    @if(str_contains($item->mime_type ?? '', 'image'))
                        <img src="{{ $item->url }}" class="w-full h-full object-contain">
                    @else
                        <flux:icon name="document" class="size-20 text-zinc-300" />
                    @endif
                </div>
             </flux:card>
        </div>
    </div>
</div>