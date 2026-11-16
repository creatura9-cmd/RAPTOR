<?php

namespace App\Service;

use App\Repository\PagoRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoService
{
    protected $pagoRepository;

    public function __construct(PagoRepository $pagoRepository)
    {
        $this->pagoRepository = $pagoRepository;
    }

    public function listartodo()
    {
        return $this->pagoRepository->listartodo();
    }

    public function preordenesDisponiblesParaPago()
    {
        $preordenes = $this->pagoRepository
            ->preordenesAprobadasConRelaciones();

        return $preordenes
            ->filter(function ($preorden) {
                $resumen = $this->calcularSaldoDisponible($preorden);

                return $resumen['saldo'] > 0.009;
            })
            ->values();
    }

    public function buscarPreordenAprobadaPorOrdenTrabajo(
        int $idOrdenTrabajo
    ) {
        return $this->pagoRepository
            ->buscarPreordenAprobadaPorOrdenTrabajo($idOrdenTrabajo);
    }

    public function preordenesConRelaciones()
    {
        return $this->pagoRepository->preordenesConRelaciones();
    }

    private function calcularSaldoDisponible(
        $preorden,
        ?int $idPagoExcluir = null
    ): array {
        $total = (float) $preorden->total;

        $pagado = $this->pagoRepository->totalPagosConfirmados(
            $preorden,
            $idPagoExcluir
        );

        return [
            'total' => $total,
            'pagado' => $pagado,
            'saldo' => max(0, $total - $pagado),
        ];
    }

    private function validarOrdenTrabajo(
        ?int $idOrdenTrabajo,
        int $idPreorden
    ): void {
        if ($idOrdenTrabajo === null) {
            return;
        }

        $ordenTrabajo = $this->pagoRepository->buscarOrdenTrabajo(
            $idOrdenTrabajo
        );

        if ((int) $ordenTrabajo->id_preorden !== $idPreorden) {
            throw new Exception(
                'La orden de trabajo no pertenece a la preorden seleccionada.'
            );
        }
    }

    public function store(array $datos)
    {
        return DB::transaction(function () use ($datos) {

            $preorden = $this->pagoRepository->buscarPreordenBloqueada(
                (int) $datos['id_preorden']
            );

            if ($preorden->estado !== 'aprobada') {
                throw new Exception(
                    'Solo se pueden registrar pagos para preórdenes aprobadas.'
                );
            }

            $idOrdenTrabajo = !empty($datos['id_ordentrabajo'])
                ? (int) $datos['id_ordentrabajo']
                : null;

            $this->validarOrdenTrabajo(
                $idOrdenTrabajo,
                (int) $preorden->id
            );

            $resumen = $this->calcularSaldoDisponible($preorden);

            if ($resumen['saldo'] <= 0) {
                throw new Exception(
                    'La preorden ya no tiene saldo pendiente.'
                );
            }

            if ((float) $datos['valor'] > $resumen['saldo']) {
                throw new Exception(
                    'El valor del pago supera el saldo pendiente.'
                );
            }

            $datos['id_ordentrabajo'] = $idOrdenTrabajo;

            return $this->pagoRepository->store($datos);
        });
    }

    public function edit(int $id)
    {
        return $this->pagoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {

            $pago = $this->pagoRepository->buscarPagoBloqueado($id);

            if (
                isset($datos['id_preorden'])
                && (int) $datos['id_preorden'] !== (int) $pago->id_preorden
            ) {
                throw new Exception(
                    'No se puede cambiar la preorden asociada a un pago.'
                );
            }

            $preorden = $this->pagoRepository->buscarPreordenBloqueada(
                (int) $pago->id_preorden
            );

            $idOrdenTrabajo = array_key_exists('id_ordentrabajo', $datos)
                ? (!empty($datos['id_ordentrabajo'])
                    ? (int) $datos['id_ordentrabajo']
                    : null)
                : $pago->id_ordentrabajo;

            $this->validarOrdenTrabajo(
                $idOrdenTrabajo,
                (int) $preorden->id
            );

            $estadoNuevo = $datos['estado'] ?? $pago->estado;

            if (
                $pago->estado === 'confirmado'
                && $estadoNuevo !== 'confirmado'
            ) {
                throw new Exception(
                    'No se puede cambiar el estado de un pago confirmado.'
                );
            }

            if (
                $pago->estado === 'confirmado'
                && array_key_exists('valor', $datos)
                && round((float) $datos['valor'], 2) !== round((float) $pago->valor, 2)
            ) {
                throw new Exception(
                    'No se puede modificar el valor de un pago confirmado.'
                );
            }

            $valorNuevo = (float) ($datos['valor'] ?? $pago->valor);

            $resumen = $this->calcularSaldoDisponible(
                $preorden,
                $id
            );

            if (
                $estadoNuevo === 'confirmado'
                && $valorNuevo > $resumen['saldo']
            ) {
                throw new Exception(
                    'El valor del pago supera el saldo pendiente.'
                );
            }

            $datos['id_ordentrabajo'] = $idOrdenTrabajo;

            // La preorden original no se modifica.
            unset($datos['id_preorden']);

            return $this->pagoRepository->update($pago, $datos);
        });
    }

    public function confirmar(int $id)
    {
        return DB::transaction(function () use ($id) {

            $pago = $this->pagoRepository->buscarPagoBloqueado($id);

            if ($pago->estado !== 'pendiente') {
                throw ValidationException::withMessages([
                    'pago' => 'Solo se pueden confirmar pagos pendientes.',
                ]);
            }

            $preorden = $this->pagoRepository->buscarPreordenBloqueada(
                (int) $pago->id_preorden
            );

            if ($preorden->estado !== 'aprobada') {
                throw ValidationException::withMessages([
                    'pago' => 'La preorden debe estar aprobada para confirmar el pago.',
                ]);
            }

            $resumen = $this->calcularSaldoDisponible($preorden);

            if (
                $resumen['saldo'] <= 0
                || (float) $pago->valor > $resumen['saldo']
            ) {
                throw ValidationException::withMessages([
                    'pago' => 'El pago supera el saldo pendiente o la preorden ya está saldada.',
                ]);
            }

            $pago->estado = 'confirmado';

            return $this->pagoRepository->guardar($pago);
        });
    }

    public function destroy(int $id)
    {
        return DB::transaction(function () use ($id) {

            $pago = $this->pagoRepository->buscarPagoBloqueado($id);

            if ($pago->estado === 'confirmado') {
                throw new Exception(
                    'No se puede eliminar un pago confirmado.'
                );
            }

            return $this->pagoRepository->eliminar($pago);
        });
    }

    public function resumenRecepciones()
    {
        return $this->pagoRepository->resumenRecepciones();
    }
}
