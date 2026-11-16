<?php

namespace App\Repository;

use App\Models\Cliente;
use App\Models\Preorden;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Collection;

class PortalClienteRepository
{
    /**
     * Relaciones necesarias para consultar el historial de servicios.
     */
    private function relacionesHistorial(): array
    {
        return [
            'inventario.moto.cliente',
            'inventario.moto.marca',
            'inventario.diagnosticos',
            'preorden.diagnostico.inventario.moto.cliente',
            'preorden.diagnostico.inventario.moto.marca',
            'preorden.repuestos.repuesto',
            'preorden.pagos',
            'mecanico',
            'pagos',
            'entrega',
        ];
    }

    /**
     * Obtener las motocicletas pertenecientes al cliente.
     */
    public function obtenerMotos(Cliente $cliente): Collection
    {
        return $cliente->motos()
            ->with('marca')
            ->get();
    }

    /**
     * Obtener las motocicletas que tienen servicios pendientes de entrega.
     */
    public function obtenerMotosConServiciosPendientes(Cliente $cliente): Collection
    {
        return $cliente->motos()
            ->with('marca')
            ->whereHas('inventarios', function ($query) {
                $query->whereDoesntHave('ordenTrabajo', function ($orden) {
                    $orden->where('estado', 'finalizada')
                        ->whereHas('entrega');
                });
            })
            ->get();
    }

    /**
     * Obtener todas las preórdenes de las motocicletas del cliente.
     */
    public function obtenerPreordenes(Cliente $cliente): Collection
    {
        return Preorden::with([
            'diagnostico.inventario.moto.marca',
            'diagnostico.mecanico',
            'repuestos.repuesto',
            'pagos',
            'ordenTrabajo.mecanico',
            'ordenTrabajo.pagos',
            'ordenTrabajo.entrega',
        ])
            ->whereHas('diagnostico.inventario.moto', function ($query) use ($cliente) {
                $query->where('id_cliente', $cliente->id);
            })
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Buscar una preorden que pertenezca al cliente autenticado.
     */
    public function buscarPreordenDelCliente(
        Cliente $cliente,
        int $id
    ): Preorden {
        return Preorden::whereHas('diagnostico.inventario.moto', function ($query) use ($cliente) {
            $query->where('id_cliente', $cliente->id);
        })->findOrFail($id);
    }

    /**
     * Obtener un servicio entregado que pertenezca al cliente.
     */
    public function obtenerServicioDelCliente(
        Cliente $cliente,
        int $id
    ): ?OrdenTrabajo {
        return OrdenTrabajo::with($this->relacionesHistorial())
            ->where('id', $id)
            ->where('estado', 'finalizada')
            ->whereHas('entrega')
            ->whereHas('inventario.moto', function ($query) use ($cliente) {
                $query->where('id_cliente', $cliente->id);
            })
            ->first();
    }

    /**
     * Obtener el historial de servicios entregados de una moto del cliente.
     */
    public function obtenerHistorialMotoDelCliente(
        Cliente $cliente,
        int $idMoto
    ): Collection {
        return OrdenTrabajo::with($this->relacionesHistorial())
            ->where('estado', 'finalizada')
            ->whereHas('entrega')
            ->whereHas('inventario.moto', function ($query) use ($cliente, $idMoto) {
                $query->where('id', $idMoto)
                    ->where('id_cliente', $cliente->id);
            })
            ->orderByDesc('fecha_fin')
            ->get();
    }
}
