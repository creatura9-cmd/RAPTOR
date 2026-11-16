<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoRequest;
use App\Service\MotoService;

class MotoController extends Controller
{
    private MotoService $moto_service;

    public function __construct(MotoService $motoService)
    {
        $this->moto_service = $motoService;
    }

    public function index()
    {
        $motos = $this->moto_service->listartodo();

        return view('Moto.index', compact('motos'));
    }

    public function create()
    {
        $clientes = $this->moto_service->listarClientes();
        $marcas = $this->moto_service->listarMarcas();

        return view('Moto.create', compact('clientes', 'marcas'));
    }

    public function store(MotoRequest $request)
    {
        $this->moto_service->store($request->validated());

        return redirect()
            ->route('moto.index')
            ->with('success', 'Moto creada correctamente');
    }

    public function edit(int $id)
    {
        $moto = $this->moto_service->edit($id);

        $clientes = $this->moto_service->listarClientes();
        $marcas = $this->moto_service->listarMarcas();

        return view('Moto.update', compact('moto', 'clientes', 'marcas'));
    }

    public function update(MotoRequest $request, int $id)
    {
        $this->moto_service->update($id, $request->validated());

        return redirect()
            ->route('moto.index')
            ->with('update', 'Moto actualizada correctamente');
    }

    public function destroy(int $id)
    {
        $eliminada = $this->moto_service->destroy($id);

        if (! $eliminada) {
            return redirect()
                ->route('moto.index')
                ->with('error', 'No se puede eliminar esta motocicleta porque tiene registros relacionados en RAPTOR.');
        }

        return redirect()
            ->route('moto.index')
            ->with('destroy', 'Moto eliminada correctamente.');
    }
}
