<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasKanbanView
{
    public array $formState = [];

    // Drag & Drop : Mise à jour du statut
    public function updateItemStatus($id, $newStatus) {
        $config = LayoutDiscovery::getKanbanConfig($this->resource::listData());
        $model = ($this->getModel())::findOrFail($id);
        
        if (array_key_exists($newStatus, $config['options'])) {
            $model->update([$config['field'] => $newStatus]);
            $this->dispatch('notify', "Statut mis à jour.");
        }
    }

    // Quick Add : Initialisation
    public function quickCreate($status) {
        $config = LayoutDiscovery::getKanbanConfig($this->resource::listData());
        $this->formState = [$config['field'] => $status];
        $this->js("\$flux.modal('quick-create-modal').show()");
    }

    public function saveQuickItem() {
        app($this->resource::getFormAction())->execute($this->formState);
        $this->formState = [];
        $this->js("\$flux.modal('quick-create-modal').close()");
    }
}