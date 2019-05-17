<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::each(function (User $user) {
            $team = create(Team::class, ['owner_id' => $user]);
            $user->joinTeam($team);
        });
    }
}
