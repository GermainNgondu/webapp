<?php

namespace App\Core\Framework\Support\Data\View\Traits\Layouts;

use Livewire\Attributes\Computed;
use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasMapView
{
    public bool $isLive = false;

    public function toggleLive() {
        $this->isLive = !$this->isLive;
        $this->dispatch('notify', message :$this->isLive ? 'Mode Live activé' : 'Mode Statique');
    }

    #[Computed]
    public function mapMarkers() {
        $config = LayoutDiscovery::getMapConfig($this->getDataClass($this->context));
        
        // On récupère les items et on les mappe pour Leaflet
        return $this->items()->map(fn($item) => [
            'id' => $item->id,
            'lat' => (float) $item->{$config['lat']},
            'lng' => (float) $item->{$config['lng']},
            'label' => $item->{$config['label']},
            'title' => $item->{$config['title']},
            'description' => $item->{$config['description']},
            'status' => $item->{$config['status']},
            'preview' => $item->url ?? null,
        ])->filter(fn($m) => $m['lat'] && $m['lng'])->values()->toArray();
    }
}