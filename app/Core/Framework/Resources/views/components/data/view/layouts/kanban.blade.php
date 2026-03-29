@php    
    $items = $this->items;
    $actions = $this->getRowActions;
    use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;
    // Récupération dynamique de la configuration Kanban depuis la Resource
    $kanbanConfig = LayoutDiscovery::getKanbanConfig($this->getDataClass('list'));
    
    $groups = $kanbanConfig['options'] ?? [];
    $groupField = $kanbanConfig['field'];
    $labelField = $kanbanConfig['label'] ?? 'title'; // Fallback sur title
    $dateField = $calendarConfig['start'] ?? 'started_at'; // Pour le badge date
    
    // Groupement des items par le champ défini (ex: status)
    $groupedItems = $items->groupBy($groupField);

@endphp

<div wire:key="view-kanban-{{ md5(serialize($items->pluck('id'))) }}" 
    class="flex overflow-x-auto pb-6 gap-6 items-start scrollbar-hide px-2">
    @foreach($groups as $statusValue => $statusLabel)
        <div 
            class="shrink-0 w-80 flex flex-col rounded-2xl bg-zinc-50/50 border border-zinc-100 p-4 min-h-[500px] dark:border-zinc-800 dark:bg-white/10"
            x-data="{ 
                init() {
                    new Sortable($refs.list, {
                        group: 'kanban-shared',
                        animation: 200,
                        ghostClass: 'opacity-40',
                        dragClass: 'rotate-2',
                        onEnd: (evt) => {
                            if (evt.from !== evt.to) {
                                // Appel à la méthode updateItemStatus du Trait HasDataView
                                $wire.updateItemStatus(evt.item.dataset.id, evt.to.dataset.status);
                            }
                        }
                    });
                } 
            }"
        >
            {{-- Header de la colonne --}}
            <div class="flex items-center justify-between mb-5 px-1">
                <div class="flex items-center gap-2">
                    <flux:heading size="sm" class="font-bold uppercase tracking-widest">
                        {{ $statusLabel }}
                    </flux:heading>
                    
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-zinc-200/50">
                        {{ $groupedItems->get($statusValue)?->count() ?? 0 }}
                    </span>
                    <flux:button 
                        wire:click="handleAction('quick','{{ $statusValue }}')" 
                        variant="subtle" 
                        icon="plus" 
                        size="sm" 
                        :loading="false"
                        class="cursor-pointer"
                     />                    
                </div>

            </div>

            {{-- Zone de Drop (Liste des cartes) --}}
            <div x-ref="list" data-status="{{ $statusValue }}" class="max-h-[calc(100vh-150px)] flex-1 space-y-3 min-h-[150px] overflow-auto">
                @foreach($groupedItems->get($statusValue) ?? [] as $item)
                    <div 
                        data-id="{{ $item->id }}" 
                        wire:click="showItem('{{ $item->id }}')"
                        class="group bg-white border border-zinc-200 p-4 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 
                            cursor-grab active:cursor-grabbing transition-all  dark:border-white/20  dark:bg-white/20"
                    >
                        <div class="flex flex-col gap-3">
                            {{-- ID & Badge de priorité (si existant) --}}
                            <div class="flex justify-between items-center">
                                @if(isset($item->priority))
                                    <span @class([
                                        'text-[9px] px-1.5 py-0.5 rounded-md font-bold uppercase',
                                        'bg-red-50 text-red-600' => $item->priority === 'high',
                                        'bg-blue-50 text-blue-600' => $item->priority === 'medium',
                                        'bg-zinc-50 text-zinc-500' => $item->priority === 'low',
                                    ])>
                                        {{ $item->priority }}
                                    </span>
                                @endif
                                <x-core::data.view.actions.row :actions="$actions" :item="$item" :grid="true"/>
                            </div>

                            {{-- Titre dynamique --}}
                            <p class="text-sm font-semibold leading-snug text-zinc-800 dark:text-white/70 transition-colors">
                                {{ $item->{$labelField} ?? $item->file_name ?? 'Sans titre' }}
                            </p>

                            {{-- Footer de la carte : Date Badge --}}
                            <div class="flex items-center justify-between mt-1">
                                @if(isset($item->{$dateField}))
                                    <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-zinc-50 border border-zinc-100 text-zinc-500">
                                        <flux:icon name="calendar" class="size-3 text-zinc-400" />
                                        <span class="text-[10px] font-medium">
                                            {{ \Carbon\Carbon::parse($item->{$dateField})->translatedFormat('d M') }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Avatar de l'utilisateur (si assigné) --}}
                                @if(isset($item->user_id))
                                    <div class="size-6 rounded-full bg-zinc-100 border border-white flex items-center justify-center text-[10px] font-bold text-zinc-400 shadow-sm" title="Assigné">
                                        {{ strtoupper(substr($item->user_id, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>
    @endforeach

    <x-core::data.view.partials.quick-create-modal />
</div>