<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Service\ClienteService;
use Illuminate\Database\QueryException;

class ClienteController extends Controller
{
    private ClienteService $cliente_service;

    public function __construct(ClienteService $clienteService)
    {
        $this->cliente_service = $clienteService;
    }

    public function index()
    {
        $clientes = $this->cliente_service->listartodo();

        return view('Cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('Cliente.create');
    }

    public function store(ClienteRequest $request)
    {
        $this->cliente_service->store($request->validated());

        return redirect()
            ->route('cliente.index')
            ->with('success', 'Cliente creado correctamente');
    }

    public function edit(int $id)
    {
        $cliente = $this->cliente_service->edit($id);

        return view('Cliente.update', compact('cliente'));
    }

    public function update(int $id, ClienteRequest $request)
    {
        $this->cliente_service->update($id, $request->validated());

        return redirect()
            ->route('cliente.index')
            ->with('update', 'Cliente actualizado correctamente');
    }

    
    public function habilitarPortal(int $id)
    {
        $resultado = $this->cliente_service->habilitarPortal($id);

        if (isset($resultado['error'])) {
            return redirect()
                ->route('cliente.index')
                ->with('error', $resultado['error']);
        }

        return redirect()
            ->route('cliente.index')
            ->with('success', $resultado['success'])
            ->with('enlace_invitacion', $resultado['enlace']);
    }

    public function reenviarInvitacion(int $id)
    {
        $resultado = $this->cliente_service->reenviarInvitacion($id);

        if (isset($resultado['error'])) {
            return redirect()
                ->route('cliente.index')
                ->with('error', $resultado['error']);
        }

        return redirect()
            ->route('cliente.index')
            ->with('success', $resultado['success'])
            ->with('enlace_invitacion', $resultado['enlace']);
    }


    public function destroy(int $id)
    {
        try {
            $this->cliente_service->destroy($id);

            return redirect()
                ->route('cliente.index')
                ->with('destroy', 'Cliente eliminado correctamente');

        } catch (QueryException $e) {

            // Solo manejar errores de integridad referencial.
            if ($e->getCode() !== '23000') {
                throw $e;
            }

            return redirect()
                ->route('cliente.index')
                ->with(
                    'error',
                    'No se puede eliminar este cliente porque tiene registros relacionados en el sistema.'
                );
        }
    }
}
