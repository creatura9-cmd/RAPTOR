<?php

namespace App\Service;

use App\Repository\InventarioRepository;
use Illuminate\Validation\ValidationException;

class InventarioService
{
    private InventarioRepository $inventario_repository;

    public function __construct(InventarioRepository $inventarioRepository)
    {
        $this->inventario_repository = $inventarioRepository;
    }

    public function listartodo()
    {
        return $this->inventario_repository->listartodo();
    }

    public function listarMotos()
    {
        return $this->inventario_repository->listarMotos();
    }

    public function listarMotosDisponibles()
    {
        return $this->inventario_repository->listarMotosDisponibles();
    }

    public function listarMecanicos()
    {
        return $this->inventario_repository->listarMecanicos();
    }

    public function store(array $datos)
    {
        $idMoto = (int) $datos['id_moto'];

        if (!$this->inventario_repository->motoPuedeIngresar($idMoto)) {
            throw ValidationException::withMessages([
                'id_moto' => 'Esta motocicleta todavía tiene una recepción pendiente de entrega.',
            ]);
        }

        return $this->inventario_repository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->inventario_repository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->inventario_repository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        if ($this->inventario_repository->tieneRelaciones($id)) {
            throw ValidationException::withMessages([
                'error' => 'No se puede eliminar este inventario porque tiene diagnósticos u órdenes de trabajo relacionados.',
            ]);
        }

        return $this->inventario_repository->destroy($id);
    }
}
