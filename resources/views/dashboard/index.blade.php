@extends('layouts.app')

@section('title', $secciones[$seccion])
@section('page-title', $secciones[$seccion])

@section('content')

    @php
        $resumenes = [
            'inicio' => [
                'titulo' => 'Resumen general',
                'descripcion' => 'Consulta una visión general de los registros y la actividad de RAPTOR.',
                'tarjetas' => [
                    ['titulo' => 'Clientes', 'valor' => $estadisticas['clientes'], 'icono' => 'user', 'ruta' => ['cliente.index']],
                    ['titulo' => 'Motocicletas', 'valor' => $estadisticas['motocicletas'], 'icono' => 'motorcycle', 'ruta' => ['moto.index']],
                    ['titulo' => 'Recepciones', 'valor' => $estadisticas['recepciones'], 'icono' => 'clipboard', 'ruta' => ['inventario.index']],
                    [
                        'titulo' => 'Diagnósticos',
                        'valor' => $estadisticas['diagnosticos'],
                        'icono' => 'square-mouse-pointer',
                        'ruta' => ['diagnostico.index'],
                        'franja' => [
                            [
                                'etiqueta' => 'En proceso',
                                'valor' => $diagnosticosPorEstado['en_proceso'] ?? 0,
                                'clase' => 'bg-sky-100 text-sky-800',
                            ],
                            [
                                'etiqueta' => 'Finalizados',
                                'valor' => $diagnosticosPorEstado['finalizado'] ?? 0,
                                'clase' => 'bg-emerald-100 text-emerald-800',
                            ],
                            [
                                'etiqueta' => 'Cancelados',
                                'valor' => $diagnosticosPorEstado['cancelado'] ?? 0,
                                'clase' => 'bg-red-100 text-red-700',
                            ],
                        ],
                    ],
                    [
                        'titulo' => 'Preórdenes',
                        'valor'  => $estadisticas['preordenes'],
                        'icono'  => 'alarm-clock',
                        'ruta' => ['preorden.index'],
                        'franja' => [
                            ['etiqueta' => 'Aprobada',    'valor' => $preordenesPorEstado['aceptada']  ?? 0, 'clase' => 'bg-emerald-100 text-emerald-800', 'texto' => 'text-emerald-700'],
                            ['etiqueta' => 'En espera',   'valor' => $preordenesPorEstado['en_espera'] ?? 0, 'clase' => 'bg-sky-100 text-sky-800',         'texto' => 'text-sky-700'],
                            ['etiqueta' => 'Desaprobada', 'valor' => $preordenesPorEstado['rechazada'] ?? 0, 'clase' => 'bg-red-100 text-red-700',         'texto' => 'text-red-600'],
                        ],
                    ],
                    [
                        'titulo' => 'Órdenes de trabajo',
                        'valor' => $estadisticas['ordenes'],
                        'icono' => 'cog',
                        'ruta' => ['orden_trabajo.index'],
                        'franja' => [
                            [
                                'etiqueta' => 'Abiertas',
                                'valor' => $ordenesPorEstado['abierta'] ?? 0,
                                'clase' => 'bg-sky-100 text-sky-800',
                            ],
                            [
                                'etiqueta' => 'En proceso',
                                'valor' => $ordenesPorEstado['en_proceso'] ?? 0,
                                'clase' => 'bg-amber-100 text-amber-800',
                            ],
                            [
                                'etiqueta' => 'Finalizadas',
                                'valor' => $ordenesPorEstado['finalizada'] ?? 0,
                                'clase' => 'bg-emerald-100 text-emerald-800',
                            ],
                        ],
                    ],
                    [
                        'titulo' => 'Pagos completos',
                        'valor' => $estadisticas['pagos_completos'] ?? 0,
                        'icono' => 'hand-coins',
                        'ruta' => ['pago.index'],
                    ],
                    [
                        'titulo' => 'Entregas registradas',
                        'valor' => $estadisticas['entregas'],
                        'icono' =>'square-arrow-up-right',
                        'ruta' => ['entrega.index'],
                    ],
                ],
            ],

            'recepcion' => [
                'titulo' => 'Recepción de motocicletas',
                'descripcion' => 'Consulta las motocicletas que han ingresado al taller y los datos registrados en cada recepción.',
                'tarjetas' => [
                    ['titulo' => 'Clientes registrados', 'valor' => $estadisticas['clientes'], 'icono' => 'user', 'ruta' => ['cliente.index']],
                    ['titulo' => 'Motocicletas registradas', 'valor' => $estadisticas['motocicletas'], 'icono' => 'motorcycle', 'ruta' => ['moto.index']],
                    ['titulo' => 'Inventarios registrados', 'valor' => $estadisticas['recepciones'], 'icono' => 'clipboard', 'ruta' => ['inventario.index']],
                ],
            ],

            'taller' => [
                'titulo' => 'Gestión del taller',
                'descripcion' => 'Consulta los diagnósticos, las preórdenes y el estado de las órdenes de trabajo.',
                'tarjetas' => [
                    [
                        'titulo' => 'Diagnósticos',
                        'valor' => $estadisticas['diagnosticos'],
                        'icono' => 'file-cog',
                        'ruta' => ['diagnostico.index'],
                        'franja' => [
                            [
                                'etiqueta' => 'En proceso',
                                'valor' => $diagnosticosPorEstado['en_proceso'] ?? 0,
                                'clase' => 'bg-sky-100 text-sky-800',
                            ],
                            [
                                'etiqueta' => 'Finalizados',
                                'valor' => $diagnosticosPorEstado['finalizado'] ?? 0,
                                'clase' => 'bg-emerald-100 text-emerald-800',
                            ],
                            [
                                'etiqueta' => 'Cancelados',
                                'valor' => $diagnosticosPorEstado['cancelado'] ?? 0,
                                'clase' => 'bg-red-100 text-red-700',
                            ],
                        ],
                    ],
                    [
                        'titulo' => 'Preórdenes',
                        'valor' => $estadisticas['preordenes'],
                        'icono' => 'clipboard',
                        'ruta' => ['preorden.index'],
                        'franja' => [
                            [
                                'etiqueta' => 'Aprobadas',
                                'valor' => $preordenesPorEstado['aceptada'] ?? 0,
                                'clase' => 'bg-emerald-100 text-emerald-800',
                            ],
                            [
                                'etiqueta' => 'En espera',
                                'valor' => $preordenesPorEstado['en_espera'] ?? 0,
                                'clase' => 'bg-sky-100 text-sky-800',
                            ],
                            [
                                'etiqueta' => 'Rechazadas',
                                'valor' => $preordenesPorEstado['rechazada'] ?? 0,
                                'clase' => 'bg-red-100 text-red-700',
                            ],
                        ],
                    ],
                    [
                        'titulo' => 'Órdenes de trabajo',
                        'valor' => $estadisticas['ordenes'],
                        'icono' => 'cog',
                        'ruta' => ['orden_trabajo.index'],
                        'franja' => [
                            [
                                'etiqueta' => 'Abiertas',
                                'valor' => $ordenesPorEstado['abierta'] ?? 0,
                                'clase' => 'bg-sky-100 text-sky-800',
                            ],
                            [
                                'etiqueta' => 'En proceso',
                                'valor' => $ordenesPorEstado['en_proceso'] ?? 0,
                                'clase' => 'bg-amber-100 text-amber-800',
                            ],
                            [
                                'etiqueta' => 'Finalizadas',
                                'valor' => $ordenesPorEstado['finalizada'] ?? 0,
                                'clase' => 'bg-emerald-100 text-emerald-800',
                            ],
                        ],
                    ],
                ],
            ],

            'seguimiento' => [
                'titulo' => 'Seguimiento de servicios',
                'descripcion' => 'Revisa el avance de cada recepción, los valores aprobados, los pagos confirmados y las entregas.',
                'tarjetas' => [
                    ['titulo' => 'Pagos registrados', 'valor' => $estadisticas['pagos'], 'icono' => 'credit-card', 'ruta' => ['pago.index']],
                    ['titulo' => 'Valor de pagos confirmados', 'valor' => '$' . number_format($estadisticas['pagos_confirmados'], 0, ',', '.'), 'icono' => 'tag', 'ruta' => ['pago.index']],
                    ['titulo' => 'Entregas registradas', 'valor' => $estadisticas['entregas'], 'icono' => 'clipboard', 'ruta' => ['entrega.index']],
                ],
            ],

            'administracion' => [
                'titulo' => 'Administración del sistema',
                'descripcion' => 'Consulta los registros de apoyo para la operación y la organización del taller.',
                'tarjetas' => [
                    ['titulo' => 'Mecánicos registrados', 'valor' => $estadisticas['mecanicos'], 'icono' => 'users', 'ruta' => ['mecanico.index']],
                    ['titulo' => 'Repuestos registrados', 'valor' => $estadisticas['repuestos'], 'icono' => 'box', 'ruta' => ['repuesto.index']],
                    ['titulo' => 'Marcas de motocicleta', 'valor' => $estadisticas['marcas'], 'icono' => 'motorcycle', 'ruta' => ['marca_moto.index']],
                ],
            ],
        ];

        $resumen = $resumenes[$seccion];

        // Mismas pills del portal de clientes
        $pills = [
            'azul'  => 'bg-sky-100 text-sky-800',
            'verde' => 'bg-emerald-100 text-emerald-800',
            'ambar' => 'bg-amber-100 text-amber-800',
            'rojo'  => 'bg-red-100 text-red-700',
        ];

        // Tono de la pill según el texto de la etapa
        $tonoEtapa = function ($etapa) {
            $e = mb_strtolower((string) $etapa);

            return match (true) {
                str_contains($e, 'entregad'), str_contains($e, 'lista para entrega') => 'verde',
                str_contains($e, 'rechazad') => 'rojo',
                str_contains($e, 'pendiente') => 'ambar',
                default => 'azul',
            };
        };

        $fecha = fn ($valor) => $valor ? \Illuminate\Support\Carbon::parse($valor)->format('d/m/Y') : 'No registrada';
        $folioRecepcion = fn ($numero) => $numero !== null
            ? 'RECEPCIÓN # ' . str_pad((string) $numero, 3, '0', STR_PAD_LEFT)
            : 'RECEPCIÓN SIN FOLIO';
    @endphp

    <style>
        [x-cloak] { display: none !important; }

        #panel-raptor {
            --navy: #0b2a3f;
            --navy-2: #123e5a;
            --blue: #1f5f8b;
            --yellow: #c9993f;
            --panel-head: #DCE8ED; /* azul de los bloques: cambiar solo aquí */
            --line: #e3eaf0;
            color: #1e2f3c;
        }

        #panel-raptor .panel-head {
            background: var(--panel-head);
            border: 1px solid var(--panel-head);
            color: #14252F;
        }

        #panel-raptor .stat-card { border-top: 2px solid transparent;}

        #panel-raptor a:focus-visible,
        #panel-raptor button:focus-visible {
            outline: 3px solid rgb(245 176 27 / 75%);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            #panel-raptor *, #panel-raptor *::before, #panel-raptor *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

        #panel-raptor .panel-summary {
            border-top: 2px solid #C9993F;
            border-left: 2px solid #C9993F;
        }

        #panel-raptor .panel-head {
            position: relative;
            background: var(--panel-head);
            border: 1px solid var(--panel-head);
            border-top: 1.5px solid #C9993F;
            border-left: 2px solid #C9993F;
            color: #14252F;
        }
    </style>

    <div id="panel-raptor" class="space-y-6">

        {{-- Encabezado de la sección --}}
        <div class="panel-summary rounded-xl border border-[#e3eaf0] bg-white px-4 py-3 shadow-sm">
            <h2 class="text-base font-semibold text-black">
                {{ $resumen['titulo'] }}
            </h2>
        </div>

        {{-- Tarjetas estadísticas --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($resumen['tarjetas'] as $tarjeta)
                @php
                    $nombreRuta = collect((array) ($tarjeta['ruta'] ?? []))
                        ->first(fn ($n) => \Illuminate\Support\Facades\Route::has($n));

                    $href = $nombreRuta ? route($nombreRuta) : null;
                @endphp

                <{{ $href ? 'a' : 'div' }}
                    @if ($href) href="{{ $href }}" aria-label="Ir a {{ $tarjeta['titulo'] }}" @endif
                    class="stat-card flex h-full min-h-[5.5rem] flex-col justify-center rounded-lg border border-[#dde3e9] bg-[#eceff3] p-3 shadow-sm {{ $href ? 'transition hover:border-[#9bb8c6] hover:shadow-md' : '' }}">

                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-white text-[#315a72]">
                            <span class="block h-5 w-5">
                                @include('layouts.partials.icons', ['icon' => $tarjeta['icono']])
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm leading-4 text-slate-700">
                                {{ $tarjeta['titulo'] }}
                            </p>

                            <p class="mt-1 text-xl font-semibold leading-none text-[#24485e]">
                                {{ $tarjeta['valor'] }}
                            </p>

                            @if ($href)
                                <p class="mt-2 text-[10px] font-medium text-[#1f5f8b]">
                                     >
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Estados a todo el ancho de la tarjeta --}}
                    @if (!empty($tarjeta['franja']))
                        <div class="mt-2 grid grid-cols-3 gap-1">
                            @foreach ($tarjeta['franja'] as $item)
                                <div class="flex items-center justify-center gap-1 rounded-full px-1.5 py-0.5 {{ $item['clase'] }}">
                                    <span class="text-xs font-semibold leading-none">{{ $item['valor'] }}</span>
                                    <span class="truncate text-[9px] font-medium leading-none">{{ $item['etiqueta'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </{{ $href ? 'a' : 'div' }}>
            @endforeach
        </div>

        {{-- ===================== INFORMACIÓN DE LAS SECCIONES ===================== --}}
        @if ($seccion === 'taller')
            <section class="overflow-hidden rounded-xl border border-[#e3eaf0] bg-white shadow-sm">
                <div class="panel-head flex flex-col gap-1 px-4 py-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold">Servicios en el taller</h3>
                    </div>
                    <span class="w-fit rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#0b2a3f]">
                        {{ $seguimientos->count() }} {{ $seguimientos->count() === 1 ? 'servicio' : 'servicios' }}
                    </span>
                </div>

                <div class="divide-y divide-[#e3eaf0]">
                    @forelse ($seguimientos as $servicio)
                       <div x-data="{ modalAbierto: false }" class="p-5 transition-colors duration-200 hover:bg-[#e8edf1]">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-4">
                                    <span class="grid h-12 w-14 shrink-0 place-items-center rounded-lg bg-[#eef3f7] text-[#0b2a3f]">
                                        <span class="block h-7 w-7 scale-110">
                                            @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                        </span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-500">{{ $folioRecepcion($servicio->recepcion) }}</p>
                                        <h4 class="mt-0.5 text-sm font-semibold tracking-wide text-[#0b2a3f]">{{ $servicio->placa }}</h4>
                                        <p class="mt-0.5 truncate text-sm text-slate-600">
                                            {{ $servicio->nombre_marca }} {{ $servicio->modelo }}
                                            @if ($servicio->anio) · Año {{ $servicio->anio }} @endif
                                        </p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            Cliente: <span class="font-semibold text-slate-800">{{ $servicio->cliente_nombre }} {{ $servicio->cliente_apellido }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 sm:items-end">
                                    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-medium {{ $pills[$tonoEtapa($servicio->etapa)] }}">
                                        {{ $servicio->etapa }}
                                    </span>
                                    <button type="button" @click="modalAbierto = true"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#294c61]">
                                        <span class="block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                                        Ver detalles
                                    </button>
                                </div>
                            </div>

                            {{-- Modal con las etapas del taller --}}
                            <div x-cloak x-show="modalAbierto" x-transition.opacity
                                 @keydown.escape.window="modalAbierto = false"
                                 @click.self="modalAbierto = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/55 p-4">
                                <div x-show="modalAbierto" x-transition
                                     class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                     role="dialog" aria-modal="true">

                                    {{-- Encabezado del modal --}}
                                    <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                <span class="block h-5 w-5">
                                                    @include('layouts.partials.icons', ['icon' => 'cog'])
                                                </span>
                                            </div>

                                            <div class="min-w-0">
                                                <h2 class="break-words text-lg font-bold text-slate-800">
                                                    Detalles del servicio
                                                </h2>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ $folioRecepcion($servicio->recepcion) }}
                                                </p>
                                            </div>

                                        </div>

                                        {{-- Cerrar --}}
                                        <button
                                            type="button"
                                            @click="modalAbierto = false"
                                            aria-label="Cerrar modal"
                                            title="Cerrar"
                                            class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>

                                    </div>

                                    <div class="max-h-[65vh] space-y-5 overflow-y-auto p-6">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $pills[$tonoEtapa($servicio->etapa)] }}">{{ $servicio->etapa }}</span>
                                                <span class="text-sm text-slate-600">{{ $servicio->nombre_marca }} {{ $servicio->modelo }} — Placa <span class="font-medium text-slate-800">{{ $servicio->placa }}</span></span>
                                            </div>
                                            <p class="mt-2 text-sm text-slate-600">Cliente: <span class="font-semibold text-slate-800">{{ $servicio->cliente_nombre }} {{ $servicio->cliente_apellido }}</span></p>
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <h4 class="font-medium text-[#0b2a3f]">Diagnóstico</h4>
                                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $pills[$tonoEtapa($servicio->diagnostico->estado ?? 'Pendiente')] }}">{{ $servicio->diagnostico->estado ?? 'Pendiente' }}</span>
                                            </div>
                                            @if ($servicio->diagnostico)
                                                <p class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $servicio->diagnostico->descripcion ?: 'Sin descripción registrada.' }}</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay diagnóstico registrado para esta recepción.</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <h4 class="font-medium text-[#0b2a3f]">Preorden</h4>
                                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $pills[$tonoEtapa($servicio->preorden->estado ?? 'Pendiente')] }}">{{ $servicio->preorden->estado ?? 'Pendiente' }}</span>
                                            </div>
                                            @if ($servicio->preorden)
                                                <p class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $servicio->preorden->descripcion ?: 'Sin descripción registrada.' }}</p>
                                                <p class="mt-2 text-sm font-semibold text-[#0b2a3f]">Total: ${{ number_format($servicio->preorden->total, 0, ',', '.') }}</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay una preorden registrada para esta recepción.</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <h4 class="font-medium text-[#0b2a3f]">Orden de trabajo</h4>
                                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $pills[$tonoEtapa($servicio->orden->estado ?? 'Pendiente')] }}">{{ $servicio->orden ? ucfirst(str_replace('_', ' ', $servicio->orden->estado)) : 'Pendiente' }}</span>
                                            </div>
                                            @if ($servicio->orden)
                                                <p class="mt-2 text-sm text-slate-700">La orden de trabajo representa la ejecución del servicio autorizado.</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay una orden de trabajo registrada para esta recepción.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="border-t border-[#e3eaf0] bg-slate-50 px-6 py-4 text-right">
                                        <button type="button" @click="modalAbierto = false" class="rounded-xl bg-[#0b2a3f] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#123e5a]">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700">No hay servicios para consultar.</p>
                            <p class="mt-1 text-xs text-slate-500">Cuando registres una recepción, podrás consultar aquí sus etapas de taller.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        @if ($seccion === 'seguimiento')
            <section class="panel-summary rounded-xl border border-[#e3eaf0] bg-white px-5 py-4 shadow-sm">
                <h3 class="text-sm font-semibold text-[#0b2a3f]">Control de pagos y entregas</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Desde esta sección puedes consultar el avance de cada recepción,
                    verificar los pagos confirmados y conocer el saldo pendiente.
                    La entrega de la motocicleta debe registrarse cuando se hayan
                    cumplido las condiciones establecidas para finalizar el servicio.
                </p>
            </section>
        @endif

        @if ($seccion === 'administracion')
            <section class="panel-summary rounded-xl border border-[#e3eaf0] bg-white px-5 py-4 shadow-sm">
                <h3 class="text-sm font-semibold text-[#0b2a3f]">Información de apoyo del taller</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Los mecánicos, repuestos y marcas de motocicleta conforman los
                    datos de apoyo que utiliza RAPTOR en sus procesos. Mantener estos
                    registros actualizados facilita la asignación de trabajos y la
                    gestión de los servicios de mantenimiento y reparación.
                </p>
            </section>
        @endif

        {{-- ===================== SEGUIMIENTO ===================== --}}
        @if ($seccion === 'seguimiento')

            <section class="overflow-hidden rounded-xl border border-[#e3eaf0] bg-white shadow-sm">
                <div class="panel-head px-4 py-2">
                    <h3 class="text-sm font-semibold">Avance de las recepciones</h3>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Cada recepción representa un ingreso al taller. Consulta su etapa actual y revisa el diagnóstico, la preorden, la orden de trabajo, los pagos y la entrega.
                    </p>
                </div>

                <div class="divide-y divide-[#e3eaf0]">
                    @forelse ($seguimientos as $servicio)
                       <div x-data="{ modalAbierto: false }" class="p-5 transition-colors duration-200 hover:bg-[#e8edf1]">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-4">
                                    <span class="grid h-12 w-14 shrink-0 place-items-center rounded-lg bg-[#eef3f7] text-[#0b2a3f]">
                                        <span class="block h-7 w-7">
                                            @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                        </span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-500">{{ $folioRecepcion($servicio->recepcion) }}</p>
                                        <h4 class="mt-0.5 text-sm font-semibold tracking-wide text-[#0b2a3f]">{{ $servicio->placa }}</h4>
                                        <p class="mt-0.5 truncate text-sm text-slate-600">
                                            {{ $servicio->nombre_marca }} {{ $servicio->modelo }}
                                            @if ($servicio->anio) · Año {{ $servicio->anio }} @endif
                                        </p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            Cliente:
                                            <span class="font-semibold text-slate-800">{{ $servicio->cliente_nombre }} {{ $servicio->cliente_apellido }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 sm:items-end">
                                    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-medium {{ $pills[$tonoEtapa($servicio->etapa)] }}">
                                        {{ $servicio->etapa }}
                                    </span>
                                    <button type="button" @click="modalAbierto = true"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#294c61]">
                                        <span class="block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                                        Ver detalles
                                    </button>
                                </div>
                            </div>

                            {{-- Resumen financiero --}}
                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Total aprobado</p>
                                    <p class="mt-1 text-base font-semibold text-[#0b2a3f]">${{ number_format($servicio->total_aprobado, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Pagos confirmados</p>
                                    <p class="mt-1 text-base font-semibold text-[#1f5f8b]">${{ number_format($servicio->pagado, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-xl p-3 {{ $servicio->saldo > 0 ? 'bg-amber-50' : 'bg-emerald-50' }}">
                                    <p class="text-xs {{ $servicio->saldo > 0 ? 'text-amber-800' : 'text-emerald-800' }}">Saldo pendiente</p>
                                    <p class="mt-1 text-base font-semibold {{ $servicio->saldo > 0 ? 'text-amber-800' : 'text-emerald-800' }}">${{ number_format($servicio->saldo, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Modal de detalles --}}
                            <div x-cloak x-show="modalAbierto" x-transition.opacity
                                 @keydown.escape.window="modalAbierto = false"
                                 @click.self="modalAbierto = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/55 p-4">
                                <div x-show="modalAbierto" x-transition
                                     class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                     role="dialog" aria-modal="true">

                                    {{-- Encabezado del modal --}}
                                    <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                <span class="block h-5 w-5">
                                                    @include('layouts.partials.icons', ['icon' => 'grid-2x2-check'])
                                                </span>
                                            </div>

                                            <div class="min-w-0">
                                                <h2 class="break-words text-lg font-bold text-slate-800">
                                                    Detalles del seguimiento
                                                </h2>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ $folioRecepcion($servicio->recepcion) }}
                                                </p>
                                            </div>

                                        </div>

                                        {{-- Cerrar --}}
                                        <button
                                            type="button"
                                            @click="modalAbierto = false"
                                            aria-label="Cerrar modal"
                                            title="Cerrar"
                                            class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>

                                    </div>

                                    <div class="max-h-[65vh] space-y-5 overflow-y-auto p-6">

                                        <div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="rounded-full px-3 py-1.5 text-sm font-medium {{ $pills[$tonoEtapa($servicio->etapa)] }}">{{ $servicio->etapa }}</span>
                                                <span class="text-sm text-slate-600">{{ $servicio->nombre_marca }} {{ $servicio->modelo }} — Placa <span class="font-medium text-slate-800">{{ $servicio->placa }}</span></span>
                                            </div>
                                            <p class="mt-3 whitespace-pre-line rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">{{ $servicio->descripcion ?: 'Sin descripción de recepción.' }}</p>
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <h4 class="font-medium text-[#0b2a3f]">Diagnóstico</h4>
                                            @if ($servicio->diagnostico)
                                                <p class="mt-2 text-sm text-slate-700">{{ $servicio->diagnostico->descripcion ?: 'Sin descripción registrada.' }}</p>
                                                <p class="mt-1 text-sm text-slate-500">Estado: {{ $servicio->diagnostico->estado }}</p>
                                                <p class="mt-2 text-xs text-slate-500">El diagnóstico registra la valoración inicial de las fallas observadas y los repuestos sugeridos.</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay diagnóstico registrado para esta recepción.</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <h4 class="font-medium text-[#0b2a3f]">Preorden</h4>
                                            @if ($servicio->preorden)
                                                <p class="mt-2 text-sm text-slate-700">{{ $servicio->preorden->descripcion ?: 'Sin descripción registrada.' }}</p>
                                                <p class="mt-1 text-sm text-slate-600">Estado: {{ $servicio->preorden->estado }}</p>
                                                <p class="mt-1 text-sm font-medium text-[#0b2a3f]">Total: ${{ number_format($servicio->preorden->total, 0, ',', '.') }}</p>
                                                <p class="mt-2 text-xs text-slate-500">La preorden contiene el presupuesto formal. Su aprobación por el cliente es necesaria antes de generar la orden de trabajo.</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay una preorden registrada para esta recepción.</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <h4 class="font-medium text-[#0b2a3f]">Orden de trabajo</h4>
                                            @if ($servicio->orden)
                                                <p class="mt-2 text-sm text-slate-700">Estado: {{ ucfirst(str_replace('_', ' ', $servicio->orden->estado)) }}</p>
                                                <p class="mt-2 text-xs text-slate-500">La orden de trabajo representa la ejecución del servicio autorizado.</p>
                                            @else
                                                <p class="mt-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Todavía no hay una orden de trabajo registrada para esta recepción.</p>
                                            @endif
                                        </div>

                                        <div class="border-t border-[#e3eaf0] pt-4">
                                            <h4 class="font-medium text-[#0b2a3f]">Pagos y entrega</h4>
                                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                                <div class="rounded-xl bg-slate-50 p-3">
                                                    <p class="text-xs text-slate-500">Total aprobado</p>
                                                    <p class="mt-1 font-medium">${{ number_format($servicio->total_aprobado, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 p-3">
                                                    <p class="text-xs text-slate-500">Pagos confirmados</p>
                                                    <p class="mt-1 font-medium text-[#1f5f8b]">${{ number_format($servicio->pagado, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="rounded-xl p-3 {{ $servicio->saldo > 0 ? 'bg-amber-50' : 'bg-emerald-50' }}">
                                                    <p class="text-xs {{ $servicio->saldo > 0 ? 'text-amber-800' : 'text-emerald-800' }}">Saldo pendiente</p>
                                                    <p class="mt-1 font-semibold {{ $servicio->saldo > 0 ? 'text-amber-800' : 'text-emerald-800' }}">${{ number_format($servicio->saldo, 0, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            @if ($servicio->entrega)
                                                <p class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">
                                                    Entrega registrada: {{ $fecha($servicio->entrega->fecha_entrega) }}
                                                </p>
                                            @else
                                                <p class="mt-3 rounded-xl bg-slate-50 p-4 text-sm text-slate-600">La motocicleta todavía no tiene una entrega registrada.</p>
                                            @endif

                                            <p class="mt-2 text-xs text-slate-500">Solo los pagos confirmados se descuentan del saldo pendiente.</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-[#e3eaf0] bg-slate-50 px-6 py-4 text-right">
                                        <button type="button" @click="modalAbierto = false" class="rounded-xl bg-[#0b2a3f] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#123e5a]">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700">No hay recepciones para consultar.</p>
                            <p class="mt-1 text-xs text-slate-500">Cuando registres una recepción en Inventario, podrás consultar aquí el avance de su servicio.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        @endif

        {{-- ===================== ACTIVIDADES RECIENTES: INICIO ===================== --}}
        @if ($seccion === 'inicio')

            <section class="overflow-hidden rounded-xl border border-[#e3eaf0] bg-white shadow-sm">
                <div class="panel-head flex flex-col gap-1 px-4 py-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold">Actividades recientes</h3>
                    </div>
                    <span class="w-fit rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#0b2a3f]">
                        Últimas 8 actividades
                    </span>
                </div>

                <div class="divide-y divide-[#e3eaf0]">
                    @forelse ($actividadesRecientes as $actividad)
                        <div class="flex items-start gap-3 px-4 py-3 transition-colors duration-200 hover:bg-[#e8edf1]">
                            <div class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#f3f6f8] text-[#587184]">
                                <span class="block h-3 w-3">
                                    @include('layouts.partials.icons', ['icon' => 'activity'])
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                @if (!empty($actividad['referencia']))
                                    <p class="mb-1 text-xs font-semibold text-slate-700">{{ $actividad['referencia'] }}</p>
                                @endif
                                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-[13px] font-medium text-[#0b2a3f]">{{ $actividad['tipo'] }}</p>
                                    <span class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($actividad['fecha'])->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="mt-1 text-[13px] leading-5 text-slate-600">{{ $actividad['descripcion'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <p class="text-[13px] font-semibold text-slate-700">Todavía no hay actividades para mostrar.</p>
                            <p class="mt-1 text-[11px] text-slate-500">Los movimientos del taller aparecerán aquí cuando se registren.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        @endif

        {{-- ===================== RECEPCIÓN ===================== --}}
        @if ($seccion === 'recepcion')

            <section class="overflow-hidden rounded-xl border border-[#e3eaf0] bg-white shadow-sm">
                <div class="panel-head flex flex-col gap-1 px-4 py-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold">Recepciones registradas</h3>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Cada registro corresponde a una visita de una motocicleta al taller. Una misma motocicleta puede tener varias recepciones.
                        </p>
                    </div>
                    <span class="w-fit rounded-full bg-white px-3 py-1 text-sm font-semibold text-[#0b2a3f]">
                        {{ $recepciones->count() }} {{ $recepciones->count() === 1 ? 'recepción' : 'recepciones' }}
                    </span>
                </div>

                <div class="divide-y divide-[#e3eaf0]">
                    @forelse ($recepciones as $recepcion)

                        <div x-data="{ modalAbierto: false }" class="p-5 transition-colors duration-200 hover:bg-[#e8edf1]">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-4">
                                    <span class="grid h-12 w-14 shrink-0 place-items-center rounded-lg bg-[#eef3f7] text-[#0b2a3f]">
                                        <span class="block h-7 w-7">
                                            @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                        </span>
                                    </span>
                                    <div class="min-w-0">
                                        <h4 class="text-base font-bold tracking-wide text-[#0b2a3f]">
                                            {{ $folioRecepcion($recepcion->recepcion) }}
                                        </h4>
                                        <p class="mt-0.5 text-xs font-medium text-slate-500">
                                            {{ $recepcion->placa }}
                                        </p>
                                        <p class="mt-0.5 truncate text-sm text-slate-600">
                                            {{ $recepcion->nombre_marca }} {{ $recepcion->modelo }}
                                            @if ($recepcion->anio) · Año {{ $recepcion->anio }} @endif
                                        </p>
                                        <p class="mt-1 text-sm text-slate-600">
                                            Cliente: <span class="font-semibold text-slate-800">{{ $recepcion->cliente_nombre }} {{ $recepcion->cliente_apellido }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 sm:items-end">
                                    @php
                                        $estadoRecepcion = ucfirst(str_replace('_', ' ', $recepcion->estado_inventario ?: 'Pendiente'));
                                    @endphp
                                    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-medium {{ $pills[$tonoEtapa($estadoRecepcion)] }}">
                                        {{ $estadoRecepcion }}
                                    </span>
                                    <button type="button" @click="modalAbierto = true"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#294c61]">
                                        <span class="block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                                        Ver detalles
                                    </button>
                                </div>
                            </div>

                            {{-- Modal de recepción --}}
                            <div x-cloak x-show="modalAbierto" x-transition.opacity
                                 @keydown.escape.window="modalAbierto = false"
                                 @click.self="modalAbierto = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/55 p-4">

                                <div x-show="modalAbierto" x-transition
                                     class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                     role="dialog" aria-modal="true"
                                     aria-labelledby="titulo-modal-recepcion-{{ $recepcion->id_inventario }}">

                                {{-- Encabezado del modal --}}
                                <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                    <div class="flex min-w-0 items-center gap-3">

                                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                            <span class="block h-5 w-5">
                                                @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                            </span>
                                        </div>

                                        <div class="min-w-0">
                                            <h2
                                                id="titulo-modal-recepcion-{{ $recepcion->id_inventario }}"
                                                class="break-words text-lg font-bold text-slate-800"
                                            >
                                                Detalles de la recepción
                                            </h2>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $folioRecepcion($recepcion->recepcion) }}
                                            </p>
                                        </div>

                                    </div>

                                    {{-- Cerrar --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = false"
                                        aria-label="Cerrar modal"
                                        title="Cerrar"
                                        class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>

                                </div>


                                    <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">

                                        {{-- Motocicleta --}}
                                        <div>
                                            <h4 class="font-medium text-[#0b2a3f]">Motocicleta</h4>
                                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Placa</p>
                                                    <p class="mt-1 font-semibold tracking-wide text-[#0b2a3f]">{{ $recepcion->placa }}</p>
                                                </div>
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Marca y modelo</p>
                                                    <p class="mt-1 font-semibold text-slate-800">{{ $recepcion->nombre_marca }} {{ $recepcion->modelo }}</p>
                                                </div>
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Año</p>
                                                    <p class="mt-1 font-semibold text-slate-800">{{ $recepcion->anio ?: 'No registrado' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Condición general</p>
                                                    <p class="mt-1 font-semibold text-slate-800">{{ $recepcion->condicion_general ?: 'No registrada' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Fecha de ingreso</p>
                                                    <p class="mt-1 font-semibold text-slate-800">{{ $fecha($recepcion->fecha_registro) }}</p>
                                                </div>
                                                <div class="rounded-lg border border-[#e3eaf0] bg-slate-50 p-3">
                                                    <p class="text-xs font-semibold text-slate-500">Estado del inventario</p>
                                                    <p class="mt-1 font-semibold text-slate-800">{{ $recepcion->estado_inventario ?: 'No registrado' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Descripción --}}
                                        <div class="rounded-xl bg-[#eef3f7] p-4">
                                            <p class="text-xs font-semibold text-slate-500">Descripción de la recepción</p>
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $recepcion->descripcion ?: 'Sin descripción registrada.' }}</p>
                                        </div>

                                        {{-- Cliente --}}
                                        <div class="border-t border-[#e3eaf0] pt-5">
                                            <h4 class="font-medium text-[#0b2a3f]">Cliente</h4>
                                            <div class="mt-3 flex items-start gap-4 rounded-xl border border-[#e3eaf0] bg-slate-50 p-4">
                                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#0b2a3f] text-sm font-semibold text-white">
                                                    {{ mb_strtoupper(mb_substr($recepcion->cliente_nombre ?? '', 0, 1) . mb_substr($recepcion->cliente_apellido ?? '', 0, 1)) ?: 'CL' }}
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="break-words font-semibold text-[#0b2a3f]">{{ $recepcion->cliente_nombre }} {{ $recepcion->cliente_apellido }}</p>
                                                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                        <div>
                                                            <p class="text-xs font-semibold text-slate-500">Documento</p>
                                                            <p class="mt-1 text-sm font-semibold text-slate-800">{{ $recepcion->tipo_documento }} {{ $recepcion->numero_documento }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-semibold text-slate-500">Teléfono</p>
                                                            <p class="mt-1 text-sm font-semibold text-slate-800">{{ $recepcion->telefono ?: 'No registrado' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Mecánico --}}
                                        <div class="border-t border-[#e3eaf0] pt-5">
                                            <h4 class="font-medium text-[#0b2a3f]">Mecánico receptor</h4>
                                            <p class="mt-2 text-sm font-semibold text-slate-800">{{ $recepcion->mecanico_nombre }} {{ $recepcion->mecanico_apellido }}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-[#e3eaf0] bg-slate-50 px-6 py-4 text-right">
                                        <button type="button" @click="modalAbierto = false" class="rounded-xl bg-[#0b2a3f] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#123e5a]">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    @empty
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700">Todavía no hay recepciones registradas.</p>
                            <p class="mt-1 text-xs text-slate-500">Registra primero la entrada de la motocicleta en el módulo Inventario. Después podrás continuar con el diagnóstico.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Siguiente paso --}}
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                <p>
                    <span class="font-semibold">Siguiente paso: Diagnóstico.</span>
                    Una vez registrada la recepción, documenta las fallas observadas y los repuestos sugeridos. El presupuesto formal se prepara posteriormente en la preorden.
                </p>

                <a href="{{ route('diagnostico.index') }}"
                class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-[#0b2a3f] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#123e5a]">
                    Ir a Diagnóstico
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        @endif

    </div>

@endsection