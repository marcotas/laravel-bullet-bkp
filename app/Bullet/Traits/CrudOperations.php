<?php

namespace App\Bullet\Traits;

trait CrudOperations
{
    public function index()
    {
        dd('index from trait');
    }

    public function store()
    {
        dd('store from trait');
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        # code...
    }

    public function show()
    {
        # code...
    }
}
