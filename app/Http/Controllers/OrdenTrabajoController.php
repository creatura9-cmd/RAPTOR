<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdenTrabajoRequest;
use App\Service\OrdenTrabajoService;

class OrdenTrabajoController extends Controller
{
    protected OrdenTrabajoService $ordenTrabajoService;

    public function __construct(OrdenTrabajoService $ordenTrabajoService)
    {
        $this->ordenTrabajoService = $ordenTrabajoService;
    }

    public function index()
    {
        $ordenesTrabajo = $this->ordenTrabajoService->listartodo();

        return view('orden_trabajo.index', compact('ordenesTrabajo'));
    }

    public function create()
    {
        $preordenes = $this->ordenTrabajoService
            ->listarPreordenesDisponibles();

        $mecanicos = $this->ordenTrabajoService
            ->listarMecanicos();

        return view('orden_trabajo.create', compact(
            'preordenes',
            'mecanicos'
        ));
    }

    public function store(OrdenTrabajoRequest $request)
    {
        $datos = $request->validated();

        $this->ordenTrabajoService->store($datos);

        return redirect()
            ->route('orden_trabajo.index')
            ->with('success', 'Orden de trabajo registrada correctamente');
    }

    public function edit(int $id)
    {
        $ordenTrabajo = $this->ordenTrabajoService->edit($id);

        $mecanicos = $this->ordenTrabajoService
            ->listarMecanicos();

        return view(
            'orden_trabajo.update',
            compact('ordenTrabajo', 'mecanicos')
        );
    }

    public function update(OrdenTrabajoRequest $request, int $id)
    {
        $datos = $request->validated();

        $this->ordenTrabajoService->update($id, $datos);

        return redirect()
            ->route('orden_trabajo.index')
            ->with('update', 'Orden de trabajo actualizada correctamente');
    }

    public function destroy(int $id)
    {
        $this->ordenTrabajoService->destroy($id);

        return redirect()
            ->route('orden_trabajo.index')
            ->with('destroy', 'Orden de trabajo eliminada correctamente');
    }
}
