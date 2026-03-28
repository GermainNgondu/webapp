<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;
use App\Core\Framework\Support\Data\View\Traits\Shared\HasDataViewCommon;
use Livewire\Attributes\{Computed};

trait HasResource
{
    use HasDataViewCommon;
    
    abstract protected function getResource(): string;

    /**
     * Initialisation automatique par Livewire
     */
    public function mountHasResource(): void
    {
        $this->schema = LayoutDiscovery::getSchema($this->getListDataClass());
        $this->filterSchema = LayoutDiscovery::getFilters($this->getListDataClass());

        $actions = LayoutDiscovery::getActions($this->getDataClass($this->context));

        $this->globalActions = array_filter($actions, fn($a) => $a['isGlobal']);
        $this->rowActions = array_filter($actions, fn($a) => !$a['isGlobal']);
        
        if (empty($this->sort)) {
            $this->sort = LayoutDiscovery::getDefaultSort($this->getListDataClass());
        }
    }

    public function getModel(): string {
        return $this->getResource()::model();
    }
    
    public function getListDataClass(): string {
        return $this->getResource()::listData();
    }

    public function getDetailDataClass(): string {
        return $this->getResource()::detailData();
    }
    public function getFormDataClass(): string {
        return $this->getResource()::formData();
    }
    public function getInsightDataClass(): string {
        return $this->getResource()::insightData();
    }
    public function getIndexAction(): string {
        return $this->getResource()::getIndexAction();
    }

    public function getShowAction(): string {
        return $this->getResource()::getShowAction();
    }

    #[Computed]
    public function schema(): array
    {
        return LayoutDiscovery::getSchema($this->getListDataClass());
    }

    #[Computed]
    public function detailSchema(): array
    {
        // Découvre le schéma du Show via le DTO
        return LayoutDiscovery::getDetailSchema($this->getDetailDataClass());
    }

    /**
     * Résout la Data Class en fonction du contexte d'utilisation.
     * Supporte des alias pour plus de flexibilité.
     */
    public function getDataClass(string $context = 'list'): string
    {
        return match (strtolower($context)) {
            'list', 'index', 'table', 'grid', 'kanban', 'map', 'calendar' => $this->getListDataClass(),
            'detail', 'show', 'view' => $this->getDetailDataClass(),
            'form', 'create', 'edit', 'upsert' => $this->getFormDataClass(),
            'insight' => $this->getInsightDataClass(),
            default => $this->getListDataClass(),
        };
    }

    public function getItemsActionClass(): string
    {
        return $this->getIndexAction();
    }
}