<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscoveryService;
use App\Core\Framework\Support\Data\View\Traits\Shared\HasDataViewCommon;

trait HasResource
{
    use HasDataViewCommon;
    
    abstract protected function getResource(): string;

    /**
     * Initialisation automatique par Livewire
     */
    public function mountHasResource(): void
    {
        if (empty($this->sort)) {
            $this->sort = LayoutDiscoveryService::getDefaultSort($this->getListDataClass());
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
            'insight','dashboard' => $this->getInsightDataClass(),
            default => $this->getListDataClass(),
        };
    }

    public function getItemsActionClass(): string
    {
        return $this->getIndexAction();
    }
}