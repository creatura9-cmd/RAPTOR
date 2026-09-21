<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoRequest;
use App\Models\Cliente;
use App\Models\MarcaMoto;
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
        $clientes = Cliente::orderBy('nombre')->get();
        $marcas = MarcaMoto::orderBy('nombre_marca')->get();

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

        $clientes = Cliente::orderBy('nombre')->get();
        $marcas = MarcaMoto::orderBy('nombre_marca')->get();

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
        $this->moto_service->destroy($id);

        return redirect()
            ->route('moto.index')
            ->with('destroy', 'Moto eliminada correctamente');
    }
}

