<?php

namespace App\Core\Framework\Support\Data\View\Traits\Layouts;

use Flux\Flux;
use App\Core\Framework\Support\Data\View\Services\LayoutDiscoveryService;

trait HasKanbanView
{
    public array $formState = [];

    // Drag & Drop : Mise à jour du statut
    public function updateItemStatus($id, $newStatus) 
    {
        $config = LayoutDiscoveryService::getKanbanConfig($this->getDataClass($this->context));
        
        if (array_key_exists($newStatus, $config['options'])) 
        {
            $this->handleAction('set', [
                'id' => $id,
                $config['field'] => $newStatus,
                '_action' => 'updateItemStatus'
            ]);
        }
    }
}