<?php

namespace App\Service;

use App\Repository\ClienteRepository;

class ClienteService
{
    private ClienteRepository $cliente_repository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->cliente_repository = $clienteRepository;
    }

    public function listartodo()
    {
        return $this->cliente_repository->listartodo();
    }

    public function store(array $datos)
    {
        $this->cliente_repository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->cliente_repository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->cliente_repository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->cliente_repository->destroy($id);
    }
}