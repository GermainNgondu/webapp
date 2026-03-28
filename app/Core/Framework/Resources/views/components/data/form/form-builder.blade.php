@props(['builder','form','layout' => null, 'full' => false])
<div 
    x-data="{ 
        isDirty: false,
        showLeaveModal: false,
        nextUrl: null,

        init() {
            window.onbeforeunload = (e) => {
                if (this.isDirty) return 'Modifications non enregistrées';
            };
        },
    }"
    @change.window="isDirty = true"
    @form-saved.window="isDirty = false"
    @keydown.window.prevent.ctrl.s="$wire.save()" @keydown.window.prevent.cmd.s="$wire.save()">
    
    <form  wire:submit.prevent="save" {{ $attributes }}>
        @csrf
        @if($layout === 'accordion')
            <x-core::data.form.layouts.accordion :sections="$builder" />
        @elseif($layout === 'tabs')
            <x-core::data.form.layouts.tabs :tabs="$builder" />
        @elseif($layout === 'simple')
            <x-core::data.form.layouts.simple :fields="$builder" />
        @elseif($layout === 'wizard')
            <x-core::data.form.layouts.wizard :steps="$builder" />
        @endif

        @if($layout && $layout !== 'wizard' && !$full)
            <x-core::data.form.render.footer target="save" />
        @endif

    </form>
</div>
