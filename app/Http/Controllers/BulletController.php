<?php

namespace App\Http\Controllers;

use App\Bullet\Traits\CrudOperations;

class BulletController extends Controller
{
    use CrudOperations;

    public function __construct()
    {
        $middlewares = [];
        foreach ($this->middleware as $middleware => $options) {
            if (!is_string($middleware)) {
                $middleware = $options;
                $options = [];
            }
            $middlewares[] = compact('middleware', 'options');
        }
        $this->middleware = $middlewares;
    }
}
