<?php

namespace App\Service;

use App\Repository\HistorialRepository;
use App\Repository\PortalClienteRepository;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class HistorialService
{
    protected HistorialRepository $historialRepository;
    protected PortalClienteRepository $portalClienteRepository;

    public function __construct(
        HistorialRepository $historialRepository,
        PortalClienteRepository $portalClienteRepository
    ) {
        $this->historialRepository = $historialRepository;
        $this->portalClienteRepository = $portalClienteRepository;
    }

    /**
     * Obtener los clientes que tienen motocicletas
     * con servicios finalizados y entregados.
     */
    public function obtenerClientesConHistorial(): Collection
    {
        return $this->historialRepository->obtenerClientesConHistorial();
    }

    /**
     * Obtener todos los servicios de una motocicleta.
     */
    public function obtenerServiciosPorMoto(int $idMoto): Collection
    {
        return $this->historialRepository->obtenerServiciosPorMoto($idMoto);
    }

    /**
     * Obtener todos los servicios del historial.
     */
    public function obtenerServicios(): Collection
    {
        return $this->historialRepository->obtenerServicios();
    }

    /**
     * Obtener un servicio específico.
     */
    public function obtenerServicio(int $id): ?OrdenTrabajo
    {
        return $this->historialRepository->obtenerServicio($id);
    }

    /**
     * Preparar los datos del listado administrativo del historial.
     */
    public function obtenerDatosHistorial(): array
    {
        $clientes = $this->obtenerClientesConHistorial();

        $clientesPreparados = $clientes->map(function (Cliente $cliente) {
            $serviciosCliente = $cliente->motos->flatMap(
                fn ($moto) => $moto->inventarios->flatMap(
                    fn ($inventario) => $inventario->ordenTrabajo->map(
                        function ($servicio) use ($inventario, $moto) {
                            $fechaEntrega = $servicio->entrega?->fecha_entrega;

                            return [
                                'servicio' => $servicio,
                                'inventario' => $inventario,
                                'moto' => $moto,

                                'folio' => $inventario->recepcion
                                    ? 'RECEPCIÓN # ' . str_pad(
                                        (string) $inventario->recepcion,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    )
                                    : 'RECEPCIÓN',

                                'fechaEntrega' => $fechaEntrega
                                    ? \Carbon\Carbon::parse($fechaEntrega)->format('d/m/Y')
                                    : 'No disponible',

                                'totalServicio' => (float) (
                                    $servicio->preorden?->total ?? 0
                                ),
                            ];
                        }
                    )
                )
            );

            return [
                'cliente' => $cliente,
                'servicios' => $serviciosCliente,
                'cantidadMotos' => $cliente->motos->count(),
                'cantidadServicios' => $serviciosCliente->count(),
            ];
        });

        return [
            'clientesPreparados' => $clientesPreparados,
            'cantidadClientes' => $clientes->count(),
            'cantidadMotos' => $clientes->sum(
                fn ($cliente) => $cliente->motos->count()
            ),
            'cantidadServicios' => $clientesPreparados->sum(
                fn ($item) => $item['cantidadServicios']
            ),
        ];
    }

    /**
     * Preparar los datos de un servicio para los informes PDF.
     */
    private function prepararDatosServicio(OrdenTrabajo $servicio): array
    {
        $inventario = $servicio->inventario;
        $preorden = $servicio->preorden;

        $diagnostico = $preorden?->diagnostico
            ?? $inventario?->diagnosticos;

        $moto = $diagnostico?->inventario?->moto
            ?? $inventario?->moto;

        $cliente = $moto?->cliente;
        $mecanico = $servicio->mecanico;
        $entrega = $servicio->entrega;

        $recepcion = $diagnostico?->inventario?->recepcion
            ?? $inventario?->recepcion;

        $folioRecepcion = 'RECEPCIÓN # ' .
            str_pad((string) ($recepcion ?? 0), 3, '0', STR_PAD_LEFT);

        $nombreCliente = trim(
            ($cliente?->nombre ?? '') . ' ' .
            ($cliente?->apellido ?? '')
        );

        $nombreMecanico = trim(
            ($mecanico?->nombre ?? '') . ' ' .
            ($mecanico?->apellido ?? '')
        );

        $totalServicio = (float) ($preorden?->total ?? 0);

        $totalPagado = (float) (
            ($preorden?->pagos ?? collect())
                ->where('estado', 'confirmado')
                ->sum('valor')
        );

        $saldoPendiente = max(0, $totalServicio - $totalPagado);

        return [
            'inventario' => $inventario,
            'preorden' => $preorden,
            'diagnostico' => $diagnostico,
            'moto' => $moto,
            'cliente' => $cliente,
            'mecanico' => $mecanico,
            'entrega' => $entrega,
            'recepcion' => $recepcion,
            'folioRecepcion' => $folioRecepcion,
            'nombreCliente' => $nombreCliente,
            'nombreMecanico' => $nombreMecanico,
            'totalServicio' => $totalServicio,
            'totalPagado' => $totalPagado,
            'saldoPendiente' => $saldoPendiente,
        ];
    }

    /**
     * Preparar todos los servicios para el PDF de historial general.
     */
    private function prepararServiciosParaPdf(Collection $servicios)
    {
        return $servicios->map(function (OrdenTrabajo $servicio) {
            return array_merge(
                ['servicio' => $servicio],
                $this->prepararDatosServicio($servicio)
            );
        });
    }


    /**
     * Generar el PDF del historial completo de un cliente.
     */
    public function generarPdfHistorialCliente(int $idCliente)
    {
        $cliente = $this->historialRepository
            ->obtenerClienteConHistorial($idCliente);

        if (!$cliente) {
            return null;
        }

        $servicios = $cliente->motos->flatMap(
            fn ($moto) => $moto->inventarios->flatMap(
                fn ($inventario) => $inventario->ordenTrabajo
            )
        )->sortByDesc('fecha_fin')->values();

        if ($servicios->isEmpty()) {
            return null;
        }
        $servicios = new Collection($servicios->all());

        $serviciosPreparados = $this->prepararServiciosParaPdf($servicios);

        return Pdf::loadView(
            'historial.pdf.todos',
            [
                'cliente' => $cliente,
                'motos' => $cliente->motos,
                'servicios' => $serviciosPreparados,
            ]
        )->setPaper('a4', 'landscape');
    }


    /**
     * Generar el PDF de un servicio específico.
     */
    public function generarPdfServicio(int $id)
    {
        $servicio = $this->obtenerServicio($id);

        if (!$servicio) {
            return null;
        }

        $datos = $this->prepararDatosServicio($servicio);

        return Pdf::loadView(
            'historial.pdf.servicio',
            array_merge(['servicio' => $servicio], $datos)
        )->setPaper('a4', 'portrait');
    }

    /**
     * Generar el PDF consolidado de una motocicleta.
     */
    public function generarPdfHistorialMoto(int $idMoto)
    {
        $servicios = $this->obtenerServiciosPorMoto($idMoto);

        if ($servicios->isEmpty()) {
            return null;
        }

        $moto = $servicios->first()->inventario?->moto;

        if (!$moto) {
            return null;
        }

        $serviciosPreparados = $this->prepararServiciosParaPdf($servicios);

        return Pdf::loadView(
            'historial.pdf.todos',
            [
                'servicios' => $serviciosPreparados,
                'moto' => $moto,
            ]
        )->setPaper('a4', 'landscape');
    }

    /**
     * Generar el PDF de un servicio del cliente autenticado.
     */
    public function generarPdfServicioDelCliente(
        Cliente $cliente,
        int $id
    ) {
        $servicio = $this->portalClienteRepository
            ->obtenerServicioDelCliente($cliente, $id);

        if (!$servicio) {
            return null;
        }

        $datos = $this->prepararDatosServicio($servicio);

        return Pdf::loadView(
            'historial.pdf.servicio',
            array_merge(['servicio' => $servicio], $datos)
        )->setPaper('a4', 'portrait');
    }

    /**
     * Generar el PDF del historial de una moto del cliente autenticado.
     */
    public function generarPdfHistorialMotoDelCliente(
        Cliente $cliente,
        int $idMoto
    ) {
        $servicios = $this->portalClienteRepository
            ->obtenerHistorialMotoDelCliente($cliente, $idMoto);

        if ($servicios->isEmpty()) {
            return null;
        }

        $moto = $servicios->first()->inventario?->moto;

        if (!$moto) {
            return null;
        }

        $serviciosPreparados = $this->prepararServiciosParaPdf($servicios);

        return Pdf::loadView(
            'historial.pdf.todos',
            [
                'servicios' => $serviciosPreparados,
                'moto' => $moto,
            ]
        )->setPaper('a4', 'landscape');
    }
}
