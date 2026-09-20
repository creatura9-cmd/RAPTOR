<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Service\ClienteService;
use Illuminate\Http\Request;

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

    public function update(int $id, Request $request)
    {
        $this->cliente_service->update($id, $request->all());

        return redirect()
            ->route('cliente.index')
            ->with('update', 'Cliente actualizado correctamente');
    }

    public function destroy(int $id)
    {
        $this->cliente_service->destroy($id);

        return redirect()
            ->route('cliente.index')
            ->with('destroy', 'Cliente eliminado correctamente');
    }
}
