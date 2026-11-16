<?php

namespace App\Repository;

use App\Models\Preorden;
use App\Models\PreordenRepuesto;
use App\Models\Repuesto;

class PreordenRepuestoRepository
{
    public function listartodo()
    {
        return PreordenRepuesto::with([
            'preorden',
            'repuesto'
        ])->get();
    }

    public function listarPreordenes()
    {
        return Preorden::all();
    }

    public function listarRepuestos()
    {
        return Repuesto::all();
    }

    public function buscarRepuesto(int $id)
    {
        return Repuesto::findOrFail($id);
    }

    public function obtenerPreorden(int $id)
    {
        return Preorden::findOrFail($id);
    }

    public function sumarSubtotalRepuestos(int $idPreorden)
    {
        return PreordenRepuesto::where(
            'id_preorden',
            $idPreorden
        )->sum('subtotal');
    }

    public function actualizarTotalPreorden(
        int $idPreorden,
        float $total
    ) {
        $preorden = Preorden::findOrFail($idPreorden);

        $preorden->total = $total;

        return $preorden->save();
    }

    public function store(array $datos)
    {
        return PreordenRepuesto::create($datos);
    }

    public function edit(int $id)
    {
        return PreordenRepuesto::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $preordenRepuesto = PreordenRepuesto::findOrFail($id);

        $preordenRepuesto->update($datos);

        return $preordenRepuesto;
    }

    public function destroy(int $id)
    {
        $preordenRepuesto = PreordenRepuesto::findOrFail($id);

        return $preordenRepuesto->delete();
    }
}
