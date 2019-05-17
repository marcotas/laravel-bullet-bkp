<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->words(4, true),
        'body' => $faker->text,
        'published_at' => Arr::random([$faker->dateTime, null]),
        'owner_id' => function () {
            return create(\App\Models\User::class)->id;
        }
    ];
});
