<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use Livewire\Attributes\On;

trait HasDynamicForm
{
    /**
     * Écoute l'événement envoyé par la Modal de sélection
     */
    #[On('media-selected')]
    public function updateMediaProperty($property, $id)
    {
        // On vérifie si la propriété appartient bien à ce formulaire
        // Cela mettra à jour par exemple $this->clientData->logo_id
        if (property_exists($this, explode('.', $property)[0])) {
            data_set($this, $property, $id);
            // Optionnel : notifier l'utilisateur
            $this->dispatch('notify', message: 'Média sélectionné');
        }
    }
    public function searchLazyOptions($model, $labelCol, $valueCol, $search = '', $page = 1)
    {
        $perPage = 20;
        $results = $model::query()
            ->when($search, fn($q) => $q->where($labelCol, 'like', "%{$search}%"))
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->pluck($labelCol, $valueCol)->toArray(),
            'hasMore' => $results->hasMorePages(),
        ];
    }

    public function validateStep(array $fieldNames)
    {
        try {
            $this->validateOnly($fieldNames[0], $this->getRules()); 
            foreach($fieldNames as $field) {
                $this->validateOnly($field);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Supprime un média via Spatie (Appelé par le bouton Trash en JS)
     */
    public function deleteMedia($mediaId, $fieldName)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
        if ($media) {
            $media->delete();
            $this->notification()->success('Fichier supprimé');
        }
    }

    /**
     * Utilitaire pour synchroniser les médias après le save()
     */
    protected function syncMedia($model, $data)
    {
        foreach ($data as $key => $value) {
            // On vérifie si le champ est un fichier uploadé par Livewire
            if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $model->addMedia($value->getRealPath())
                      ->usingFileName($value->getClientOriginalName())
                      ->toMediaCollection($key); // On utilise le nom du champ comme collection par défaut
            } elseif (is_array($value)) {
                // Gestion du multiple
                foreach ($value as $file) {
                    if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        $model->addMedia($file->getRealPath())->toMediaCollection($key);
                    }
                }
            }
        }
    }
}