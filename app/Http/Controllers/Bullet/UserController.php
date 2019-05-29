<?php

namespace App\Http\Controllers\Bullet;

use App\Http\Controllers\BulletController;
use Spatie\QueryBuilder\Filter;

class UserController extends BulletController
{
    protected $defaultSorts    = '-created_at';
    protected $allowedIncludes = ['posts.owner'];
    protected $allowedAppends  = 'posts_count';

    protected function allowedFilters()
    {
        return [
            Filter::scope('verified'),
        ];
    }

    protected function beforeStore($request, $attributes)
    {
        $attributes['password'] = bcrypt($attributes['password']);

        return $attributes;
    }

    protected function getStoreQuery($request)
    {
        return team()->users();
    }

    protected function afterStore($request, $model)
    {
        $model->currentTeam();

        return $model->load('team');
    }
}
