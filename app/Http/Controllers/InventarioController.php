<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventarioRequest;
use App\Service\InventarioService;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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
        $motos = $this->inventario_service->listarMotosDisponibles();
        $mecanicos = $this->inventario_service->listarMecanicos();

        return view('Inventario.create', compact('motos', 'mecanicos'));
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

        $motos = $this->inventario_service->listarMotos();
        $mecanicos = $this->inventario_service->listarMecanicos();

        return view(
            'Inventario.update',
            compact('inventario', 'motos', 'mecanicos')
        );
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
        try {
            $this->inventario_service->destroy($id);

            return redirect()
                ->route('inventario.index')
                ->with('destroy', 'Inventario eliminado correctamente');

        } catch (ValidationException $e) {

            return redirect()
                ->route('inventario.index')
                ->with(
                    'error',
                    $e->errors()['error'][0] ?? 'No se puede eliminar este inventario.'
                );

        } catch (QueryException $e) {

            if ($e->getCode() !== '23000') {
                throw $e;
            }

            return redirect()
                ->route('inventario.index')
                ->with(
                    'error',
                    'No se puede eliminar este inventario porque tiene registros relacionados en el sistema.'
                );
        }
    }
}
