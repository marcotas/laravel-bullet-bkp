<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            create(Post::class, ['owner_id' => $user], rand(1, 10));
        });
    }
}
