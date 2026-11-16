<?php

namespace App\Service;

use App\Repository\EntregaRepository;

class EntregaService
{
    protected $entregaRepository;

    public function __construct(EntregaRepository $entregaRepository)
    {
        $this->entregaRepository = $entregaRepository;
    }

    public function listartodo()
    {
        return $this->entregaRepository->listartodo();
    }

    public function buscarporid($id)
    {
        return $this->entregaRepository->buscarporid($id);
    }

    /**
     * Devuelve únicamente las órdenes que pueden entregarse.
     */
    public function ordenesDisponiblesParaEntrega()
    {
        return $this->entregaRepository
            ->ordenesFinalizadasSinEntrega()
            ->filter(function ($ordenTrabajo) {

                $preorden = $this->obtenerPreorden($ordenTrabajo);

                // La orden debe tener una preorden asociada.
                if (!$preorden) {
                    return false;
                }

                $total = (float) $preorden->total;

                $pagosConfirmados = (float) $preorden->pagos
                    ->where('estado', 'confirmado')
                    ->sum('valor');

                $saldo = round($total - $pagosConfirmados, 2);

                return $saldo === 0.0;
            })
            ->values();
    }

    public function guardar(array $data)
    {
        $this->validarOrdenParaEntrega($data['id_ordentrabajo']);

        return $this->entregaRepository->guardar($data);
    }

    /**
     * Obtiene la preorden asociada directamente a la orden de trabajo.
     */
    private function obtenerPreorden($ordenTrabajo)
    {
        return $ordenTrabajo->preorden;
    }

    private function validarOrdenParaEntrega($idOrdenTrabajo, $idEntregaActual = null)
    {
        $ordenTrabajo = $this->entregaRepository
            ->buscarOrdenParaEntrega($idOrdenTrabajo);

        // La orden debe estar finalizada.
        if ($ordenTrabajo->estado !== 'finalizada') {
            throw new \Exception(
                'La orden de trabajo debe estar finalizada para realizar la entrega.'
            );
        }

        // No permitir otra entrega para la misma orden.
        $entregaExistente = $ordenTrabajo->entrega;

        if (
            $entregaExistente &&
            (int) $entregaExistente->id_entrega !== (int) $idEntregaActual
        ) {
            throw new \Exception(
                'Esta orden de trabajo ya tiene una entrega registrada.'
            );
        }

        $preorden = $this->obtenerPreorden($ordenTrabajo);

        if (!$preorden) {
            throw new \Exception(
                'La orden de trabajo no tiene una preorden asociada.'
            );
        }

        $total = (float) $preorden->total;

        $pagosConfirmados = (float) $preorden->pagos
            ->where('estado', 'confirmado')
            ->sum('valor');

        $saldo = round($total - $pagosConfirmados, 2);

        if ($saldo !== 0.0) {
            throw new \Exception(
                'No se puede realizar la entrega porque la orden de trabajo tiene un saldo pendiente.'
            );
        }

        return $ordenTrabajo;
    }

    public function actualizar($id, array $data)
    {
        $entregaActual = $this->entregaRepository->buscarporid($id);

        $this->validarOrdenParaEntrega(
            $data['id_ordentrabajo'],
            $entregaActual->id_entrega
        );

        return $this->entregaRepository->actualizar($id, $data);
    }

    public function eliminar($id)
    {
        return $this->entregaRepository->eliminar($id);
    }
}
