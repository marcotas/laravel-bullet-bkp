<?php

namespace App\Http\Controllers\Bullet;

use App\Http\Controllers\BulletController;
use Spatie\QueryBuilder\Filter;

class PostController extends BulletController
{
    protected $defaultSorts    = 'title';
    protected $allowedIncludes = ['owner'];

    protected function allowedFilters()
    {
        return [
            Filter::scope('withTrashed'),
            Filter::scope('onlyTrashed'),
            Filter::exact('id'),
        ];
    }
}
