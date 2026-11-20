<?php

namespace App\Service;

use App\Repository\OrdenTrabajoRepository;
use Illuminate\Validation\ValidationException;

class OrdenTrabajoService
{
    protected OrdenTrabajoRepository $ordenTrabajoRepository;

    public function __construct(
        OrdenTrabajoRepository $ordenTrabajoRepository
    ) {
        $this->ordenTrabajoRepository = $ordenTrabajoRepository;
    }

    public function listartodo()
    {
        $ordenesTrabajo = $this->ordenTrabajoRepository->listartodo();

        return $ordenesTrabajo->map(function ($ordenTrabajo) {

            $preorden = $ordenTrabajo->preorden;

            // Obtener el total de la preorden.
            $totalOT = (float) ($preorden?->total ?? 0);

            // Sumar únicamente los pagos confirmados de la preorden.
            $totalPagado = (float) ($preorden?->pagos ?? collect())
                ->where('estado', 'confirmado')
                ->sum('valor');

            // El saldo pendiente nunca puede ser negativo.
            $saldo = max(0, $totalOT - $totalPagado);

            // Entregar los valores calculados a la vista.
            $ordenTrabajo->setAttribute('total_ot', $totalOT);
            $ordenTrabajo->setAttribute('total_pagado', $totalPagado);
            $ordenTrabajo->setAttribute('saldo_pendiente', $saldo);

            return $ordenTrabajo;
        });
    }

    public function listarPreordenesDisponibles()
    {
        return $this->ordenTrabajoRepository->listarPreordenesDisponibles();
    }

    public function listarMecanicos()
    {
        return $this->ordenTrabajoRepository->listarMecanicos();
    }

    public function store(array $datos)
    {
        $preorden = $this->ordenTrabajoRepository
            ->buscarPreorden((int) $datos['id_preorden']);

        // 1. La preorden debe estar aprobada.
        if ($preorden->estado !== 'aprobada') {
            throw ValidationException::withMessages([
                'id_preorden' => 'No se puede crear la orden de trabajo: la preorden debe estar aprobada.',
            ]);
        }

        // 2. La preorden debe tener diagnóstico e inventario asociados.
        $diagnostico = $preorden->diagnostico;

        if (! $diagnostico || ! $diagnostico->inventario) {
            throw ValidationException::withMessages([
                'id_preorden' => 'No se puede crear la orden de trabajo: no se encontró el diagnóstico o el inventario de la preorden.',
            ]);
        }

        $inventario = $diagnostico->inventario;

        // 3. Una preorden solo puede tener una orden de trabajo.
        if (
            $this->ordenTrabajoRepository
                ->existeOrdenTrabajoPorPreorden($preorden->id)
        ) {
            throw ValidationException::withMessages([
                'id_preorden' => 'Esta preorden ya tiene una orden de trabajo registrada.',
            ]);
        }

        // 4. Asignar el inventario correspondiente.
        $datos['id_inventario'] = $inventario->id;

        return $this->ordenTrabajoRepository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->ordenTrabajoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->ordenTrabajoRepository->edit($id);

        // No permitir cambiar la preorden ni el inventario original.
        unset($datos['id_preorden'], $datos['id_inventario']);

        return $this->ordenTrabajoRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        // No permitir eliminar órdenes que tengan pagos o entrega.
        if ($this->ordenTrabajoRepository->tienePagosOEntrega($id)) {
            throw ValidationException::withMessages([
                'orden_trabajo' => 'No se puede eliminar la orden de trabajo porque tiene pagos o una entrega asociada.',
            ]);
        }

        return $this->ordenTrabajoRepository->destroy($id);
    }
}
