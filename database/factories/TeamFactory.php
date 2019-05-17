<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Team;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'name'     => $faker->company,
        'owner_id' => function () {
            return create(User::class)->id;
        },
    ];
});
