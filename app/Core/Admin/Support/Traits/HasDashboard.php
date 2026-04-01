<?php

namespace App\Core\Admin\Support\Traits;

use Livewire\Attributes\{Computed, Url};

trait HasDashboard
{
    public string $dataClass;

    #[Url(as: 'id')]
    public ?string $id = null;

    #[Computed]
    public function insight()
    {
        $query = auth()->user()->insights();

        if($this->id) 
        {
            return $query->where('uuid', $this->id)->first();
        }

        return $query->where('is_primary', true)->first();
    }

    #[Computed]
    public function insights()
    {
        return auth()->user()->insights()->orderBy('is_primary', 'desc')->orderBy('name')->get();
    }

    public function changeInsight(string|int $id): void
    {
        $this->id = $id;
    }

    public function setPrimary($id): void
    {
        auth()->user()->insights()->update(['is_primary' => false]);
        
        auth()->user()->insights()->where('id', $id)->update(['is_primary' => true]);

        $this->dispatch('notify', message: 'Tableau de bord favori mis à jour', variant: 'success');
    }

    public function deleteInsight($id): void
    {
        auth()->user()->insights()->where('id', $id)->delete();
        $this->dispatch('notify', message: 'Tableau de bord supprimé');
    }
}