<flux:modal name="quick-create-modal" class="md:w-[500px]">
    <form wire:submit="saveQuickItem" class="space-y-6">
        <div>
            <flux:heading size="lg">Nouvel élément</flux:heading>
            <flux:subheading>Ajout rapide dans la colonne sélectionnée.</flux:subheading>
        </div>

        <div class="space-y-4">
            {{-- Ici on pourrait boucler sur le formData pour générer les inputs --}}
            <flux:input label="Nom / Titre" wire:model="formState.file_name" placeholder="Ex: Rapport_final.pdf" />
            
            {{-- Le statut est masqué ou grisé car déjà défini --}}
            <flux:input label="Statut" wire:model="formState.status" readonly class="bg-zinc-50" />
            
            <flux:textarea label="Description (optionnel)" wire:model="formState.description" />
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:button variant="ghost" x-on:click="$flux.modal('quick-create-modal').close()">Annuler</flux:button>
            <flux:button type="submit" variant="primary">Créer l'élément</flux:button>
        </div>
    </form>
</flux:modal>