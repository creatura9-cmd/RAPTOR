<?php

namespace App\Repository;

use App\Models\Inventario;

class InventarioRepository
{
    public function listartodo()
    {
        return Inventario::with('moto')->get();
    }

    public function store(array $datos)
    {
        Inventario::create($datos);
    }

    public function edit(int $id)
    {
        return Inventario::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $inventario = Inventario::findOrFail($id);

        $inventario->update($datos);
    }

    public function destroy(int $id)
    {
        Inventario::destroy($id);
    }
}
