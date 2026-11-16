<?php

namespace App\Repository;

use App\Models\Inventario;
use App\Models\Moto;
use App\Models\Mecanico;
use Illuminate\Support\Facades\DB;

class InventarioRepository
{
    public function listartodo()
    {
        return Inventario::with([
            'moto.cliente',
            'moto.marca',
            'mecanico',
        ])->get();
    }

    public function listarMotos()
    {
        return Moto::with(['cliente', 'marca'])
            ->orderBy('placa')
            ->get();
    }

    public function listarMecanicos()
    {
        return Mecanico::orderBy('apellido')
            ->orderBy('nombre')
            ->get();
    }

    public function store(array $datos)
    {
        return DB::transaction(function () use ($datos) {

            // El folio siempre lo genera el sistema.
            unset($datos['recepcion']);

            $ultimoNumero = Inventario::query()
                ->max('recepcion');

            $datos['recepcion'] = ($ultimoNumero ?? 0) + 1;

            return Inventario::create($datos);

        }, 5);
    }

    public function edit(int $id)
    {
        return Inventario::findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $inventario = Inventario::findOrFail($id);

        // No permitir modificar el folio original.
        unset($datos['recepcion']);

        $inventario->update($datos);
    }

    public function destroy(int $id)
    {
        Inventario::destroy($id);
    }
}
