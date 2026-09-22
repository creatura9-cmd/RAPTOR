<?php

namespace App\Repository;

use App\Models\Diagnostico;

class DiagnosticoRepository
{
    public function listartodo()
    {
        return Diagnostico::with(['inventario', 'mecanico'])->get();
    }

    public function store(array $datos)
    {
        return Diagnostico::create($datos);
    }

    public function edit(int $id)
    {
        return Diagnostico::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $diagnostico = Diagnostico::findOrFail($id);

        $diagnostico->update($datos);
    }

    public function destroy(int $id)
    {
        Diagnostico::destroy($id);
    }
}