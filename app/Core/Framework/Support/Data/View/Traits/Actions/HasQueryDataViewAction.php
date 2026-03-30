<?php

namespace App\Core\Framework\Support\Data\View\Traits\Actions;

use ReflectionClass;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Core\Framework\Support\Data\View\Services\LayoutDiscoveryService;
use App\Core\Framework\Support\Data\View\Attributes\{Filter, Column};

trait HasQueryDataViewAction
{
    abstract protected function getModel(): string;
    public function execute(array $params = [])
    {
        $request = new Request([
            'filter' => $params['filters'] ?? [],
            'sort'   => $params['sort'] ?? null,
        ]);

        $defaultSort = $this->getDefaultSort();
        $allowedSorts = $this->discoverSorts();

        if ($defaultSort) 
        {
            $cleanDefault = ltrim($defaultSort, '-');
            
            if (!in_array($cleanDefault, $allowedSorts)) 
            {
                $allowedSorts[] = $cleanDefault;
            }
        }

        $query = QueryBuilder::for($this->getModel(), $request)
            ->allowedSorts($allowedSorts)
            ->defaultSort($defaultSort ?? [])
            ->allowedFilters($this->discoverFilters());

        $paginator = $query->paginate($params['per_page'] ?? 15);

        return ($this->getDataClass())::collect($paginator);
    }
    
    protected function getDefaultSort(): ?string
    {
        return LayoutDiscoveryService::getDefaultSort($this->getDataClass());
    }
    
    protected function discoverFilters(): array
    {
        $reflection = new ReflectionClass($this->getDataClass());
        $allowed = [];

        foreach ($reflection->getProperties() as $property) 
        {
            $filterAttr = $property->getAttributes(Filter::class)[0] ?? null;
            
            if ($filterAttr) 
            {
                $instance = $filterAttr->newInstance();
                $name = $property->getName();

                // Mapping intelligent selon le type de filtre
                $allowed[] = match ($instance->type) {
                    'text'   => AllowedFilter::partial($name),
                    'select' => AllowedFilter::exact($name),
                    'date'   => AllowedFilter::callback($name, fn($q, $v) => $q->where($name, '>=', $v)),
                    default  => $name,
                };
            }
        }

        $allowed[] = AllowedFilter::callback('global', function ($query, $value) use ($reflection) {
                $query->where(function ($q) use ($value, $reflection) {
                        foreach ($reflection->getProperties() as $property) 
                        {
                            $columnAttr = $property->getAttributes(Column::class)[0] ?? null;
                            if ($columnAttr && $columnAttr->newInstance()->searchable) 
                            {
                                $q->orWhere($property->getName(), 'LIKE', "%{$value}%");
                            }
                        }
                    });
        });

        return $allowed;
    }

    protected function discoverSorts(): array
    {
        $reflection = new ReflectionClass($this->getDataClass());
        $sorts = [];

        foreach ($reflection->getProperties() as $property) 
        {
            $colAttr = $property->getAttributes(Column::class)[0] ?? null;
            if ($colAttr && $colAttr->newInstance()->sortable) 
            {
                $sorts[] = $property->getName();
            }
        }
        return $sorts;
    }
}