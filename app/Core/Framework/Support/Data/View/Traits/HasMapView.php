<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use Livewire\Attributes\Computed;
use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasMapView
{
    public bool $isLive = false;

    public function toggleLive() {
        $this->isLive = !$this->isLive;
        $this->dispatch('notify', $this->isLive ? 'Mode Live activé' : 'Mode Statique');
    }

    #[Computed]
    public function mapMarkers() {
        $config = LayoutDiscovery::getMapConfig($this->resource::listData());
        
        // On récupère les items et on les mappe pour Leaflet
        return $this->items->map(fn($item) => [
            'id' => $item->id,
            'lat' => (float) $item->{$config['lat']},
            'lng' => (float) $item->{$config['lng']},
            'label' => $item->{$config['label']},
            'preview' => $item->url ?? null,
        ])->filter(fn($m) => $m['lat'] && $m['lng'])->values()->toArray();
    }
}