<?php

namespace App\Service;

use App\Models\Cliente;
use App\Repository\PortalClienteRepository;

class PortalClienteService
{
    public function __construct(
        protected PortalClienteRepository $portalClienteRepository,
        protected PreordenService $preordenService
    ) {}

    /**
     * Obtener toda la información necesaria para el portal.
     */
    public function obtenerDatosPortal(Cliente $cliente): array
    {
        $motos = $this->portalClienteRepository->obtenerMotos($cliente);

        $motosConServicios = $this->portalClienteRepository->obtenerMotosConServiciosPendientes($cliente);

        $preordenes = $this->portalClienteRepository->obtenerPreordenes($cliente);

        // Una tarjeta de seguimiento por motocicleta.
        $seguimientoMotos = $motosConServicios->map(function ($moto) use ($preordenes) {
            $servicio = $preordenes->first(function ($preorden) use ($moto) {
                $idMoto = $preorden->diagnostico?->inventario?->moto?->id;

                return (int) $idMoto === (int) $moto->id;
            });

            return [
                'moto' => $moto,
                'servicio' => $servicio,
            ];
        });

        return [
            'cliente' => $cliente,
            'motos' => $motos,
            'preordenes' => $preordenes,
            'motosConServicios' => $motosConServicios,
            'seguimientoMotos' => $seguimientoMotos,
        ];
    }

    /**
     * Aprobar una preorden perteneciente al cliente.
     */
    public function aprobar(Cliente $cliente, int $id): void
    {
        $this->portalClienteRepository->buscarPreordenDelCliente($cliente, $id);

        $this->preordenService->aprobar($id);
    }

    /**
     * Rechazar una preorden perteneciente al cliente.
     */
    public function rechazar(Cliente $cliente, int $id): void
    {
        $this->portalClienteRepository->buscarPreordenDelCliente($cliente, $id);

        $this->preordenService->rechazar($id);
    }
}
