<?php

namespace App\Service;

use App\Repository\DiagnosticoRepository;

class DiagnosticoService
{
    protected $diagnosticoRepository;

    public function __construct(DiagnosticoRepository $diagnosticoRepository)
    {
        $this->diagnosticoRepository = $diagnosticoRepository;
    }

    public function listartodo()
    {
        return $this->diagnosticoRepository->listartodo();
    }

    public function store(array $datos)
    {
        return $this->diagnosticoRepository->store($datos);
    }

    public function edit(int $id)
    {
        return $this->diagnosticoRepository->edit($id);
    }

    public function update(int $id, array $datos)
    {
        $this->diagnosticoRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        $this->diagnosticoRepository->destroy($id);
    }
}