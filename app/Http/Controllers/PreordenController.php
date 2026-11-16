<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreordenRequest;
use App\Models\Diagnostico;
use App\Models\Preorden;
use App\Service\PreordenService;

class PreordenController extends Controller
{
    protected $preordenService;

    public function __construct(PreordenService $preordenService)
    {
        $this->preordenService = $preordenService;
    }

    public function index()
    {
        $preordenes = $this->preordenService->listartodo();

        return view('preorden.index', compact('preordenes'));
    }

    public function create()
    {
        $diagnosticos = Diagnostico::with('inventario.moto')
            ->where('estado', 'finalizado')
            ->whereDoesntHave('preordenes')
            ->get();

        $idPreorden = request()->query('id_preorden');

        $preorden = $idPreorden
            ? Preorden::with('repuestos.repuesto')->findOrFail($idPreorden)
            : null;

        $repuestos = \App\Models\Repuesto::all();

        return view(
            'preorden.create',
            compact('diagnosticos', 'preorden', 'repuestos', 'idPreorden')
        );
    }

    public function store(PreordenRequest $request)
    {
        $datos = $request->validated();

        $this->preordenService->store($datos);

        return redirect()
            ->route('preorden.index')
            ->with('success', 'Preorden registrada correctamente.');
    }

    public function edit(int $id)
    {
        $preorden = $this->preordenService->edit($id);

        $diagnosticos = Diagnostico::with('inventario.moto')
            ->where('estado', 'finalizado')
            ->get();

        return view(
            'preorden.update',
            compact('preorden', 'diagnosticos')
        );
    }

    public function update(PreordenRequest $request, int $id)
    {
        $datos = $request->validated();

        $this->preordenService->update($id, $datos);

        return redirect()
            ->route('preorden.index')
            ->with('update', 'Preorden actualizada correctamente');
    }

    public function destroy(int $id)
    {
        $this->preordenService->destroy($id);

        return redirect()
            ->route('preorden.index')
            ->with('destroy', 'Preorden eliminada correctamente');
    }

    public function aprobar(int $id)
    {
        $this->preordenService->aprobar($id);

        return redirect()
            ->route('preorden.index')
            ->with('update', 'Preorden aprobada correctamente.');
    }

    public function rechazar(int $id)
    {
        $this->preordenService->rechazar($id);

        return redirect()
            ->route('preorden.index')
            ->with('update', 'Preorden rechazada correctamente.');
    }
}
