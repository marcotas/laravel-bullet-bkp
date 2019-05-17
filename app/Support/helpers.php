<?php

function user(): ? App\Models\User
{
    return auth()->user();
}

function team(): ? App\Models\Team
{
    return user()->current_team;
}
