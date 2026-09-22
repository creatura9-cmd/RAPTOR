<?php

namespace App\Http\Controllers;

use App\Http\Requests\MecanicoRequest;
use App\Service\MecanicoService;

class MecanicoController extends Controller
{
    protected $mecanicoService;

    public function __construct(MecanicoService $mecanicoService)
    {
        $this->mecanicoService = $mecanicoService;
    }

    public function index()
    {
        $mecanicos = $this->mecanicoService->listartodo();

        return view('mecanico.index', compact('mecanicos'));
    }

    public function create()
    {
        return view('mecanico.create');
    }

    public function store(MecanicoRequest $request)
    {
        $datos = $request->validated();

        $this->mecanicoService->store($datos);

        return redirect()
            ->route('mecanico.index')
            ->with('success', 'Mecánico registrado correctamente');
    }

    public function edit(int $id)
    {
        $mecanico = $this->mecanicoService->edit($id);

        return view('mecanico.update', compact('mecanico'));
    }

    public function update(MecanicoRequest $request, int $id)
    {
        $datos = $request->validated();

        $this->mecanicoService->update($id, $datos);

        return redirect()
            ->route('mecanico.index')
            ->with('update', 'Mecánico actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $this->mecanicoService->destroy($id);

        return redirect()
            ->route('mecanico.index')
            ->with('destroy', 'Mecánico eliminado correctamente');
    }
}
