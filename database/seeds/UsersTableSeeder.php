<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create(['name' => 'Marco Túlio', 'email' => 'marco@mail.com', 'gender' => 'male']);
        factory(User::class, rand(500, 1000))->create();
    }
}
