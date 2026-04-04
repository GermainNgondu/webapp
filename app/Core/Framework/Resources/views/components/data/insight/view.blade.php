@props([
    'insight' => [],
    'widgets' => [],
    'sortable' => true,
])
<div>
    @if(count($widgets) < 1)
        <div class="flex flex-col items-center justify-center py-20 text-zinc-400">
            <flux:icon icon="inbox" variant="outline" class="mb-4 size-12 opacity-20" />
            <flux:text>Aucun widget trouvé</flux:text>
        </div>
    @else
        <div 
            x-data="{
                init() {
                    if (@js($sortable)) {
                        new Sortable($el, {
                            group: 'insights',
                            animation: 150,
                            ghostClass: 'opacity-50',
                            handle: '[data-sort-handle]',
                            onEnd: (evt) => {
                                let order = Array.from(evt.to.children).map(el => el.getAttribute('data-id'));
                                $wire.updateWidgetOrder(order);
                            }
                        });
                    }
                }
            }"
            class="grid grid-cols-12 gap-x-6 gap-y-4"
        >
            @foreach($widgets as $widget)
                <div 
                    data-id="{{ $widget['id'] ?? $widget['property'] }}"

                    class="col-span-12 md:col-span-{{ $widget['config']['colSpan'] ?? 12 }}"
                >
                    @livewire('insight.widget', ['widget' => $widget], key($widget['property']))
                </div>
            @endforeach
        </div>
    @endif  
</div>
