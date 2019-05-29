<?php

namespace App\Bullet\Traits;

trait CrudOperations
{
    use IndexAction,
        StoreAction,
        UpdateAction,
        DestroyAction,
        ShowAction,
        EditAction,
        ForceDeleteAction,
        RestoreAction;

    protected $model;
    protected $only;
    protected $except;
}
