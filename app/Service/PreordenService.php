<?php

namespace App\Service;

use App\Repository\PreordenRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PreordenService
{
    protected $preordenRepository;

    public function __construct(PreordenRepository $preordenRepository)
    {
        $this->preordenRepository = $preordenRepository;
    }

    public function listartodo()
    {
        return $this->preordenRepository->listartodo();
    }

    public function listarDiagnosticosDisponibles()
    {
        return $this->preordenRepository->listarDiagnosticosDisponibles();
    }

    public function prepararFormularioCreate(?int $idPreorden): array
    {
        return [
            'diagnosticos' => $this->preordenRepository->listarDiagnosticosDisponibles(),
            'preorden' => $idPreorden
                ? $this->preordenRepository->obtenerPreordenConRepuestos($idPreorden)
                : null,
            'repuestos' => $this->preordenRepository->listarRepuestos(),
            'idPreorden' => $idPreorden,
        ];
    }

    public function store(array $datos)
    {
        return DB::transaction(function () use ($datos) {

            $repuestos = $datos['repuestos'] ?? [];

            unset($datos['repuestos']);

            $totalRepuestos = 0;
            $repuestosPreparados = [];

            foreach ($repuestos as $linea) {

                $repuesto = $this->preordenRepository
                    ->buscarRepuesto((int) $linea['id_repuesto']);

                $cantidad = (int) $linea['cantidad'];
                $precioUnitario = (float) $repuesto->precio;

                $subtotal = round(
                    $precioUnitario * $cantidad,
                    2
                );

                $totalRepuestos += $subtotal;

                $repuestosPreparados[] = [
                    'id_repuesto' => $repuesto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ];
            }

            $datos['total'] = round(
                (float) $datos['mano_obra'] + $totalRepuestos,
                2
            );

            $preorden = $this->preordenRepository->store($datos);

            foreach ($repuestosPreparados as $linea) {
                $this->preordenRepository->storeRepuesto(
                    $preorden,
                    $linea
                );
            }

            return $preorden;
        });
    }

    public function edit(int $id)
    {
        return $this->preordenRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $preorden = $this->preordenRepository->edit($id);

        $totalRepuestos = $this->preordenRepository
            ->sumarSubtotalRepuestos($id);

        $datos['total'] = round(
            (float) $datos['mano_obra'] + $totalRepuestos,
            2
        );

        return $this->preordenRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        if ($this->preordenRepository->tieneRelaciones($id)) {
            throw ValidationException::withMessages([
                'error' => 'No se puede eliminar esta preorden porque está relacionada con otros registros y áreas del sistema.',
            ]);
        }

        return $this->preordenRepository->destroy($id);
    }

    public function aprobar(int $id)
    {
        $preorden = $this->preordenRepository->edit($id);

        if ($preorden->estado !== 'pendiente') {
            throw ValidationException::withMessages([
                'estado' => 'Solo se pueden aprobar preórdenes pendientes.',
            ]);
        }

        return $this->preordenRepository->aprobar($id);
    }

    public function rechazar(int $id)
    {
        $preorden = $this->preordenRepository->edit($id);

        if ($preorden->estado !== 'pendiente') {
            throw ValidationException::withMessages([
                'estado' => 'Solo se pueden rechazar preórdenes pendientes.',
            ]);
        }

        return $this->preordenRepository->rechazar($id);
    }
}
