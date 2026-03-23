<?php

namespace App\Core\Framework\Support\DataView\Contracts;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use ReflectionClass;
use App\Core\Framework\Support\DataView\Attributes\{Filter,Column};

abstract class BaseDataViewAction
{
    // Doivent être définis dans l'action finale
    abstract protected function getModel(): string;
    abstract protected function getDataClass(): string;

    public function execute(array $params = [])
    {
        $request = new Request([
            'filter' => $params['filters'] ?? [],
            'sort'   => $params['sort'] ?? null,
        ]);

        $query = QueryBuilder::for($this->getModel(), $request);

        // 1. Auto-découverte des Filtres
        $query->allowedFilters($this->discoverFilters());

        // 2. Auto-découverte des Tris
        $query->allowedSorts($this->discoverSorts());

        // 3. Exécution et Transformation en Data Collection
        $results = $query->paginate($params['per_page'] ?? 15);

        return ($this->getDataClass())::collect($results);
    }

    protected function discoverFilters(): array
    {
        $reflection = new ReflectionClass($this->getDataClass());
        $allowed = [];

        foreach ($reflection->getProperties() as $property) {
            $filterAttr = $property->getAttributes(Filter::class)[0] ?? null;
            
            if ($filterAttr) {
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
                        foreach ($reflection->getProperties() as $property) {
                            $columnAttr = $property->getAttributes(Column::class)[0] ?? null;
                            if ($columnAttr && $columnAttr->newInstance()->searchable) {
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

        foreach ($reflection->getProperties() as $property) {
            $colAttr = $property->getAttributes(Column::class)[0] ?? null;
            if ($colAttr && $colAttr->newInstance()->sortable) {
                $sorts[] = $property->getName();
            }
        }
        return $sorts;
    }
}