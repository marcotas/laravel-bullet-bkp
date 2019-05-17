<?php

namespace App\Bullet\Traits;

use App\Bullet\Exceptions\ModelNotFoundException;
use App\Bullet\Resources\DataResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait CrudHelpers
{
    protected $model;
    protected $modelPolicy;
    protected $modelColumns;
    protected $modelResource;

    protected function getModel()
    {
        $controller = class_basename(get_called_class());
        $model = $this->model ?? Str::singular(str_replace('Controller', '', $controller));

        if (class_exists($model)) {
            return $this->model = $model;
        } elseif (class_exists('App\\Models\\' . $model)) {
            return $this->model = 'App\\Models\\' . $model;
        } elseif (class_exists('App\\' . $model)) {
            return $this->model = 'App\\' . $model;
        } else {
            throw new ModelNotFoundException("Model $model not found for controller $controller");
        }
    }

    protected function getQuery(): Builder
    {
        return $this->getModel()::query();
    }

    protected function getModelUrl()
    {
        return Str::slug(Str::plural(class_basename($this->getModel())));
    }

    protected function getModelVariableName()
    {
        return Str::camel(Str::plural(class_basename($this->getModel())));
    }

    protected function getModelResource()
    {
        if (isset($this->modelResource)) {
            return $this->modelResource ?: DataResource::class;
        }

        $modelResource = class_basename($this->getModel()) . 'Resource';
        $resourceClass = 'App\\Http\\Resources\\' . $modelResource;

        if (class_exists($resourceClass)) {
            return $resourceClass;
        }

        return DataResource::class;
    }

    protected function getModelPolicy()
    {
        dd('policy');
    }

    protected function getModelColumns()
    {
        $modelClass = $this->getModel();
        $model = new $modelClass();

        return $this->modelColumns = $this->modelColumns ?? Schema::getColumnListing($model->getTable());
    }
}
