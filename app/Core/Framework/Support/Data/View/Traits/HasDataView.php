<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;
use App\Core\Framework\Support\Data\View\Traits\Shared\HasDataViewCommon;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

trait HasDataView
{
    use HasDataViewCommon;

    /**
     * Ces méthodes doivent être définies dans le composant qui utilise le trait
     */
    abstract protected function getDataClass(): string;
    abstract protected function getActionClass(): string;

    /**
     * Initialisation automatique par Livewire
     */
    public function mountHasDataView(): void
    {
        $this->schema = LayoutDiscovery::getSchema($this->getDataClass());
        $this->filterSchema = LayoutDiscovery::getFilters($this->getDataClass());

        $actions = LayoutDiscovery::getActions($this->getDataClass());

        $this->globalActions = array_filter($actions, fn($a) => $a['isGlobal']);
        $this->rowActions = array_filter($actions, fn($a) => !$a['isGlobal']);
        
        if (empty($this->sort)) {
            $this->sort = LayoutDiscovery::getDefaultSort($this->getDataClass());
        }
    }

    public function getItemsActionClass(): string
    {
        return $this->getActionClass();
    }

    
    // Récupération du schéma de détail via le Service
    #[Computed]
    public function detailSchema(): array {
        return LayoutDiscovery::getDetailSchema($this->getDataClass());
    }

    /**
     * On définit l'action à utiliser pour le détail.
     */
    protected function getShowAction(): string
    {
        $path =  Str::beforeLast($this->getDataClass(), 'Domain');
        $name = Str::beforeLast(Str::afterLast($path, 'Features\\'), '\\');
        $action = str_replace($this->getDataClass(), $path.'Actions\Find'.$name.'Action', $this->getDataClass());
        return str_replace('Get', 'Find', $action);
    }
}