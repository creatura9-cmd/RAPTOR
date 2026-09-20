<?php

namespace App\Repository;

use App\Models\MarcaMoto;

class MarcaMotoRepository
{
    public function listartodo()
    {
        return MarcaMoto::all();
    }

    public function store(array $datos)
    {
        MarcaMoto::create($datos);
    }

    public function edit(int $id)
    {
        return MarcaMoto::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $marca = MarcaMoto::findOrFail($id);

        $marca->update($datos);
    }

    public function destroy(int $id)
    {
        MarcaMoto::destroy($id);
    }
}