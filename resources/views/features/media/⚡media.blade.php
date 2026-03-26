<?php

use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Features\Media\Actions\GetMediaAction;
use Livewire\Attributes\{On, Layout,Lazy,Title};
use App\Core\Framework\Support\DataView\Traits\HasDataView;
use App\Features\Media\Domain\Data\MediaData;


new #[Lazy,Title('Médias'),Layout('admin::layouts.admin')] class extends Component
{
    use WithFileUploads;
    use HasDataView;

    // Configuration requise par le Trait
    protected function getDataClass(): string { return MediaData::class; }
    protected function getActionClass(): string { return GetMediaAction::class; }

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

    /**
     * Action de ligne 'delete'
     */
    public function delete($id): void
    {

    }

    #[On('media-imported')]
    public function refresh():void
    {

    }

    public function render()
    {
        return $this->view( [
            'items' => $this->getRowsProperty()
        ]);
    }
};
?>
@placeholder
    <div class="flex items-center justify-center min-h-screen">
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

    <x-core::dataview.view :$view :$items :$schema :available-views="['grid', 'table']"/>
    <livewire:features::media.uploader />
</div>