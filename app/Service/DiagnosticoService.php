<?php

namespace App\Service;

use App\Repository\DiagnosticoRepository;

class DiagnosticoService
{
    protected DiagnosticoRepository $diagnosticoRepository;

    public function __construct(DiagnosticoRepository $diagnosticoRepository)
    {
        $this->diagnosticoRepository = $diagnosticoRepository;
    }

    public function listartodo()
    {
        return $this->diagnosticoRepository->listartodo();
    }

    public function inventariosDisponibles()
    {
        return $this->diagnosticoRepository->inventariosDisponibles();
    }

    public function inventarioPorId(int $idInventario)
    {
        return $this->diagnosticoRepository->inventarioPorId($idInventario);
    }

    public function mecanicos()
    {
        return $this->diagnosticoRepository->mecanicos();
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
        return $this->diagnosticoRepository->update($id, $datos);
    }

    public function destroy(int $id)
    {
        return $this->diagnosticoRepository->destroy($id);
    }
}