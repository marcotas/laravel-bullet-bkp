<?php

namespace App\Bullet\Traits;

trait CrudOperations
{
    use IndexAction,
        StoreAction,
        UpdateAction,
        DestroyAction,
        ShowAction,
        ForceDeleteAction,
        RestoreAction;

    protected $model;
    protected $only;
    protected $except;
}
