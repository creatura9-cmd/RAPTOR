<?php

namespace App\Repository;

use App\Models\Repuesto;

class RepuestoRepository
{
    public function listartodo()
    {
        return Repuesto::all();
    }

    public function store(array $datos)
    {
        Repuesto::create($datos);
    }

    public function edit(int $id)
    {
        return Repuesto::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $repuesto = Repuesto::findOrFail($id);

        $repuesto->update($datos);
    }

    public function destroy(int $id)
    {
        Repuesto::destroy($id);
    }
}