<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use Flux\Flux;
use Livewire\Attributes\Computed;

trait HasDetailView
{
    public $mode = 'slideover';
    public ?string $activeItemId = null;

    // Récupération de l'item via l'Action Find
    #[Computed]
    public function activeItem() {
        if (!$this->activeItemId) return null;
        return app($this->getShowAction())->execute($this->activeItemId);
    }

    public function showItem($id) {
        $this->activeItemId = $id;
        Flux::modal('item-detail')->show();
    }
}