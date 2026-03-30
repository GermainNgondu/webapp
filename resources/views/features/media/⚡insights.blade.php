<?php

use Livewire\Component;
use Livewire\Attributes\{Layout,Lazy,Title};
use App\Core\Framework\Support\Data\Insight\Services\InsightDiscoveryService;
use App\Features\Media\Domain\Data\MediaInsightData;
use Illuminate\Support\Facades\Auth;

new #[Lazy,Title('Médias Insights'),Layout('admin::layouts.admin')] class extends Component
{
    public array $widgets = [];

    public function mount()
    {
        $this->loadDashboard();
    }

    /**
     * Charge la configuration et les données du dashboard
     */
    public function loadDashboard()
    {
        // 1. Découverte de la structure via les attributs du DTO
        $discoveredWidgets = InsightDiscoveryService::discover(MediaInsightData::class);

        // 3. Tri des widgets
        $this->widgets = $this->sortWidgets($discoveredWidgets, []);

    }

    /**
     * Gère le changement d'ordre via Sortable.js
     */
    public function updateWidgetOrder(array $newOrder)
    {
        // Sauvegarde persistante
        Auth::user()->setSetting('media_dashboard_order', $newOrder);

        // Mise à jour de l'état local pour le rendu
        $this->widgets = collect($newOrder)
            ->map(fn($prop) => collect($this->widgets)->firstWhere('property', $prop))
            ->filter()
            ->toArray();

        $this->dispatch('notify', message: 'Mise en page sauvegardée', variant: 'success');
    }

    protected function sortWidgets(array $widgets, ?array $order): array
    {
        if (!$order) return $widgets;

        return collect($order)
            ->map(fn($prop) => collect($widgets)->firstWhere('property', $prop))
            ->filter()
            ->toArray();
    }
};
?>
@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder
<div>
    <x-core::data.insight.view :widgets="$this->widgets"/>
</div>