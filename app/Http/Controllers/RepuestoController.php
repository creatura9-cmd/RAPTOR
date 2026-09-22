<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepuestoRequest;
use App\Service\RepuestoService;

class RepuestoController extends Controller
{
    protected $repuestoService;

    public function __construct(RepuestoService $repuestoService)
    {
        $this->repuestoService = $repuestoService;
    }

    public function index()
    {
        $repuestos = $this->repuestoService->listartodo();

        return view('repuesto.index', compact('repuestos'));
    }

    public function create()
    {
        return view('repuesto.create');
    }

    public function store(RepuestoRequest $request)
    {
        $datos = $request->validated();

        $this->repuestoService->store($datos);

        return redirect()
            ->route('repuesto.index')
            ->with('success', 'Repuesto registrado correctamente');
    }

    public function edit(int $id)
    {
        $repuesto = $this->repuestoService->edit($id);

        return view('repuesto.Update', compact('repuesto'));
    }

    public function update(RepuestoRequest $request, int $id)
    {
        $datos = $request->validated();

        $this->repuestoService->update($id, $datos);

        return redirect()
            ->route('repuesto.index')
            ->with('update', 'Repuesto actualizado correctamente');
    }

    public function destroy(int $id)
    {
        
        $this->repuestoService->destroy($id);

        return redirect()
            ->route('repuesto.index')
            ->with('destroy', 'Repuesto eliminado correctamente');
    }
}
