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

    public function listarMotosDisponibles()
    {
        return Moto::with(['cliente', 'marca'])
            ->whereDoesntHave('inventarios', function ($query) {

                // Revisar únicamente la última recepción de la moto.
                $query->whereRaw(
                    'inventario.id = (
                        SELECT MAX(ultimo.id)
                        FROM inventario AS ultimo
                        WHERE ultimo.id_moto = inventario.id_moto
                    )'
                );

                // La última recepción debe tener una Orden de Trabajo
                // con una Entrega registrada para considerarse cerrada.
                $query->whereDoesntHave('ordenTrabajo', function ($orden) {
                    $orden->whereHas('entrega');
                });

            })
            ->orderBy('placa')
            ->get();
    }

    public function motoPuedeIngresar(int $idMoto): bool
    {
        return Moto::whereKey($idMoto)
            ->whereDoesntHave('inventarios', function ($query) {

                $query->whereRaw(
                    'inventario.id = (
                        SELECT MAX(ultimo.id)
                        FROM inventario AS ultimo
                        WHERE ultimo.id_moto = inventario.id_moto
                    )'
                );

                $query->whereDoesntHave('ordenTrabajo', function ($orden) {
                    $orden->whereHas('entrega');
                });

            })
            ->exists();
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

    public function tieneRelaciones(int $id): bool
    {
        $inventario = Inventario::findOrFail($id);

        return $inventario->diagnosticos()->exists()
            || $inventario->ordenTrabajo()->exists();
    }

    public function destroy(int $id)
    {
        $inventario = Inventario::findOrFail($id);

        return $inventario->delete();
    }
}
