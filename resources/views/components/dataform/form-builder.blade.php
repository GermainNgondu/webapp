@props(['builder','form','layout' => null])
<div @keydown.window.prevent.ctrl.s="$wire.save()" @keydown.window.prevent.cmd.s="$wire.save()">
    <form  wire:submit.prevent="save" {{ $attributes }}>

        @if($layout === 'accordion')
            <x-dataform.layouts.accordion :sections="$builder" />
        @elseif($layout === 'tabs')
            <x-dataform.layouts.tabs :tabs="$builder" />
        @elseif($layout === 'simple')
            <x-dataform.layouts.simple :fields="$builder" />
        @elseif($layout === 'wizard')
            <x-dataform.layouts.wizard :steps="$builder" />
        @endif

        @if($layout && $layout !== 'wizard')
            <x-dataform.render.footer target="save" />
        @endif

    </form>
</div>
