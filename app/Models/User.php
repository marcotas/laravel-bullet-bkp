<?php

namespace App\Models;

use App\Bullet\Traits\Searchable;
use Illuminate\Auth\MustVerifyEmail as VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, VerifyEmail, Searchable, HasApiTokens;

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

    public function scopeVerified(Builder $builder, $verified = true)
    {
        return $verified
            ? $builder->whereNotNull('email_verified_at')
            : $builder->whereNull('email_verified_at');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'owner_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_teams');
    }

    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function currentTeam(): ? Team
    {
        if (!$this->team && $this->teams()->count()) {
            $this->forceFill(['current_team_id' => $this->teams->first()->id])->save();
            $this->refresh();

            return $this->team;
        }

        return $this->team;
    }

    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }

    public function joinTeam(Team $team)
    {
        $this->teams()->syncWithoutDetaching($team);
    }
}
