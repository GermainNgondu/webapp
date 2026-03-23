<?php

namespace App\Core\Framework\Support\DataView\Traits;

use App\Core\Framework\Support\DataView\Services\LayoutDiscovery;
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

    #[Url(history: true)]
    public string $view = 'table';
    #[Url(history: true)]
    public int $perPage = 10;
    public array $schema = [];

    public array $filterSchema = [];
    public array $globalActions = [];
    public array $rowActions = [];


    /**
     * Ces méthodes doivent être définies dans le composant qui utilise le trait
     */
    abstract protected function getDataClass(): string;
    abstract protected function getActionClass(): string;

    /**
     * Initialisation automatique par Livewire
     */
    public function mountHasDataView()
    {
        $this->schema = LayoutDiscovery::getSchema($this->getDataClass());
        $this->filterSchema = LayoutDiscovery::getFilters($this->getDataClass());
        $actions = LayoutDiscovery::getActions($this->getDataClass());
        $this->globalActions = array_filter($actions, fn($a) => $a['isGlobal']);
        $this->rowActions = array_filter($actions, fn($a) => !$a['isGlobal']);
    }
    /**
     * On réinitialise la page quand on cherche ou qu'on filtre
     */
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilters() { $this->resetPage(); }
    /**
     * Récupération des données via l'Action
     */
    public function getRowsProperty()
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
    public function sortBy(string $field)
    {
        if ($this->sort === $field) {
            $this->sort = "-{$field}"; // Switch vers DESC
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
    public function handleAction(string $actionName, $id = null)
    {
        if (method_exists($this, $actionName)) {
            $this->{$actionName}($id);
        } else {
            // Optionnel : Dispatcher une erreur ou un log si l'action n'est pas codée
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
            'row'    => array_values(array_filter($allActions, fn($a) => !($a['isGlobal'] ?? false))),
        ];
    }
}