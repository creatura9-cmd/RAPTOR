<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventarioRequest;
use App\Models\Moto;
use App\Service\InventarioService;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    private InventarioService $inventario_service;

    public function __construct(InventarioService $inventarioService)
    {
        $this->inventario_service = $inventarioService;
    }

    public function index()
    {
        $inventarios = $this->inventario_service->listartodo();

        return view('Inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $motos = Moto::with(['cliente', 'marca'])
            ->orderBy('placa')
            ->get();

        return view('Inventario.create', compact('motos'));
    }

    public function store(InventarioRequest $request)
    {
        $this->inventario_service->store($request->validated());

        return redirect()
            ->route('inventario.index')
            ->with('success', 'Inventario registrado correctamente');
    }

    public function edit(int $id)
    {
        $inventario = $this->inventario_service->edit($id);

        $motos = Moto::with(['cliente', 'marca'])
            ->orderBy('placa')
            ->get();

        return view('Inventario.update', compact('inventario', 'motos'));
    }

    public function update(int $id, InventarioRequest $request)
    {
        $this->inventario_service->update($id, $request->validated());

        return redirect()
            ->route('inventario.index')
            ->with('update', 'Inventario actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $this->inventario_service->destroy($id);

        return redirect()
            ->route('inventario.index')
            ->with('destroy', 'Inventario eliminado correctamente');
    }
}
