<?php

namespace App\Bullet\Facades;

use Illuminate\Support\Facades\Facade;

class Bullet extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'bullet';
    }
}
