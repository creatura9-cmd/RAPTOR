<?php

namespace App\Service;

use App\Repository\InventarioRepository;

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

    public function store(array $datos)
    {
        $this->inventario_repository->store($datos);
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
        $this->inventario_repository->destroy($id);
    }
}
