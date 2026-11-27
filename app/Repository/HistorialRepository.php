<?php

namespace App\Repository;

use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Collection;

class HistorialRepository
{
    /**
     * Obtener los clientes que tienen motocicletas
     * con servicios finalizados y entregados.
     */
    public function obtenerClientesConHistorial(): Collection
    {
        return Cliente::with([
            'motos' => function ($query) {
                $query->whereHas('inventarios.ordenTrabajo', function ($orden) {
                    $orden->where('estado', 'finalizada')
                        ->whereHas('entrega');
                })->with([
                    'marca',
                    'inventarios' => function ($inventario) {
                        $inventario->whereHas('ordenTrabajo', function ($orden) {
                            $orden->where('estado', 'finalizada')
                                ->whereHas('entrega');
                        })->with([
                            'ordenTrabajo' => function ($orden) {
                                $orden->where('estado', 'finalizada')
                                    ->whereHas('entrega')
                                    ->with([
                                        'preorden.repuestos.repuesto',
                                        'preorden.pagos',
                                        'mecanico',
                                        'pagos',
                                        'entrega',
                                    ]);
                            },
                        ]);
                    },
                ]);
            },
        ])
            ->whereHas('motos.inventarios.ordenTrabajo', function ($orden) {
                $orden->where('estado', 'finalizada')
                    ->whereHas('entrega');
            })
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtener todos los servicios finalizados y entregados
     * correspondientes a una motocicleta.
     */
    public function obtenerServiciosPorMoto(int $idMoto): Collection
    {
        return OrdenTrabajo::with([
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
        ])
            ->where('estado', 'finalizada')
            ->whereHas('entrega')
            ->whereHas('inventario', function ($query) use ($idMoto) {
                $query->where('id_moto', $idMoto);
            })
            ->orderByDesc('fecha_fin')
            ->get();
    }

    /**
     * Obtener todos los servicios finalizados y entregados.
     */
    public function obtenerServicios(): Collection
    {
        return OrdenTrabajo::with([
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
        ])
            ->where('estado', 'finalizada')
            ->whereHas('entrega')
            ->orderByDesc('fecha_fin')
            ->get();
    }

    /**
     * Obtener un servicio específico del historial.
     */
    public function obtenerServicio(int $id): ?OrdenTrabajo
    {
        return OrdenTrabajo::with([
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
        ])
            ->where('estado', 'finalizada')
            ->whereHas('entrega')
            ->find($id);
    }


    /**
     * Obtener un cliente con todas sus motocicletas
     * y sus servicios finalizados y entregados.
     */
    public function obtenerClienteConHistorial(int $idCliente): ?Cliente
    {
        return Cliente::with([
            'motos' => function ($query) {
                $query->with([
                    'marca',
                    'inventarios' => function ($inventario) {
                        $inventario->whereHas('ordenTrabajo', function ($orden) {
                            $orden->where('estado', 'finalizada')
                                ->whereHas('entrega');
                        })->with([
                            'moto.cliente',
                            'moto.marca',
                            'diagnosticos',
                            'ordenTrabajo' => function ($orden) {
                                $orden->where('estado', 'finalizada')
                                    ->whereHas('entrega')
                                    ->with([
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
                                    ]);
                            },
                        ]);
                    },
                ]);
            },
        ])
            ->whereKey($idCliente)
            ->first();
    }

}
