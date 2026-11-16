<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntregaRequest;
use App\Service\EntregaService;

class EntregaController extends Controller
{
    protected $entregaService;

    public function __construct(EntregaService $entregaService)
    {
        $this->entregaService = $entregaService;
    }

    public function index()
    {
        $entregas = $this->entregaService->listartodo();

        return view('entrega.index', compact('entregas'));
    }

    public function create()
    {
        $ordenesDisponibles = $this->entregaService
            ->ordenesDisponiblesParaEntrega();

        return view('entrega.create', compact('ordenesDisponibles'));
    }

    public function store(EntregaRequest $request)
    {
        try {
            $this->entregaService->guardar($request->validated());

            return redirect()
                ->route('entrega.index')
                ->with('success', 'Entrega registrada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $entrega = $this->entregaService->buscarporid($id);

        return view('entrega.edit', compact('entrega'));
    }

    public function update(EntregaRequest $request, $id)
    {
        try {
            $this->entregaService->actualizar(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('entrega.index')
                ->with('update', 'Entrega actualizada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->entregaService->eliminar($id);

            return redirect()
                ->route('entrega.index')
                ->with('success', 'Entrega eliminada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }
}
