<?php

namespace App\Bullet\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use App\Bullet\Bullet;

class BulletServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('bullet', function () {
            return new Bullet();
        });
    }

    public function boot()
    {
        // Route::macro('')
        // dd('boot bullet service provider');
    }
}
