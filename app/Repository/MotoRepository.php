<?php

namespace App\Repository;

use App\Models\Moto;

class MotoRepository
{
    public function listartodo()
    {
        return Moto::with(['cliente', 'marca'])->get();
    }

    public function store(array $datos)
    {
        Moto::create($datos);
    }

    public function edit(int $id)
    {
        return Moto::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $moto = Moto::findOrFail($id);

        $moto->update($datos);
    }

    public function destroy(int $id)
    {
        Moto::destroy($id);
    }
}
