<?php

namespace App\Repository;

use App\Models\Pago;
use App\Models\Preorden;
use App\Models\OrdenTrabajo;

class PagoRepository
{
    /*
    |--------------------------------------------------------------------------
    | Consultas generales
    |--------------------------------------------------------------------------
    */

    public function listartodo()
    {
        return Pago::with([
            'preorden.diagnostico.inventario.moto.cliente',
            'preorden.ordenTrabajo',
            'ordenTrabajo',
        ])->get();
    }

    public function resumenRecepciones()
    {
        return $this->preordenesAprobadasConRelaciones();
    }

    public function preordenesAprobadasConRelaciones()
    {
        return Preorden::with([
            'diagnostico.inventario.moto.cliente',
            'ordenTrabajo',
            'pagos',
        ])
            ->where('estado', 'aprobada')
            ->get();
    }

    public function buscarPreordenAprobadaPorOrdenTrabajo(
        int $idOrdenTrabajo
    ) {
        return Preorden::with('ordenTrabajo')
            ->whereHas('ordenTrabajo', function ($query) use ($idOrdenTrabajo) {
                $query->where('orden_trabajo.id', $idOrdenTrabajo);
            })
            ->where('estado', 'aprobada')
            ->first();
    }

    public function preordenesConRelaciones()
    {
        return Preorden::with([
            'diagnostico.inventario.moto.cliente',
            'ordenTrabajo',
        ])->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Operaciones CRUD
    |--------------------------------------------------------------------------
    */

    public function store(array $datos)
    {
        return Pago::create($datos);
    }

    public function edit(int $id)
    {
        return Pago::with([
            'preorden.diagnostico.inventario.moto.cliente',
            'preorden.ordenTrabajo',
            'ordenTrabajo',
        ])->findOrFail($id);
    }

    public function update(Pago $pago, array $datos)
    {
        $pago->update($datos);

        return $pago;
    }

    public function destroy(int $id)
    {
        $pago = Pago::findOrFail($id);

        return $pago->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Consultas para las reglas de negocio de pagos
    |--------------------------------------------------------------------------
    */

    public function buscarPreordenBloqueada(int $id)
    {
        return Preorden::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function buscarPagoBloqueado(int $id)
    {
        return Pago::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function buscarOrdenTrabajo(int $id)
    {
        return OrdenTrabajo::findOrFail($id);
    }

    public function totalPagosConfirmados(
        Preorden $preorden,
        ?int $idPagoExcluir = null
    ): float {
        $consulta = $preorden->pagos()
            ->where('estado', 'confirmado');

        if ($idPagoExcluir !== null) {
            $consulta->where('id', '!=', $idPagoExcluir);
        }

        return (float) $consulta->sum('valor');
    }

    public function guardar(Pago $pago)
    {
        $pago->save();

        return $pago;
    }

    public function eliminar(Pago $pago)
    {
        return $pago->delete();
    }
}