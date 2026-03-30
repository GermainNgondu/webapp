<?php

namespace App\Core\Framework\Support\Data\View\Traits\Layouts;

use Flux\Flux;
use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasKanbanView
{
    public array $formState = [];

    // Drag & Drop : Mise à jour du statut
    public function updateItemStatus($id, $newStatus) {
        $config = LayoutDiscovery::getKanbanConfig($this->getDataClass($this->context));
        $model = ($this->getModel())::findOrFail($id);
        
        if (array_key_exists($newStatus, $config['options'])) {
            $model->update([$config['field'] => $newStatus]);
            $this->dispatch('notify', message: "Statut mis à jour.");
        }
    }
}