<?php

namespace App\Service;

use App\Repository\MecanicoRepository;

class MecanicoService
{
    protected $mecanicoRepository;

    public function __construct(MecanicoRepository $mecanicoRepository)
    {
        $this->mecanicoRepository = $mecanicoRepository;
    }

    public function listartodo()
    {
        return $this->mecanicoRepository->listartodo();
    }

    public function store(array $datos)
    {
        $this->mecanicoRepository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->mecanicoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->mecanicoRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->mecanicoRepository->destroy($id);
    }
}