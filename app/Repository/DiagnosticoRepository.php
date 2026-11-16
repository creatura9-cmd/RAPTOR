<?php

namespace App\Repository;

use App\Models\Diagnostico;
use App\Models\Inventario;
use App\Models\Mecanico;
use Illuminate\Database\Eloquent\Collection;

class DiagnosticoRepository
{
    /**
     * Obtener todos los diagnósticos con sus relaciones.
     */
    public function listartodo(): Collection
    {
        return Diagnostico::with([
            'inventario.moto.cliente',
            'inventario.moto.marca',
            'mecanico',
        ])->get();
    }

    /**
     * Obtener inventarios finalizados sin diagnóstico.
     */
    public function inventariosDisponibles(): Collection
    {
        return Inventario::with('moto')
            ->where('estado_inventario', 'finalizado')
            ->whereDoesntHave('diagnosticos')
            ->get();
    }

    /**
     * Obtener todos los mecánicos.
     */
    public function mecanicos(): Collection
    {
        return Mecanico::all();
    }

    /**
     * Obtener un inventario por su ID.
     */
    public function inventarioPorId(int $idInventario): Collection
    {
        return Inventario::with('moto')
            ->where('id', $idInventario)
            ->get();
    }

    /**
     * Registrar un diagnóstico.
     */
    public function store(array $datos): Diagnostico
    {
        return Diagnostico::create($datos);
    }

    /**
     * Buscar un diagnóstico por su ID.
     */
    public function edit(int $id): Diagnostico
    {
        return Diagnostico::findOrFail($id);
    }

    /**
     * Actualizar un diagnóstico.
     */
    public function update(int $id, array $datos): Diagnostico
    {
        $diagnostico = Diagnostico::findOrFail($id);

        $diagnostico->update($datos);

        return $diagnostico;
    }

    /**
     * Eliminar un diagnóstico.
     */
    public function destroy(int $id): int
    {
        return Diagnostico::destroy($id);
    }
}
