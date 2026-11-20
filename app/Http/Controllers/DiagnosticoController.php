<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticoRequest;
use App\Service\DiagnosticoService;

class DiagnosticoController extends Controller
{
    protected $diagnosticoService;

    public function __construct(DiagnosticoService $diagnosticoService)
    {
        $this->diagnosticoService = $diagnosticoService;
    }

    public function index()
    {
        $diagnosticos = $this->diagnosticoService->listartodo();

        return view('diagnostico.index', compact('diagnosticos'));
    }

    public function create()
    {
        $inventarios = $this->diagnosticoService->inventariosDisponibles();
        $mecanicos = $this->diagnosticoService->mecanicos();

        return view('diagnostico.create', compact('inventarios', 'mecanicos'));
    }

    public function store(DiagnosticoRequest $request)
    {
        $datos = $request->validated();

        $this->diagnosticoService->store($datos);

        return redirect()
            ->route('diagnostico.index')
            ->with('success', 'Diagnóstico registrado correctamente');
    }

    public function edit(int $id)
    {
        $diagnostico = $this->diagnosticoService->edit($id);

        $inventarios = $this->diagnosticoService->inventarioPorId(
            $diagnostico->id_inventario
        );

        $mecanicos = $this->diagnosticoService->mecanicos();

        return view(
            'diagnostico.update',
            compact('diagnostico', 'inventarios', 'mecanicos')
        );
    }

    public function update(DiagnosticoRequest $request, int $id)
    {
        $datos = $request->validated();

        $this->diagnosticoService->update($id, $datos);

        return redirect()
            ->route('diagnostico.index')
            ->with('update', 'Diagnóstico actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $this->diagnosticoService->destroy($id);

        return redirect()
            ->route('diagnostico.index')
            ->with('error', 'No se puede eliminar este diagnóstico porque está relacionado con otros registros y áreas del sistema.');
    }
}
