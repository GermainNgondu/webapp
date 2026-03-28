<?php

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\{On, Layout,Lazy,Title};
use App\Core\Framework\Support\Data\View\Traits\HasResource;
use App\Features\Media\MediaResource;

new #[Lazy,Title('Médias'),Layout('admin::layouts.admin')] class extends Component
{
    use HasResource;

    protected function getResource(): string { return MediaResource::class; }

    public function mount():void
    {
        $this->view = 'grid';
    }

    /**
     * Action déclenchée par le bouton global 'upload'
     */
    public function showModalImport():void
    {
        Flux::modal('media-uploader-modal')->show();
    }

    public function show($id): void
    {
        $this->showItem($id);
    }

    /**
     * Action de ligne 'delete'
     */
    public function delete($id): void
    {

    }

    public function bulkDelete(array $ids): void
    {

    }

    #[On('media-imported')]
    public function refresh():void
    {

    }


};
?>
@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder
<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <flux:heading size="xl" level="1">Bibliothèque Médias</flux:heading>
            <flux:subheading>Gérez vos images, vidéos et documents</flux:subheading>
        </div>
    </div>

    <x-core::data.view :view="$this->view" :items="$this->items()" :schema="$this->schema" :available-views="['grid', 'table']"/>

    <livewire:features::media.uploader />

</div>