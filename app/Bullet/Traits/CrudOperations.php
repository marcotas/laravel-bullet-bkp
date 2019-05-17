<?php

namespace App\Bullet\Traits;

use Illuminate\Http\Request;

trait CrudOperations
{
    use IndexAction;

    protected $model;

    public function store(Request $request)
    {
        return $this->getModel()::create($request->all());
    }

    public function create()
    {
    }

    public function edit($id)
    {
        $this->model::findOrFail($id);
        # code...
    }

    public function update($id)
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
