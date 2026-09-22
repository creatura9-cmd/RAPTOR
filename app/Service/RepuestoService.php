<?php

namespace App\Service;

use App\Repository\RepuestoRepository;

class RepuestoService
{
    protected $repuestoRepository;

    public function __construct(RepuestoRepository $repuestoRepository)
    {
        $this->repuestoRepository = $repuestoRepository;
    }

    public function listartodo()
    {
        return $this->repuestoRepository->listartodo();
    }

    public function store(array $datos)
    {
        $this->repuestoRepository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->repuestoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->repuestoRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->repuestoRepository->destroy($id);
    }
}