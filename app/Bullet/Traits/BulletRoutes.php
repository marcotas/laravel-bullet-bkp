<?php

namespace App\Bullet\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait BulletRoutes
{
    protected $ignoreClasses = [
        \Illuminate\Routing\Controller::class,
        \App\Http\Controllers\Controller::class,
    ];
    protected $namespace;
    protected $httpMethods;

    public function controllers(string $namespace = null)
    {
        $this->namespace = $namespace ?? '';

        $controllers = $this->mapMethods($this->getControllers());

        $this->makeRoutes($controllers);
    }

    private function getControllers(): Collection
    {
        $dirs  = new \IteratorIterator(new \DirectoryIterator(app_path('Http/Controllers/' . $this->namespace)));
        $files = collect();
        foreach ($dirs as $file) {
            if ($file->isDir() || $file->getBasename() === 'Controller.php') {
                continue;
            }
            $files->push(str_replace('.php', '', $file->getBasename()));
        }

        return $files->filter(function ($controller) {
            return class_exists($this->getNamespaced($controller));
        });
    }

    private function getNamespaced($controller)
    {
        $namespace = str_replace('/', '\\', $this->namespace);

        return str_replace('\\\\', '\\', Str::studly('App\\Http\\Controllers\\' . $namespace . '\\' . $controller));
    }

    private function getNamespacedForRoute($controller)
    {
        $namespaced = $this->getNamespaced($controller);

        return str_replace('App\\Http\\Controllers\\', '', $namespaced);
    }

    private function mapMethods(Collection $controllers): Collection
    {
        return $controllers->mapWithKeys(function ($controller) {
            $class = new \ReflectionClass($this->getNamespaced($controller));
            $methods = collect($class->getMethods())->filter(function ($method) {
                return $method->isPublic()
                    && !Str::startsWith($method->name, '__')
                    && !collect($this->ignoreClasses)->contains($method->class);
            })->map->name->values()->toArray();

            return [$controller => $methods];
        });
    }

    private function makeRoutes(Collection $controllers)
    {
        foreach ($controllers as $controllerName => $methods) {
            $controller = $this->getNamespacedForRoute($controllerName);
            foreach ($methods as $method) {
                $httpMethod = $this->inferHttpMethodFromMethodName($method);
                $model      = $this->getModelFromControllerName($controllerName);
                $url        = $this->getRouteOf($controllerName, $model, $method);
                $route      = Str::plural(Str::kebab($model));
                $routeName  = Str::kebab($method);

                Route::{$httpMethod}("$url", "$controller@$method")->name("$route.$routeName");
            }
        }
    }

    private function getRouteOf(string $controller, string $model, string $method)
    {
        $modelSlug             = Str::kebab($model);
        $modelInVariableFormat = Str::camel($modelSlug);
        $defaultRoute          = Str::plural($modelSlug);
        $methodSlug            = Str::kebab($this->sanitizeMethodName($method));
        $urlParams             = $this->getMethodParametersOf($controller, $method)->map(function (\ReflectionParameter $param) {
            return '{' . $param->getName() . '}';
        })->join('/');

        switch ($method) {
            case 'index':
                return $defaultRoute;
            case 'update':
            case 'show':
            case 'destroy':
                return "$defaultRoute/{" . $modelInVariableFormat . '}';
            case 'forceDelete':
                return "$defaultRoute/{" . $modelInVariableFormat . '}/force-delete';
            case 'restore':
                return "$defaultRoute/{" . $modelInVariableFormat . '}/restore';
            case 'store':
                return "$defaultRoute";
            default:
                return "$defaultRoute/$methodSlug/$urlParams";
        }
    }

    private function getMethodParametersOf(string $controller, string $method): Collection
    {
        $controller = $this->getNamespaced($controller);
        $ref        = new \ReflectionClass($controller);

        return collect($ref->getMethod($method)->getParameters())->filter(function (\ReflectionParameter $param) {
            if (!$param->hasType() || $param->getClass() === null) {
                return $param;
            }

            return !$param->getClass()->isSubclassOf(Request::class)
                && $param->getType()->getName() !== Request::class;
        })->values();
    }

    private function sanitizeMethodName(string $method): string
    {
        $sanitized = $method;

        foreach ($this->httpMethods() as $httpMethod) {
            if (substr($sanitized, 0, strlen($httpMethod)) == $httpMethod) {
                $sanitized = Str::camel(substr($sanitized, strlen($httpMethod)));
            }
        }

        return $sanitized;
    }

    private function inferHttpMethodFromMethodName(string $method)
    {
        $resourceMethods = collect(['index', 'store', 'update', 'show', 'destroy', 'forceDelete', 'restore']);

        if ($resourceMethods->contains($method)) {
            return $this->getResourceHttpMethodFrom($method);
        }

        list($httpMethod) = explode('-', Str::kebab($method));

        return $this->httpMethods()->contains($httpMethod) ? $httpMethod : 'get';
    }

    private function httpMethods(): Collection
    {
        if ($this->httpMethods) {
            return $this->httpMethods;
        }

        return $this->httpMethods = collect(['get', 'post', 'put', 'patch', 'delete']);
    }

    private function getResourceHttpMethodFrom(string $method)
    {
        switch ($method) {
            case 'index':
                return 'get';
            case 'store':
                return 'post';
            case 'update':
            case 'restore':
                return 'put';
            case 'show':
                return 'get';
            case 'destroy':
            case 'forceDelete':
                return 'delete';
        }
        throw new \LogicException('There is no http method defined for the resource method "' . $method . '"');
    }

    private function getModelFromControllerName(string $controller)
    {
        list($controller) = explode('-', Str::kebab($controller));

        return Str::studly($controller);
    }
}
