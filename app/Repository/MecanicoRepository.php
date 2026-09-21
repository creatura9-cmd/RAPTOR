<?php

namespace App\Repository;

use App\Models\Mecanico;

class MecanicoRepository
{
    public function listartodo()
    {
        return Mecanico::all();
    }

    public function store(array $datos)
    {
        Mecanico::create($datos);
    }

    public function edit(int $id)
    {
        return Mecanico::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $mecanico = Mecanico::findOrFail($id);

        $mecanico->update($datos);
    }

    public function destroy(int $id)
    {
        Mecanico::destroy($id);
    }
}