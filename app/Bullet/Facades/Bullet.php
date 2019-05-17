<?php

namespace App\Bullet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void controllers(string $directory = 'app/Http/Controllers')
 *
 * @see \App\Bullet\Bullet
 */
class Bullet extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'bullet';
    }
}
