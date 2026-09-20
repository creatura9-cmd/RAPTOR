<?php

namespace App\Service;

use App\Repository\MotoRepository;

class MotoService
{
    private MotoRepository $moto_repository;

    public function __construct(MotoRepository $motoRepository)
    {
        $this->moto_repository = $motoRepository;
    }

    public function listartodo()
    {
        return $this->moto_repository->listartodo();
    }

    public function store(array $datos)
    {
        $this->moto_repository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->moto_repository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->moto_repository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->moto_repository->destroy($id);
    }
}
