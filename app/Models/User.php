<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as VerifyEmail;
use App\Bullet\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, VerifyEmail, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender',
    ];

    protected $searchableFields = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeVerified(Builder $builder)
    {
        return $builder->whereNotNull('email_verified_at');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'owner_id');
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }
}
