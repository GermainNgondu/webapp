<div class="max-w-3xl mx-auto py-12">

    <form wire:submit="save">
        <x-dataform.tabs :tabs="$this->tabs()" :form="$this->form" />
        <br>
        @php
            // On vérifie s'il existe au moins un champ modifiable dans tous les onglets
            $canSave = collect($this->tabs())->pluck('fields')->flatten(1)->contains('readonly', false);
        @endphp

        @if($canSave)
            <flux:button type="submit" variant="primary" class="cursor-pointer">Enregistrer</flux:button>
        @endif
    </form>
</div>