<?php

namespace App\Repository;

use App\Models\Moto;
use App\Models\Cliente;
use App\Models\MarcaMoto;
use Illuminate\Database\QueryException;

class MotoRepository
{
    public function listartodo()
    {
        return Moto::with(['cliente', 'marca'])->get();
    }

    public function listarClientes()
    {
        return Cliente::orderBy('nombre')->get();
    }

    public function listarMarcas()
    {
        return MarcaMoto::orderBy('nombre_marca')->get();
    }

    public function store(array $datos)
    {
        Moto::create($datos);
    }

    public function edit(int $id)
    {
        return Moto::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $moto = Moto::findOrFail($id);

        $moto->update($datos);
    }

    public function destroy(int $id): bool
    {
        $moto = Moto::findOrFail($id);

        try {
            $moto->delete();

            return true;
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'FOREIGN KEY constraint failed')) {
                return false;
            }

            throw $e;
        }
    }
}
