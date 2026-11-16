<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreordenRepuestoRequest;
use App\Models\Preorden;
use App\Models\Repuesto;
use App\Service\PreordenRepuestoService;

class PreordenRepuestoController extends Controller
{
    protected $preordenRepuestoService;

    public function __construct(
        PreordenRepuestoService $preordenRepuestoService
    ) {
        $this->preordenRepuestoService = $preordenRepuestoService;
    }

    public function index()
    {
        $preordenRepuestos = $this->preordenRepuestoService->listartodo();

        return view(
            'preorden_repuesto.index',
            compact('preordenRepuestos')
        );
    }

    public function create()
    {
        $preordenes = Preorden::all();
        $repuestos = Repuesto::all();
        $idPreorden = request()->query('id_preorden');
        return view(
            'preorden_repuesto.create',
            compact('preordenes', 'repuestos', 'idPreorden')
        );
    }

    public function store(PreordenRepuestoRequest $request)
    {
        $datos = $request->validated();

        $this->preordenRepuestoService->store($datos);

        if ($request->input('return_to') === 'preorden.create') {
         return redirect()
            ->route('preorden.create', [
                'id_preorden' => $datos['id_preorden'],
            ])
            ->with('success', 'Repuesto agregado correctamente.');
    }

        return redirect()
            ->route('preorden.index')
            ->with('success', 'Repuesto agregado a la preorden correctamente');
    }

    public function edit(int $id)
    {
        $preordenRepuesto = $this->preordenRepuestoService->edit($id);

        $preordenes = Preorden::all();
        $repuestos = Repuesto::all();

        return view(
            'preorden_repuesto.update',
            compact(
                'preordenRepuesto',
                'preordenes',
                'repuestos'
            )
        );
    }

    public function update(
        PreordenRepuestoRequest $request,
        int $id
    ) {
        $datos = $request->validated();

        $this->preordenRepuestoService->update($id, $datos);

        return redirect()
            ->route('preorden_repuesto.index')
            ->with('update', 'Repuesto de la preorden actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $this->preordenRepuestoService->destroy($id);

        return redirect()
            ->route('preorden_repuesto.index')
            ->with('destroy', 'Repuesto eliminado de la preorden correctamente');
    }
}
