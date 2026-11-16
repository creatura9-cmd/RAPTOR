<?php

namespace App\Service;

use App\Repository\PreordenRepuestoRepository;
use Illuminate\Support\Facades\DB;

class PreordenRepuestoService
{
    protected $preordenRepuestoRepository;

    public function __construct(
        PreordenRepuestoRepository $preordenRepuestoRepository
    ) {
        $this->preordenRepuestoRepository = $preordenRepuestoRepository;
    }

    public function listartodo()
    {
        return $this->preordenRepuestoRepository->listartodo();
    }

    public function listarPreordenes()
    {
        return $this->preordenRepuestoRepository->listarPreordenes();
    }

    public function listarRepuestos()
    {
        return $this->preordenRepuestoRepository->listarRepuestos();
    }

    public function store(array $datos)
    {
        return DB::transaction(function () use ($datos) {

            $repuesto = $this->preordenRepuestoRepository
                ->buscarRepuesto((int) $datos['id_repuesto']);

            $datos['precio_unitario'] = $repuesto->precio;

            $datos['subtotal'] = round(
                $datos['cantidad'] * $datos['precio_unitario'],
                2
            );

            $preordenRepuesto = $this->preordenRepuestoRepository
                ->store($datos);

            $this->actualizarTotalPreorden(
                (int) $datos['id_preorden']
            );

            return $preordenRepuesto;
        });
    }

    public function edit(int $id)
    {
        return $this->preordenRepuestoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        return DB::transaction(function () use ($id, $datos) {

            $preordenRepuesto = $this->preordenRepuestoRepository
                ->edit($id);

            $idPreorden = (int) $preordenRepuesto->id_preorden;

            $repuesto = $this->preordenRepuestoRepository
                ->buscarRepuesto((int) $datos['id_repuesto']);

            $datos['precio_unitario'] = $repuesto->precio;

            $datos['subtotal'] = round(
                $datos['cantidad'] * $datos['precio_unitario'],
                2
            );

            $preordenRepuesto = $this->preordenRepuestoRepository
                ->update($id, $datos);

            $this->actualizarTotalPreorden($idPreorden);

            return $preordenRepuesto;
        });
    }

    public function destroy(int $id)
    {
        return DB::transaction(function () use ($id) {

            $preordenRepuesto = $this->preordenRepuestoRepository
                ->edit($id);

            $idPreorden = (int) $preordenRepuesto->id_preorden;

            $resultado = $this->preordenRepuestoRepository
                ->destroy($id);

            $this->actualizarTotalPreorden($idPreorden);

            return $resultado;
        });
    }

    private function actualizarTotalPreorden(int $idPreorden)
    {
        $preorden = $this->preordenRepuestoRepository
            ->obtenerPreorden($idPreorden);

        $totalRepuestos = $this->preordenRepuestoRepository
            ->sumarSubtotalRepuestos($idPreorden);

        $total = round(
            (float) $preorden->mano_obra + (float) $totalRepuestos,
            2
        );

        $this->preordenRepuestoRepository
            ->actualizarTotalPreorden($idPreorden, $total);
    }
}
