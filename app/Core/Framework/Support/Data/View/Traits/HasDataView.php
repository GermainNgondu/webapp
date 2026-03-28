<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;
use Livewire\WithPagination;
use Livewire\Attributes\{Url,Computed};

trait HasDataView
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $sort = null;

    #[Url(history: true)]
    public array $filters = [];

    #[Url()]
    public string $view = 'table';

    public int $perPage = 25;
    public array $schema = [];

    public array $filterSchema = [];
    public array $globalActions = [];
    public array $rowActions = [];
    public array $selected = [];


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
    /**
     * On réinitialise la page quand on cherche ou qu'on filtre
     */
    public function updatedSearch(): void { $this->selected = []; $this->resetPage(); }
    public function updatedFilters(): void { $this->selected = []; $this->resetPage(); }
    /**
     * Récupération des données via l'Action
     */
    public function getRowsProperty(): mixed
    {
        $searchPayload = [];
        $searchTerm = trim($this->search);

        // On ne déclenche la recherche que si on a 0 ou >= 3 caractères
        if (strlen($searchTerm) >= 2) {
            $searchPayload['global'] = $searchTerm;
        } elseif (strlen($searchTerm) === 0) {
            // Permet de réinitialiser la liste quand on efface tout
            $searchPayload['global'] = null;
        }

        $allFilters = array_merge($this->filters, $searchPayload);

        return app($this->getActionClass())->execute([
            'filters' => array_filter($allFilters, fn($value) => $value !== null && $value !== ''),
            'sort'    => $this->sort,
            'per_page' => $this->perPage,
        ]);
    }
    /**
     * Helper pour changer le tri depuis la vue (utilisé par Flux Table)
     */
    public function sortBy(string $field): void
    {
        if ($this->sort === $field) {
            $this->sort = "-{$field}"; // DESC
        } elseif ($this->sort === "-{$field}") {
            $this->sort = null; // Annule le tri
        } else {
            $this->sort = $field; // Définit ASC
        }
    }
    /**
     * Mapping des icônes pour le switcher Flux UI
     */
    public function getIconForView(string $view): string
    {
        return match ($view) {
            'table'  => 'table',
            'grid'   => 'layout-grid',
            'kanban' => 'columns-3',
            'map'   => 'map',
            'calendar'=> 'calendar',
            default  => 'stop',
        };
    }

    /**
     * Routeur d'actions : redirige vers les méthodes du composant
    */
    public function handleAction(string $actionName, $id = null): void
    {
        if (method_exists($this, $actionName)) {
            $this->{$actionName}($id);
        } else {
           
        }
    }
    /**
     * Sélectionner/Désélectionner tout sur la page actuelle
     */
    public function toggleSelectAll($pageIds)
    {
        if (count($this->selected) === count($pageIds)) {
            $this->selected = [];
        } else {
            $this->selected = $pageIds;
        }
    }

    /**
     * L'action ne reçoit plus les IDs en paramètre, 
     * elle utilise directement $this->selected
     */
    public function handleBulkAction(string $method)
    {
        if (empty($this->selected)) return;

        if (method_exists($this, $method)) {
            $this->{$method}($this->selected);
            $this->selected = [];
        }
    }

    #[Computed]
    public function actions(): array
    {
        // On récupère toutes les actions via le service
        $allActions = LayoutDiscovery::getActions($this->getDataClass());

        // On garantit que les clés 'global' et 'row' existent TOUJOURS
        return [
            'global' => array_values(array_filter($allActions, fn($a) => ($a['isGlobal'] ?? false))),
            'row'    => array_values(array_filter($allActions, fn($a) => !$a['isGlobal'] && !$a['isBulk'])),
            'bulk'   => array_values(array_filter($allActions, fn($a) => $a['isBulk'])),
        ];
    }

    /**
     * @return string
     */
    public function paginationView(): string
    {
        return 'components.pagination.pagination';
    }
}