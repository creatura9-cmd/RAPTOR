<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaMotoRequest;
use App\Service\MarcaMotoService;
use Illuminate\Http\Request;

class MarcaMotoController extends Controller
{
    private MarcaMotoService $marca_moto_service;

    public function __construct(MarcaMotoService $marcaMotoService)
    {
        $this->marca_moto_service = $marcaMotoService;
    }

    public function index()
    {
        $marcas = $this->marca_moto_service->listartodo();
        
        return view('MarcaMoto.index', compact('marcas'));
    }

    public function create()
    {
        return view('MarcaMoto.create');
    }

    public function store(MarcaMotoRequest $request)
    {
        $this->marca_moto_service->store($request->validated());

        return redirect()
            ->route('marca_moto.index')
            ->with('success', 'Marca creada correctamente');
    }

    public function edit(int $id)
    {
        $marca = $this->marca_moto_service->edit($id);

        return view('MarcaMoto.update', compact('marca'));
    }

    public function update(int $id, Request $request)
    {
        $this->marca_moto_service->update($id, $request->all());

        return redirect()
            ->route('marca_moto.index')
            ->with('update', 'Marca actualizada correctamente');
    }

    public function destroy(int $id)
    {
        $this->marca_moto_service->destroy($id);

        return redirect()
            ->route('marca_moto.index')
            ->with('destroy', 'Marca eliminada correctamente');
    }
}