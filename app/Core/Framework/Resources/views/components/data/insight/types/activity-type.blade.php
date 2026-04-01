@props(['data', 'config'])
{{-- Flux d'activité --}}
<div class="flex-1 overflow-hidden">
    @if($data === null)
        {{-- Skeleton --}}
        <div class="space-y-4 animate-pulse">
            @for($i = 0; $i < $config['limit']; $i++)
                <div class="flex gap-3">
                    <div class="h-8 w-8 bg-gray-100 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-gray-100 rounded w-3/4"></div>
                        <div class="h-2 bg-gray-50 rounded w-1/4"></div>
                    </div>
                </div>
            @endfor
        </div>
    @else
        <div class="relative space-y-4">
            {{-- Ligne verticale décorative --}}
            <div class="absolute left-4 top-2 bottom-2 w-px bg-gray-100"></div>

            @foreach($data as $log)
                <div class="relative flex gap-4 items-start">
                    {{-- Icone de l'événement --}}
                    <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-100 shadow-sm">
                        <flux:icon :name="$log['icon']" variant="micro" class="text-{{ $log['color'] }}-500" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-tight">
                            <span class="font-bold">{{ $log['causer_name'] }}</span> 
                            {{ strtolower($log['description']) }}
                                @if($log['subject_type'])
                                    <span class="text-gray-400">({{ $log['subject_type'] }})</span>
                                @endif
                        </p>
                        <span class="text-[10px] text-gray-400 uppercase font-medium">{{ $log['time'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>