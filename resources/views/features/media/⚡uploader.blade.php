<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Lazy;
use App\Features\Media\Models\MediaLibrary;
use App\Features\Media\Actions\{UploadMedia, UploadMediaFromUrl, CleanTemporaryFilesAction};

new #[Lazy] class extends Component
{
    use WithFileUploads;

    public MediaLibrary $library;
    
    // Source Locale
    public $uploads = [];

    public $meta = [];
    
    // Source URL
    public $url = '';

    public bool $browser;

    public function mount(bool $browser =  false)
    {
        $this->browser = $browser;
        // On récupère ou crée la bibliothèque par défaut
        $this->library = MediaLibrary::firstOrCreate(['name' => 'all','slug'=> 'all']);
    }

    public function uploadLocal()
    {
        $this->validate(['uploads.*' => 'required|file|max:20480']);

        foreach ($this->uploads as $file) {
            $media = UploadMedia::run($this->library, $file);
            // On récupère les meta saisies par l'utilisateur
            $fileMeta = $this->meta[$file->getClientOriginalName()] ?? [];
            if (!empty($fileMeta)) {
                $media->setCustomProperty('alt', $fileMeta['alt'] ?? '');
                $media->save();
            }
        }

        $this->finishImport();
    }

    public function uploadUrl()
    {
        $this->validate(['url' => 'required|url']);

        UploadMediaFromUrl::run($this->library, $this->url);

        $this->url = '';
        $this->finishImport();
    }

    protected function finishImport()
    {
        $this->uploads = [];
        CleanTemporaryFilesAction::run();
        $this->dispatch('media-imported'); // Rafraîchit le browser
        $this->dispatch('notify', message: 'Importation réussie');
        $this->modal('media-uploader-modal')->close();
    }

    public function suppr($filename)
    {
        foreach ($this->uploads as $index => $file) {
            if ($file->getFilename() === $filename) {
                unset($this->uploads[$index]);
                break;
            }
        }
        $this->uploads = array_values($this->uploads);
    }
};
?>

@placeholder
    <div >
        <flux:icon.loading />
    </div>
@endplaceholder

<div>
    @if ($browser)
        @include('admin.media.partials.uploader-content')
    @else
        <flux:modal name="media-uploader-modal" class="md:w-6xl p-0 overflow-hidden">
            @include('features.media.partials.uploader-content')
        </flux:modal>
    @endif
</div>