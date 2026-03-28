<flux:modal name="quick-create-modal" class="md:w-[500px]">
    <form wire:submit="saveQuickItem" class="space-y-6">
        <div>
            <flux:heading size="lg">Nouvel élément</flux:heading>
            <flux:subheading>Remplissez les informations pour créer un nouvel enregistrement.</flux:subheading>
        </div>

        <div class="space-y-4">
           
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:button variant="ghost" x-on:click="$flux.modal('quick-create-modal').close()">Annuler</flux:button>
            <flux:button type="submit" variant="primary">Enregistrer</flux:button>
        </div>
    </form>
</flux:modal>