<?php

namespace App\Repository;

use App\Models\Mecanico;
use App\Models\OrdenTrabajo;
use App\Models\Preorden;

class OrdenTrabajoRepository
{
    public function listartodo()
    {
        return OrdenTrabajo::with([
            'preorden.diagnostico.inventario.moto.cliente',
            'preorden.repuestos.repuesto',
            'preorden.pagos',
            'inventario.moto.cliente',
            'mecanico',
            'pagos',
            'entrega',
        ])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function listarPreordenesDisponibles()
    {
        return Preorden::with([
            'diagnostico.inventario.moto.cliente',
            'ordenTrabajo',
        ])
            ->where('estado', 'aprobada')
            ->whereDoesntHave('ordenTrabajo')
            ->get();
    }

    public function listarMecanicos()
    {
        return Mecanico::all();
    }

    public function buscarPreorden(int $id)
    {
        return Preorden::with('diagnostico.inventario')
            ->findOrFail($id);
    }

    public function existeOrdenTrabajoPorPreorden(int $idPreorden): bool
    {
        return OrdenTrabajo::where('id_preorden', $idPreorden)
            ->exists();
    }

    public function store(array $datos)
    {
        return OrdenTrabajo::create($datos);
    }

    public function edit(int $id)
    {
        return OrdenTrabajo::with([
            'preorden.diagnostico.inventario.moto.cliente',
            'preorden.repuestos.repuesto',
            'inventario.moto.cliente',
            'mecanico',
            'pagos',
            'entrega',
        ])->findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $ordenTrabajo = OrdenTrabajo::findOrFail($id);

        $ordenTrabajo->update($datos);

        return $ordenTrabajo;
    }

    public function tienePagosOEntrega(int $id): bool
    {
        $ordenTrabajo = OrdenTrabajo::with([
            'pagos',
            'preorden.pagos',
            'entrega',
        ])->findOrFail($id);

        return $ordenTrabajo->pagos->isNotEmpty()
            || $ordenTrabajo->preorden?->pagos->isNotEmpty()
            || $ordenTrabajo->entrega !== null;
    }

    public function destroy(int $id)
    {
        $ordenTrabajo = OrdenTrabajo::findOrFail($id);

        return $ordenTrabajo->delete();
    }
}
