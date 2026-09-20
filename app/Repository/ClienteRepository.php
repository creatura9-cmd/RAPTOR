<?php

namespace App\Repository;

use App\Models\Cliente;

class ClienteRepository
{
    public function listartodo()
    {
        return Cliente::all();
    }

    public function store(array $datos)
    {
        Cliente::create($datos);
    }

    public function edit(int $id)
    {
        return Cliente::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update($datos);
    }

    public function destroy(int $id)
    {
        Cliente::destroy($id);
    }
}