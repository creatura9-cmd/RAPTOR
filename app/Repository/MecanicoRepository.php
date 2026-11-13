<?php

namespace App\Repository;

use App\Models\Mecanico;
use Illuminate\Validation\ValidationException;

class MecanicoRepository
{
    public function listartodo()
    {
        return Mecanico::all();
    }

    public function store(array $datos)
    {
        Mecanico::create($datos);
    }

    public function edit(int $id)
    {
        return Mecanico::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $mecanico = Mecanico::findOrFail($id);

        $mecanico->update($datos);

    }
    public function destroy(int $id)
    {
            $mecanico = Mecanico::findOrFail($id);

            if ($mecanico->ordenesTrabajo()->exists()) {
                throw ValidationException::withMessages([
                    'mecanico' => 'No se puede eliminar este mecánico porque tiene órdenes de trabajo relacionadas.',
                ]);
            }

           $mecanico->delete();
    }
}