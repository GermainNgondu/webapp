@isset($close)

    <div class="flex items-center gap-3">

        {{$close}}
        
        <flux:modal.close>
                
            <flux:button class="capitalize cursor-pointer" variant="ghost">
                {{__('cancel')}}
            </flux:button>
                
        </flux:modal.close>

    </div>

@endif