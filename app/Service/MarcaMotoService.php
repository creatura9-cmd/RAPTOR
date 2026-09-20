<?php

namespace App\Service;

use App\Repository\MarcaMotoRepository;

class MarcaMotoService
{
    private MarcaMotoRepository $marca_moto_repository;

    public function __construct(MarcaMotoRepository $marcamotorepository)
    {
        $this->marca_moto_repository = $marcamotorepository;
    }

    public function listartodo()
    {
        return $this->marca_moto_repository->listartodo();
    }

    public function store(array $datos)
    {
        $this->marca_moto_repository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->marca_moto_repository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->marca_moto_repository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->marca_moto_repository->destroy($id);
    }
}