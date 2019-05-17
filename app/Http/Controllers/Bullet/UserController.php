<?php

namespace App\Http\Controllers\Bullet;

use App\Http\Controllers\BulletController;
use Spatie\QueryBuilder\Filter;
use Illuminate\Http\Request;

class UserController extends BulletController
{
    protected $defaultSorts = '-created_at';
    protected $allowedIncludes = ['posts.owner'];
    protected $allowedAppends = 'posts_count';

    protected function allowedFilters()
    {
        return [
            Filter::scope('verified'),
        ];
    }
}
