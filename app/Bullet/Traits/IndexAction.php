<?php

namespace App\Bullet\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait IndexAction
{
    use CrudHelpers;

    protected $defaultSorts = null;
    protected $allowedFilters = null;
    protected $allowedIncludes = null;
    protected $allowedSorts = null;
    protected $allowedFields = null;
    protected $allowedAppends = null;
    protected $defaultPerPage = 15;
    protected $maxPerPage = 500;
    protected $searchable = true;

    public function index(Request $request)
    {
        $this->beforeIndex($request);
        $this->authorizeIndex($request);

        $perPage = $request->per_page ?? $request->perPage ?? $this->defaultPerPage;
        $perPage = $perPage <= 0 ? 1 : $perPage;
        $perPage = $perPage > $this->maxPerPage ? $this->maxPerPage : $perPage;

        $query = $this->getIndexQuery($request);
        $query = $this->afterIndex($request, $query);

        $collection = $query->paginate($perPage);

        if ($request->wantsJson()) {
            return $this->getModelResource()::collection($collection);
        }

        return view($this->getModelUrl() . '.index', [
            $this->getModelVariableName() => $this->getModelResource()::collection($collection)
                ->toResponse($request)->getData(true),
        ]);
    }

    protected function beforeIndex(Request $request)
    {
    }

    protected function afterIndex(Request $request, Builder $query): Builder
    {
        return $query;
    }

    protected function getIndexQuery(Request $request): Builder
    {
        $query = $this->getQuery();

        if (class_exists('Spatie\QueryBuilder\QueryBuilder')) {
            $query = \Spatie\QueryBuilder\QueryBuilder::for($query)
                ->defaultSorts($this->defaultSorts())
                ->allowedFilters($this->allowedFilters())
                ->allowedIncludes($this->allowedIncludes())
                ->allowedSorts($this->allowedSorts())
                ->allowedFields($this->allowedFields())
                ->allowedAppends($this->allowedAppends());
        }

        if ($this->searchable) {
            $query->search($request->search);
        }

        return $query;
    }

    protected function defaultSorts()
    {
        return $this->defaultSorts ?? [];
    }

    protected function allowedFilters()
    {
        return $this->allowedFilters ?? [];
    }

    protected function allowedIncludes()
    {
        return $this->allowedIncludes ?? [];
    }

    protected function allowedSorts()
    {
        return $this->allowedSorts ?? $this->getModelColumns();
    }

    protected function allowedFields()
    {
        return $this->allowedFields ?? $this->getModelColumns();
    }

    protected function allowedAppends()
    {
        return $this->allowedAppends ?? [];
    }
}
