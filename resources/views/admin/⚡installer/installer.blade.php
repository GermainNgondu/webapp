<div class="max-w-3xl mx-auto py-2">

    <form wire:submit="save">
        <x-dataform.simple :fields="$this->simple()" />
        <div class="mt-6 flex justify-end">
            <flux:button type="submit" variant="primary" class="cursor-pointer">Enregistrer</flux:button>
        </div>
    </form>
</div>