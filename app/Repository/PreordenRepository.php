<?php

namespace App\Repository;

use App\Models\Diagnostico;
use App\Models\Preorden;
use App\Models\Repuesto;

class PreordenRepository
{
    public function listartodo()
    {
        return Preorden::with([
            'diagnostico',
            'repuestos.repuesto'
        ])->get();
    }

    public function listarDiagnosticosDisponibles()
    {
        return Diagnostico::with('inventario.moto')
            ->whereDoesntHave('preordenes')
            ->get();
    }

    public function obtenerPreordenConRepuestos(int $id)
    {
        return Preorden::with('repuestos.repuesto')->findOrFail($id);
    }

    public function listarRepuestos()
    {
        return Repuesto::all();
    }

    public function store(array $datos)
    {
        return Preorden::create($datos);
    }

    public function buscarRepuesto(int $id)
    {
        return Repuesto::findOrFail($id);
    }

    public function sumarSubtotalRepuestos(int $idPreorden)
    {
        return Preorden::findOrFail($idPreorden)
            ->repuestos()
            ->sum('subtotal');
    }

    public function storeRepuesto(Preorden $preorden, array $datos)
    {
        return $preorden->repuestos()->create($datos);
    }

    public function edit(int $id)
    {
        return Preorden::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $preorden = Preorden::findOrFail($id);

        $preorden->update($datos);

        return $preorden;
    }

    public function tieneRelaciones(int $id): bool
    {
        $preorden = Preorden::findOrFail($id);

        return $preorden->repuestos()->exists()
            || $preorden->ordenTrabajo()->exists()
            || $preorden->pagos()->exists();
    }

    public function destroy(int $id)
    {
        $preorden = Preorden::findOrFail($id);

        return $preorden->delete();
    }

    public function aprobar(int $id)
    {
        $preorden = Preorden::findOrFail($id);

        $preorden->estado = 'aprobada';

        return $preorden->save();
    }

    public function rechazar(int $id)
    {
        $preorden = Preorden::findOrFail($id);

        $preorden->estado = 'rechazada';

        return $preorden->save();
    }
}
