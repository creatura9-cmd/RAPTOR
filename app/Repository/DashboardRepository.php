<?php

namespace App\Repository;

use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardRepository
{
    public function contar(string $tabla): int
    {
        return Schema::hasTable($tabla)
            ? DB::table($tabla)->count()
            : 0;
    }

    public function obtenerPagosConfirmados(): float
    {
        if (
            ! Schema::hasTable('pago')
            || ! Schema::hasColumn('pago', 'valor')
            || ! Schema::hasColumn('pago', 'estado')
        ) {
            return 0;
        }

        return (float) DB::table('pago')
            ->where('estado', 'confirmado')
            ->sum('valor');
    }

    public function obtenerOrdenesPorEstado(): array
    {
        $estados = [
            'abierta' => 0,
            'en_proceso' => 0,
            'finalizada' => 0,
        ];

        $resultados = DB::table('orden_trabajo')
            ->select('estado', DB::raw('COUNT(*) as cantidad'))
            ->whereIn('estado', array_keys($estados))
            ->groupBy('estado')
            ->get();

        foreach ($resultados as $resultado) {
            $estados[$resultado->estado] = (int) $resultado->cantidad;
        }

        return $estados;
    }

    public function obtenerDiagnosticosPorEstado(): array
    {
        $estados = [
            'en_proceso' => 0,
            'finalizado' => 0,
            'cancelado' => 0,
        ];

        if (
            ! Schema::hasTable('diagnostico')
            || ! Schema::hasColumn('diagnostico', 'estado')
        ) {
            return $estados;
        }

        $resultados = DB::table('diagnostico')
            ->select('estado', DB::raw('COUNT(*) as cantidad'))
            ->whereIn('estado', array_keys($estados))
            ->groupBy('estado')
            ->get();

        foreach ($resultados as $resultado) {
            $estados[$resultado->estado] = (int) $resultado->cantidad;
        }

        return $estados;
    }

    public function obtenerPreordenesPorEstado(): array
    {
        $conteo = DB::table('preorden')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->mapWithKeys(fn ($total, $estado) => [
                mb_strtolower(trim($estado)) => $total,
            ]);

        return [
            'aceptada' => ($conteo['aceptada'] ?? 0)
                + ($conteo['aprobada'] ?? 0),

            'en_espera' => ($conteo['en_espera'] ?? 0)
                + ($conteo['en espera'] ?? 0)
                + ($conteo['pendiente'] ?? 0),

            'rechazada' => ($conteo['rechazada'] ?? 0)
                + ($conteo['desaprobada'] ?? 0),
        ];
    }

    public function obtenerRecepciones()
    {
        $tablas = [
            'inventario',
            'moto',
            'marca_moto',
            'cliente',
            'mecanicos',
        ];

        foreach ($tablas as $tabla) {
            if (! Schema::hasTable($tabla)) {
                return collect();
            }
        }

        $columnas = [
            'inventario' => [
                'id',
                'recepcion',
                'id_moto',
                'id_mecanico',
                'fecha_registro',
                'condicion_general',
                'estado_inventario',
                'descripcion',
            ],
            'moto' => [
                'id',
                'id_marca',
                'id_cliente',
                'modelo',
                'anio',
                'placa',
            ],
            'marca_moto' => [
                'id',
                'nombre_marca',
            ],
            'cliente' => [
                'id',
                'nombre',
                'apellido',
                'tipo_documento',
                'numero_documento',
                'telefono',
            ],
            'mecanicos' => [
                'id_mecanico',
                'nombre',
                'apellido',
            ],
        ];

        foreach ($columnas as $tabla => $lista) {
            foreach ($lista as $columna) {
                if (! Schema::hasColumn($tabla, $columna)) {
                    return collect();
                }
            }
        }

        return DB::table('inventario')
            ->join('moto', 'inventario.id_moto', '=', 'moto.id')
            ->join(
                'marca_moto',
                'moto.id_marca',
                '=',
                'marca_moto.id'
            )
            ->join(
                'cliente',
                'moto.id_cliente',
                '=',
                'cliente.id'
            )
            ->leftJoin(
                'mecanicos',
                'inventario.id_mecanico',
                '=',
                'mecanicos.id_mecanico'
            )
            ->select([
                'inventario.id as id_inventario',
                'inventario.recepcion as recepcion',
                'inventario.fecha_registro',
                'inventario.condicion_general',
                'inventario.estado_inventario',
                'inventario.descripcion',
                'moto.placa',
                'moto.modelo',
                'moto.anio',
                'marca_moto.nombre_marca',
                'cliente.nombre as cliente_nombre',
                'cliente.apellido as cliente_apellido',
                'cliente.tipo_documento',
                'cliente.numero_documento',
                'cliente.telefono',
                'mecanicos.nombre as mecanico_nombre',
                'mecanicos.apellido as mecanico_apellido',
            ])
            ->orderByDesc('inventario.fecha_registro')
            ->orderByDesc('inventario.id')
            ->get();
    }

    public function obtenerEstadisticas(): array
    {
        return [
            'clientes' => $this->contar('cliente'),
            'motocicletas' => $this->contar('moto'),
            'recepciones' => $this->contar('inventario'),
            'diagnosticos' => $this->contar('diagnostico'),
            'preordenes' => $this->contar('preorden'),
            'ordenes' => $this->contar('orden_trabajo'),
            'mecanicos' => $this->contar('mecanicos'),
            'repuestos' => $this->contar('repuesto'),
            'marcas' => $this->contar('marca_moto'),
            'pagos' => $this->contar('pago'),
            'entregas' => $this->contar('entrega'),
            'pagos_confirmados' => $this->obtenerPagosConfirmados(),
        ];
    }

    public function obtenerSeguimientos()
    {
        $recepciones = $this->obtenerRecepciones();

        return $recepciones->map(function ($recepcion) {
            // Buscar el diagnóstico de esta recepción.
            $diagnostico = DB::table('diagnostico')
                ->where('id_inventario', $recepcion->id_inventario)
                ->orderByDesc('id')
                ->first();

            // Buscar la preorden del diagnóstico.
            $preorden = null;

            if ($diagnostico !== null) {
                $preorden = DB::table('preorden')
                    ->where('id_diagnostico', $diagnostico->id)
                    ->orderByDesc('id')
                    ->first();
            }

            // Buscar la orden de trabajo de esta recepción.
            $orden = DB::table('orden_trabajo')
                ->where('id_inventario', $recepcion->id_inventario)
                ->orderByDesc('id')
                ->first();

            // Si existe una orden, priorizar su preorden vinculada.
            if ($orden !== null && $orden->id_preorden !== null) {
                $preorden = DB::table('preorden')
                    ->where('id', $orden->id_preorden)
                    ->first();
            }

            // Sumar únicamente los pagos confirmados.
            $pagado = 0.0;

            if ($orden !== null) {
                $pagado = (float) DB::table('pago')
                    ->where('id_ordentrabajo', $orden->id)
                    ->where('estado', 'confirmado')
                    ->sum('valor');
            }

            // Comprobar si existe una entrega.
            $entrega = null;

            if ($orden !== null) {
                $entrega = DB::table('entrega')
                    ->where('id_ordentrabajo', $orden->id)
                    ->first();
            }

            $total = (
                $preorden !== null
                && $preorden->estado === 'aprobada'
            ) ? (float) $preorden->total : 0.0;

            $saldo = max(0, $total - $pagado);

            // Determinar la etapa actual.
            if ($entrega !== null) {
                $etapa = 'Entregada';
            } elseif ($orden !== null && $orden->estado === 'finalizada') {
                $etapa = $saldo > 0
                    ? 'Pendiente de pago'
                    : 'Pendiente de entrega';
            } elseif ($orden !== null && $orden->estado === 'en_proceso') {
                $etapa = 'En reparación';
            } elseif ($orden !== null && $orden->estado === 'abierta') {
                $etapa = 'Orden de trabajo abierta';
            } elseif ($preorden !== null && $preorden->estado === 'aprobada') {
                $etapa = 'Preorden aprobada';
            } elseif ($preorden !== null && $preorden->estado === 'rechazada') {
                $etapa = 'Preorden rechazada';
            } elseif ($preorden !== null) {
                $etapa = 'Pendiente de aprobación';
            } elseif ($diagnostico !== null) {
                $etapa = match ($diagnostico->estado) {
                    'en_proceso' => 'Diagnóstico en proceso',
                    'finalizado' => 'Diagnóstico finalizado',
                    'cancelado' => 'Diagnóstico cancelado',
                    default => 'Pendiente de diagnóstico',
                };
            } else {
                $etapa = 'Recepción registrada';
            }

            $recepcion->diagnostico = $diagnostico;
            $recepcion->preorden = $preorden;
            $recepcion->orden = $orden;
            $recepcion->entrega = $entrega;
            $recepcion->total_aprobado = $total;
            $recepcion->pagado = $pagado;
            $recepcion->saldo = $saldo;
            $recepcion->etapa = $etapa;

            return $recepcion;
        });
    }

    public function obtenerActividadesRecientes()
    {
        $actividades = collect();

        /*
        |--------------------------------------------------------------------------
        | Recepciones registradas
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('inventario', 'fecha_registro')
            && Schema::hasColumn('inventario', 'id_moto')
        ) {
            $consulta = DB::table('inventario')
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'inventario.id',
                    'inventario.recepcion as folio_recepcion',
                    'inventario.fecha_registro',
                    'inventario.created_at',
                    'inventario.updated_at',
                    'moto.placa'
                )
                ->orderByDesc('inventario.id')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = $registro->fecha_registro
                    ?: $registro->created_at
                    ?: $registro->updated_at;

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Recepción registrada',
                    'descripcion' => 'Ingreso de motocicleta'
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Diagnósticos registrados o actualizados
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('diagnostico')
            && Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('diagnostico', 'id_inventario')
            && Schema::hasColumn('diagnostico', 'estado')
        ) {
            $consulta = DB::table('diagnostico')
                ->leftJoin(
                    'inventario',
                    'diagnostico.id_inventario',
                    '=',
                    'inventario.id'
                )
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'diagnostico.id',
                    'diagnostico.estado',
                    'diagnostico.fecha_diagnostico',
                    'diagnostico.created_at',
                    'diagnostico.updated_at',
                    'inventario.recepcion as folio_recepcion',
                    'moto.placa'
                )
                ->orderByDesc('diagnostico.id')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = $registro->fecha_diagnostico
                    ?: $registro->created_at
                    ?: $registro->updated_at;

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Diagnóstico registrado',
                    'descripcion' => 'Estado: '
                        . ($registro->estado ?: 'Sin estado')
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Preórdenes creadas o modificadas
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('preorden')
            && Schema::hasTable('diagnostico')
            && Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('preorden', 'id_diagnostico')
            && Schema::hasColumn('preorden', 'estado')
        ) {
            $consulta = DB::table('preorden')
                ->leftJoin(
                    'diagnostico',
                    'preorden.id_diagnostico',
                    '=',
                    'diagnostico.id'
                )
                ->leftJoin(
                    'inventario',
                    'diagnostico.id_inventario',
                    '=',
                    'inventario.id'
                )
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'preorden.id',
                    'preorden.estado',
                    'preorden.created_at',
                    'preorden.updated_at',
                    'inventario.recepcion as folio_recepcion',
                    'moto.placa'
                )
                ->orderByDesc('preorden.id')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = $registro->updated_at ?: $registro->created_at;

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Preorden registrada o modificada',
                    'descripcion' => 'Estado: '
                        . ($registro->estado ?: 'Sin estado')
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Órdenes de trabajo
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('orden_trabajo')
            && Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('orden_trabajo', 'id_inventario')
            && Schema::hasColumn('orden_trabajo', 'estado')
        ) {
            $consulta = DB::table('orden_trabajo')
                ->leftJoin(
                    'inventario',
                    'orden_trabajo.id_inventario',
                    '=',
                    'inventario.id'
                )
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'orden_trabajo.id',
                    'orden_trabajo.estado',
                    'orden_trabajo.fecha_inicio',
                    'orden_trabajo.fecha_fin',
                    'orden_trabajo.created_at',
                    'orden_trabajo.updated_at',
                    'inventario.recepcion as folio_recepcion',
                    'moto.placa'
                )
                ->orderByDesc('orden_trabajo.id')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = match ($registro->estado) {
                    'finalizada' => $registro->fecha_fin
                        ?: $registro->updated_at
                        ?: $registro->created_at,
                    'en_proceso' => $registro->fecha_inicio
                        ?: $registro->updated_at
                        ?: $registro->created_at,
                    default => $registro->created_at
                        ?: $registro->updated_at,
                };

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Orden de trabajo · '
                        . str_replace('_', ' ', $registro->estado),
                    'descripcion' => 'Estado: '
                        . str_replace('_', ' ', $registro->estado)
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagos registrados
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('pago')
            && Schema::hasTable('orden_trabajo')
            && Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('pago', 'id_ordentrabajo')
            && Schema::hasColumn('pago', 'estado')
        ) {
            $consulta = DB::table('pago')
                ->leftJoin(
                    'preorden',
                    'pago.id_preorden',
                    '=',
                    'preorden.id'
                )
                ->leftJoin(
                    'diagnostico',
                    'preorden.id_diagnostico',
                    '=',
                    'diagnostico.id'
                )
                ->leftJoin(
                    'inventario',
                    'diagnostico.id_inventario',
                    '=',
                    'inventario.id'
                )
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'pago.id',
                    'pago.estado',
                    'pago.valor',
                    'pago.fecha_pago',
                    'pago.created_at',
                    'pago.updated_at',
                    'inventario.recepcion as folio_recepcion',
                    'moto.placa'
                )
                ->orderByDesc('pago.id')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = $registro->fecha_pago
                    ?: $registro->created_at
                    ?: $registro->updated_at;

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Pago registrado',
                    'descripcion' => 'Estado: '
                        . ($registro->estado ?: 'Sin estado')
                        . ' · Valor: $'
                        . number_format((float) $registro->valor, 0, ',', '.')
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Entregas registradas
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('entrega')
            && Schema::hasTable('orden_trabajo')
            && Schema::hasTable('inventario')
            && Schema::hasColumn('inventario', 'recepcion')
            && Schema::hasColumn('entrega', 'id_ordentrabajo')
            && Schema::hasColumn('entrega', 'fecha_entrega')
        ) {
            $consulta = DB::table('entrega')
                ->leftJoin(
                    'orden_trabajo',
                    'entrega.id_ordentrabajo',
                    '=',
                    'orden_trabajo.id'
                )
                ->leftJoin(
                    'inventario',
                    'orden_trabajo.id_inventario',
                    '=',
                    'inventario.id'
                )
                ->leftJoin('moto', 'inventario.id_moto', '=', 'moto.id')
                ->select(
                    'entrega.id_entrega',
                    'entrega.fecha_entrega',
                    'entrega.created_at',
                    'entrega.updated_at',
                    'inventario.recepcion as folio_recepcion',
                    'moto.placa'
                )
                ->orderByDesc('entrega.id_entrega')
                ->limit(30)
                ->get();

            foreach ($consulta as $registro) {
                $fecha = $registro->fecha_entrega
                    ?: $registro->created_at
                    ?: $registro->updated_at;

                if (!$fecha) {
                    continue;
                }

                $actividades->push([
                    'tipo' => 'Motocicleta entregada',
                    'descripcion' => 'Entrega registrada'
                        . ($registro->placa ? ' · Placa ' . $registro->placa : ''),
                    'fecha' => $fecha,
                    'referencia' => $this->formatearFolio($registro->folio_recepcion),
                ]);
            }
        }

        return $actividades
            ->sortByDesc(function ($actividad) {
                return strtotime($actividad['fecha']) ?: 0;
            })
            ->take(8)
            ->values();
    }

    /**
     * Construir el folio visible de una recepción.
     */
    private function formatearFolio($numero): string
    {
        if ($numero === null) {
            return 'RECEPCIÓN SIN FOLIO';
        }

        return 'RECEPCIÓN # ' . str_pad(
            (string) $numero,
            3,
            '0',
            STR_PAD_LEFT
        );
    }
}
