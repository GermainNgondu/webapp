@props(['tabs'])

<div x-data="{ 
    activeTab: '{{ array_values($tabs)[0]['meta']['slug'] }}'
}" class="w-full">
 
    <nav class="flex gap-6 border-b border-zinc-200 dark:border-zinc-800 mb-8 overflow-x-auto">
        @foreach($tabs as $tab)
            @php 
                $meta = $tab['meta'];
                $errorCount = collect($tab['fields'])
                    ->filter(fn($f) => $errors->has('form.'.$f['name']))
                    ->count();
            @endphp

            <button type="button"
                @click="activeTab = '{{ $meta['slug'] }}'"
                :class="activeTab === '{{ $meta['slug'] }}' ? 'border-zinc-600 text-zinc-600 dark:border-white dark:text-white' : 'border-transparent text-zinc-500 dark:text-zinc-400'"
                class="cursor-pointer relative pb-4 px-2 border-b-2 font-semibold text-sm flex items-center gap-2 transition-all duration-300 outline-none"
            >
                @if($meta['icon'])
                     <flux:icon :name="$meta['icon']" class="w-5 h-5" />
                @endif
                <span>{{ $meta['name'] }}</span>
                
                @if($errorCount > 0)
                    <span class="h-5 w-5 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center">
                        {{ $errorCount }}
                    </span>
                @endif
            </button>
        @endforeach
    </nav>

    <div class="relative min-h-[300px]">
        @foreach($tabs as $tab)
            <div 
                x-show="activeTab === '{{ $tab['meta']['slug'] }}'" 
                x-cloak
                {{-- TRANSITION ALPINE.JS --}}
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 absolute top-0 left-0 w-full"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
            >
                <x-dataform.render.fields :fields="$tab['fields']" />
            </div>
        @endforeach
    </div>
    <div class="mt-10 text-sm flex items-center justify-between p-2 bg-zinc-50 rounded-md">
        {{-- Feedback en bas du bouton --}}
        @if($errors->any())
            <p class="text-red-600 text-sm font-bold">⚠️ Il reste des champs obligatoires non remplis.</p>
        @else
            <p class="text-zinc-500 text-sm">Vérifiez vos informations avant de valider.</p>
        @endif
    </div>
</div>