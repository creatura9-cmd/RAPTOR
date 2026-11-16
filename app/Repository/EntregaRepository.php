<?php

namespace App\Repository;

use App\Models\Entrega;
use App\Models\OrdenTrabajo;

class EntregaRepository
{
    public function listartodo()
    {
        return Entrega::with([
            'ordenTrabajo.inventario.moto.marca',
            'ordenTrabajo.inventario.moto.cliente',
        ])->get();
    }

    public function buscarporid($id)
    {
        return Entrega::with('ordenTrabajo')->findOrFail($id);
    }

    /**
     * Consulta órdenes finalizadas que todavía no tienen entrega.
     */
    public function ordenesFinalizadasSinEntrega()
    {
        return OrdenTrabajo::with([
            'inventario.moto.cliente',
            'preorden.pagos',
            'entrega',
        ])
            ->where('estado', 'finalizada')
            ->whereDoesntHave('entrega')
            ->get();
    }

    /**
     * Busca una orden con los datos necesarios para validar su entrega.
     */
    public function buscarOrdenParaEntrega($id)
    {
        return OrdenTrabajo::with([
            'preorden.pagos',
            'entrega',
        ])->findOrFail($id);
    }

    public function guardar(array $data)
    {
        return Entrega::create($data);
    }

    public function actualizar($id, array $data)
    {
        $entrega = Entrega::findOrFail($id);

        $entrega->update($data);

        return $entrega;
    }

    public function eliminar($id)
    {
        $entrega = Entrega::findOrFail($id);

        return $entrega->delete();
    }
}
