<?php

namespace App\Core\Framework\Support\Data\Form\Traits;

use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasFormMedia
{
    /**
     * Écoute l'événement envoyé par la Modal de sélection
     */
    #[On('media-selected')]
    public function updateMediaProperty($property, $id)
    {
        if (property_exists($this, explode('.', $property)[0])) {
            data_set($this, $property, $id);
            $this->dispatch('notify', message: 'Média sélectionné');
        }
    }

    /**
     * Supprime un média via Spatie (Appelé par le bouton Trash en JS)
     */
    public function deleteMedia($mediaId, $fieldName = null)
    {
        $media = Media::find($mediaId);
        if ($media) {
            $media->delete();
            if (method_exists($this, 'notification')) {
                $this->notification()->success('Fichier supprimé');
            } else {
                $this->dispatch('notify', message: 'Fichier supprimé');
            }
        }
    }

    /**
     * Utilitaire pour synchroniser les médias après le save()
     */
    protected function syncMedia($model, $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof TemporaryUploadedFile) {
                $model->addMedia($value->getRealPath())
                      ->usingFileName($value->getClientOriginalName())
                      ->toMediaCollection($key);
            } elseif (is_array($value)) {
                foreach ($value as $file) {
                    if ($file instanceof TemporaryUploadedFile) {
                        $model->addMedia($file->getRealPath())->toMediaCollection($key);
                    }
                }
            }
        }
    }
}
