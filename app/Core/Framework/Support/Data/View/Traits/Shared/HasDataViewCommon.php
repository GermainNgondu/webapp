<?php

namespace App\Core\Framework\Support\Data\View\Traits\Shared;

use Flux\Flux;
use Livewire\Attributes\{Url,Computed};
use Livewire\{WithPagination,WithFileUploads};
use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasDataViewCommon
{
    use WithPagination,WithFileUploads;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?string $sort = null;

    #[Url(history: true)]
    public array $filters = [];

    #[Url()]
    public string $view;

    public int $perPage = 25;
    public array $selected = [];
    public string $context = 'list';
    public $isCreating = false;
    public array $formState = [];
    public $mode = 'slideover';
    public ?string $activeItemId = null;

    /**
     * Initialisation automatique par Livewire
     */
    public function mountHasResource(): void
    {
        if (empty($this->sort)) {
            $this->sort = LayoutDiscovery::getDefaultSort($this->getListDataClass());
        }
    }

    // Récupération de l'item via l'Action Find
    #[Computed]
    public function activeItem() {
        if (!$this->activeItemId) return null;
        return app($this->getShowAction())->execute($this->activeItemId);
    }

    // Ouvre le slideover avec l'item
    public function showItem($id) {
        $this->activeItemId = $id;
        Flux::modal('item-detail')->show();
    }

    /**
     * Récupération des filtres
     */
    #[Computed]
    public function getAllFilters(): array
    {
        return LayoutDiscovery::getFilters($this->getDataClass($this->context));
    }

    /**
     * Récupération des actions globales
     */
    #[Computed]
    public function getGlobalActions(): array
    {
        return $this->actions()['global'];
    }
    /**
     * Récupération des actions row
     */
    #[Computed]
    public function getRowActions(): array
    {
        return $this->actions()['row'];
    }
    /**
     * Récupération des actions bulk
     */
    #[Computed]
    public function getBulkActions(): array
    {
        return $this->actions()['bulk'];
    }

    #[Computed]
    public function schema(): array 
    {
        return LayoutDiscovery::resolve($this->getDataClass($this->context));
    }
    
    // Récupération du schéma de détail via le Service
    #[Computed]
    public function detailSchema(): array {
        return LayoutDiscovery::getDetailSchema($this->getDataClass($this->context));
    }

    /**
     * Récupération des données via l'Action
     */
     #[Computed]
    public function items(): mixed
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

        return app($this->getItemsActionClass())->execute([
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
     * Récupération de toutes les actions
     */
    public function actions(): array
    {
        // On récupère toutes les actions via le service
        $allActions = LayoutDiscovery::getActions($this->getDataClass($this->context));

        // On garantit que les clés 'global' et 'row' existent TOUJOURS
        return [
            'global' => array_values(array_filter($allActions, fn($a) => ($a['isGlobal'] ?? false))),
            'row'    => array_values(array_filter($allActions, fn($a) => !$a['isGlobal'] && !$a['isBulk'])),
            'bulk'   => array_values(array_filter($allActions, fn($a) => $a['isBulk'])),
        ];
    }

    /**
     * Routeur d'actions : redirige vers les méthodes du composant
    */
    public function handleAction(string $actionName,mixed $data = null): void
    {
        if (method_exists($this, $actionName)) {
            $this->{$actionName}($data);
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

    /**
     * On réinitialise la page quand on cherche ou qu'on filtre
     */
    public function updatedSearch(): void { $this->selected = []; $this->resetPage(); }
    public function updatedFilters(): void { $this->selected = []; $this->resetPage(); }

    /**
     * @return string
     */
    public function paginationView(): string
    {
        return 'components.pagination.pagination';
    }
}