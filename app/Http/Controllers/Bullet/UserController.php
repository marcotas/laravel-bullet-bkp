<?php

namespace App\Http\Controllers\Bullet;

use Illuminate\Http\Request;
use App\Bullet\Traits\CrudOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateRequest;

class UserController extends Controller
{
    use CrudOperations;

    public function jetete(Request $request)
    {
        dd('jetete action');
    }


    public function postChargeCreditCard(UpdateRequest $request, int $card, $cvc, $address)
    {
        dd('charge credit card');
    }

    // public function postJetete(User $user)
    // {
    //     dd('post jetete');
    // }

    // public function putJetete()
    // {
    //     dd('put jetete');
    // }

    // public function patchJetete()
    // {
    //     dd('patch jetete');
    // }

    // public function deleteJetete()
    // {
    //     dd('delete jetete');
    // }
}
