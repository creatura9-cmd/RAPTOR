@extends('layouts.auth')

@section('content')
@php
    /*
    |--------------------------------------------------------------------------
    | Preparación de datos
    |--------------------------------------------------------------------------
    */
    $resumir = function ($preorden) {
        $diagnostico = $preorden->diagnostico;
        $inventario  = $diagnostico?->inventario;
        $moto        = $inventario?->moto;
        $orden       = $preorden->ordenTrabajo;
        $entrega     = $orden?->entrega;

        // Folio único de recepción.
        $folioRecepcion = $inventario?->recepcion !== null
            ? 'RECEPCIÓN # ' . str_pad((string) $inventario->recepcion, 3, '0', STR_PAD_LEFT)
            : 'RECEPCIÓN SIN FOLIO';

        // Total del servicio.
        $totalOrden = (float) ($orden?->total ?? 0);
        $totalPreorden = (float) ($preorden->total ?? 0);
        $totalServicio = $totalOrden > 0 ? $totalOrden : $totalPreorden;

        // Los pagos financieros se consultan desde la preorden.
        $pagos = $preorden->pagos;

        // Solo se descuentan los pagos confirmados.
        $pagosConfirmados = (float) $pagos
            ->where('estado', 'confirmado')
            ->sum('valor');

        $saldo = max(0, $totalServicio - $pagosConfirmados);
        $estado = $preorden->estado;

        [$etapa, $tono, $progreso] = match (true) {
            $entrega !== null                                       => ['Entregada', 'verde', 100],
            $estado === 'rechazada'                                 => ['Preorden rechazada', 'rojo', 0],
            $estado === 'pendiente'                                 => ['Pendiente de aprobación', 'ambar', 35],
            $estado === 'aprobada' && !$orden                       => ['Aprobada; falta orden de trabajo', 'azul', 45],
            $orden && $orden->estado === 'finalizada' && $saldo > 0 => ['Pendiente de pago', 'ambar', 90],
            $orden && $orden->estado === 'finalizada'               => ['Lista para entrega', 'verde', 95],
            $orden && $orden->estado === 'abierta'                  => ['Orden de trabajo abierta', 'azul', 55],
            $orden && $orden->estado === 'en_proceso'               => ['Reparación en curso', 'azul', 75],
            $orden                                                    => ['Orden de trabajo registrada', 'azul', 55],
            $diagnostico !== null                                   => ['Diagnóstico y presupuesto', 'azul', 30],
            $inventario !== null                                    => ['Recepción', 'azul', 15],
            default                                                 => ['En proceso de registro', 'azul', 5],
        };

        return compact(
            'diagnostico',
            'inventario',
            'moto',
            'orden',
            'entrega',
            'folioRecepcion',
            'totalServicio',
            'pagos',
            'pagosConfirmados',
            'saldo',
            'estado',
            'etapa',
            'tono',
            'progreso'
        );
    };

    $pills = [
        'azul'  => 'bg-sky-100 text-sky-800',
        'verde' => 'bg-emerald-100 text-emerald-800',
        'ambar' => 'bg-amber-100 text-amber-800',
        'rojo'  => 'bg-red-100 text-red-700',
    ];

    $todas = $preordenes->sortByDesc('id')->unique('id')->values();

    $info = $todas->mapWithKeys(fn ($p) => [$p->id => $resumir($p)]);

    // Agrupar los servicios por motocicleta.
    $serviciosPorMoto = $todas
        ->filter(fn ($p) => $info[$p->id]['moto'] !== null)
        ->groupBy(fn ($p) => $info[$p->id]['moto']->id);

    // Servicios que aún no han sido entregados.
    $sinEntrega = $todas
        ->filter(fn ($p) => $info[$p->id]['entrega'] === null)
        ->values();

    $entregadas = $todas
        ->filter(fn ($p) => $info[$p->id]['entrega'] !== null)
        ->values();

    // Motocicletas que tienen al menos un servicio entregado.
    $motosEntregadas = $entregadas
        ->map(fn ($p) => $info[$p->id]['moto'])
        ->filter()
        ->unique('id')
        ->values();

    $circ = 2 * M_PI * 54;

    $porAprobar = $preordenes->where('estado', 'pendiente')->count();

    $iniciales = mb_strtoupper(
        mb_substr($cliente->nombre ?? '', 0, 1) .
        mb_substr($cliente->apellido ?? '', 0, 1)
    );

    $heroFoto = file_exists(public_path('images/portal-hero.jpg'))
        ? asset('images/portal-hero.jpg')
        : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?q=80&w=2070&auto=format&fit=crop';

    $fechaCorta = fn ($valor) => $valor
        ? \Carbon\Carbon::parse($valor)->format('d/m/Y')
        : '—';

    $iconoMoto = '
        <svg viewBox="0 0 256 256" fill="currentColor" class="h-8 w-8 scale-100">
            <path d="M216 120a41 41 0 0 0-6.6.55l-5.82-15.14A55.6 55.6 0 0 1 216 104a8 8 0 0 0 0-16h-19.12l-13.41-34.87A8 8 0 0 0 176 48h-32a8 8 0 0 0 0 16h26.51l9.23 24H152c-18.5 0-33.5 4.31-43.37 12.46a16 16 0 0 1-16.76 2.07c-10.58-4.81-73.29-30.12-73.8-30.26a8 8 0 0 0-5 15.19s55.5 21.94 66.53 32.94A55.67 55.67 0 0 1 95.43 152H79.2a40 40 0 1 0 0 16h52.12a31.91 31.91 0 0 0 30.74-23.1a56 56 0 0 1 26.59-33.72l5.82 15.13A40 40 0 1 0 216 120M40 168h22.62a24 24 0 1 1 0-16H40a8 8 0 0 0 0 16m176 16a24 24 0 0 1-15.58-42.23l8.11 21.1a8 8 0 1 0 14.94-5.74L215.35 136h.65a24 24 0 0 1 0 48"/>
        </svg>
    ';
@endphp

<style>
    body:has(#portal-cliente) { display: block; padding: 0; }
    body:has(#portal-cliente) > main { max-width: none; width: 100%; }
    [x-cloak] { display: none !important; }

    #portal-cliente {
        --navy: #0b2a3f;
        --navy-2: #123e5a;
        --blue: #1f5f8b;
        --yellow: #f5b01b;
        --line: #e3eaf0;
        color: #1e2f3c;
        background: #f3f6f9;
    }

    html { scroll-behavior: smooth; }
    #servicios, #historial { scroll-margin-top: 5.5rem; }

    #portal-cliente .portal-aside {
        position: fixed;
        overflow: hidden;
        isolation: isolate;
        background: #081f2f;
        border-right: 4px solid var(--yellow);
        transform: translateX(-100%);
        transition: transform .22s ease;
    }

    #portal-cliente .aside-foto {
        position: absolute;
        z-index: -3;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 62% center;
        opacity: .5;
    }

    #portal-cliente .aside-velo {
        position: absolute;
        z-index: -2;
        inset: 0;
        background: linear-gradient(180deg, rgb(8 31 47 / 96%) 0%, rgb(8 31 47 / 82%) 45%, rgb(8 31 47 / 55%) 100%);
    }

    #portal-cliente .portal-aside::after {
        right: 22%;
        width: 34%;
        opacity: .1;
    }

    #portal-cliente .portal-aside.abierto { transform: none; }

    @media (min-width: 1024px) {
        #portal-cliente .portal-aside { transform: none; }
    }

    #portal-cliente .nav-link {
        display: flex;
        align-items: center;
        gap: .85rem;
        width: 100%;
        padding: .7rem 1rem;
        border-radius: .6rem;
        text-align: left;
        font-size: .875rem;
        font-weight: 500;
        color: #d3e0ea;
        border-left: 4px solid transparent;
    }

    #portal-cliente .nav-link:hover {
        background: rgb(255 255 255 / 8%);
        color: #fff;
    }

    #portal-cliente .nav-link.activo {
        background: rgb(255 255 255 / 10%);
        border-left-color: var(--yellow);
        color: var(--yellow);
        font-weight: 700;
    }

    #portal-cliente .panel-head {
        background: linear-gradient(100deg, #315a72 0%, #527d92 100%);
        border-left: 5px solid var(--yellow);
        color: #fff;
    }

    #portal-cliente a:focus-visible,
    #portal-cliente button:focus-visible {
        outline: 3px solid rgb(245 176 27 / 75%);
        outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }

        #portal-cliente *,
        #portal-cliente *::before,
        #portal-cliente *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>

<div id="portal-cliente"
     x-data="{ menu: false, historial: false, modalCuenta: false, modalMotos: false, modalServicio: null }"
     x-on:keydown.escape.window="menu = false; modalCuenta = false; modalMotos = false; modalServicio = null"
     class="min-h-screen w-full">

    {{-- Fondo oscuro para el menú en móvil --}}
    <div x-cloak x-show="menu" x-transition.opacity x-on:click="menu = false"
         class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden"></div>

    {{-- BARRA LATERAL --}}
    <aside class="portal-aside fixed inset-y-0 left-0 z-40 flex w-56 flex-col px-3 py-6"
           :class="{ 'abierto': menu }"
           aria-label="Navegación del portal">

        <img src="{{ $heroFoto }}" alt="" class="aside-foto" aria-hidden="true">
        <div class="aside-velo" aria-hidden="true"></div>

        <div class="px-2 pb-6 text-center">
            <img src="{{ asset('images/logoRAPTOR.png') }}" alt="Logo de RAPTOR" class="mx-auto h-24 w-auto object-contain">
            <p class="mt-2 text-2xl font-black italic tracking-wide text-white">RAPTOR</p>
            <p class="mt-1 text-[11px] italic tracking-[0.08em] text-slate-300/90">Tu moto al día</p>
        </div>

        <nav class="mt-2 flex flex-1 flex-col gap-1">
            <a href="#inicio" class="nav-link activo" x-on:click="menu = false">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 11 9-8 9 8M5 10v10h5v-6h4v6h5V10"/></svg>
                Inicio
            </a>

            <button type="button" class="nav-link" x-on:click="menu = false; modalMotos = true">
                <span class="[&>svg]:h-5 [&>svg]:w-5">
                    {!! $iconoMoto !!}
                </span>
                Mis motocicletas
            </button>

            <a href="#servicios" class="nav-link" x-on:click="menu = false">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a4 4 0 0 0-5.4 5.4L4 17l3 3 5.3-5.3a4 4 0 0 0 5.4-5.4L15 12l-3-3 2.7-2.7Z"/></svg>
                Mis servicios
            </a>

            <button type="button" class="nav-link" x-on:click="menu = false; modalCuenta = true">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4.5 20a7.5 7.5 0 0 1 15 0"/></svg>
                Mi cuenta
            </button>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="nav-link">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9V5.5A2.5 2.5 0 0 0 12.5 3h-5A2.5 2.5 0 0 0 5 5.5v13A2.5 2.5 0 0 0 7.5 21h5a2.5 2.5 0 0 0 2.5-2.5V15m3-6 3 3m0 0-3 3m3-3H10"/></svg>
                Cerrar sesión
            </button>
        </form>
    </aside>

    {{-- CONTENIDO --}}
    <div class="lg:pl-56">

        {{-- BARRA SUPERIOR --}}
        <div class="sticky top-0 z-20 flex h-[68px] items-center justify-between border-b border-[#e3eaf0] bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
            <button type="button" x-on:click="menu = !menu" aria-label="Abrir o cerrar el menú"
                    class="rounded-lg p-2 text-[#0b2a3f] hover:bg-slate-100 lg:invisible">
                <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <div class="flex items-center gap-3 sm:gap-5">
                <a href="#servicios"
                   class="relative rounded-lg p-2 text-[#0b2a3f] hover:bg-slate-100"
                   aria-label="{{ $porAprobar > 0 ? $porAprobar . ' preórdenes por aprobar' : 'Sin preórdenes por aprobar' }}">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9Zm4 10a2 2 0 0 0 4 0"/></svg>
                    @if ($porAprobar > 0)
                        <span class="absolute right-0 top-0 grid h-5 min-w-5 place-items-center rounded-full bg-red-600 px-1 text-[11px] font-bold text-white">{{ $porAprobar }}</span>
                    @endif
                </a>

                <button type="button" x-on:click="modalCuenta = true"
                        class="flex items-center gap-1.5 rounded-full py-1 pl-1 pr-3 hover:bg-slate-100">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-[#0b2a3f] text-sm font-extrabold text-white">{{ $iniciales ?: 'CL' }}</span>
                    <span class="hidden text-sm font-medium text-[#0b2a3f] sm:inline">{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
                    <svg viewBox="0 0 24 24" class="hidden h-4 w-4 text-slate-500 sm:block" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </button>
            </div>
        </div>

        <main id="inicio" class="space-y-6 px-4 py-6 sm:px-6 lg:px-8">

            {{-- ENCABEZADO --}}
            <div>
                <h1 class="text-2xl font-extrabold text-[#0b2a3f] sm:text-3xl">Portal cliente</h1>
                <p class="mt-1 text-sm text-slate-500">Consulta tus motocicletas, servicios y pagos.</p>
            </div>

            {{-- MENSAJES --}}
            @if (session('success') && session('success') !== 'Has aprobado la preorden correctamente.')
                <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-bold">No se pudo completar la operación.</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- RESUMEN --}}
            <section class="grid grid-cols-1 gap-3 md:grid-cols-3">

                <button type="button" x-on:click="modalMotos = true"
                    class="flex items-center gap-3 rounded-xl border border-[#e3eaf0] bg-white p-3.5 text-left shadow-sm hover:border-[#9bb8c6] hover:shadow-md">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#eef3f7] text-[#0b2a3f]">
                        {!! $iconoMoto !!}
                    </span>
                    <span class="flex-1">
                        <span class="block text-sm text-slate-700">Mis motocicletas</span>
                        <span class="mt-0.5 block text-2xl font-bold text-[#0b2a3f]">{{ $motos->count() }}</span>
                        <span class="block text-right text-xs font-medium text-[#1f5f8b]">Ver detalle &rarr;</span>
                    </span>
                </button>

                {{-- SERVICIOS EN PROCESO --}}
                <a href="#servicios"
                   class="flex items-center gap-3 rounded-xl border border-[#e3eaf0] bg-white p-3.5 shadow-sm hover:border-[#9bb8c6] hover:shadow-md">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-amber-50 text-[#0b2a3f]">
                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                        </svg>
                    </span>
                    <span class="flex-1">
                        <span class="block text-sm text-slate-700">Servicios en proceso</span>
                        <span class="mt-0.5 block text-2xl font-bold text-[#0b2a3f]">{{ $sinEntrega->count() }}</span>
                        <span class="block text-right text-xs font-medium text-[#1f5f8b]">Ver seguimiento &rarr;</span>
                    </span>
                </a>

                {{-- SERVICIOS COMPLETADOS --}}
                <a href="#historial" x-on:click="historial = true"
                   class="flex items-center gap-3 rounded-xl border border-[#e3eaf0] bg-white p-3.5 shadow-sm hover:border-[#9bb8c6] hover:shadow-md">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#0b2a3f] text-white ring-2 ring-amber-100">
                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                        </svg>
                    </span>
                    <span class="flex-1">
                        <span class="block text-sm text-slate-700">Servicios completados</span>
                        <span class="mt-0.5 block text-2xl font-bold text-[#0b2a3f]">{{ $entregadas->count() }}</span>
                        <span class="block text-right text-xs font-medium text-[#1f5f8b]">Ver historial &rarr;</span>
                    </span>
                </a>
            </section>

            {{-- AVISO DE APROBACIÓN PENDIENTE --}}
            @if ($porAprobar > 0)
                <div role="status" class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
                    <span class="font-bold">Necesitamos tu respuesta.</span>
                    Tienes {{ $porAprobar }} {{ $porAprobar === 1 ? 'preorden esperando' : 'preórdenes esperando' }} tu aprobación.
                </div>
            @endif

            {{-- MIS SERVICIOS --}}
            <section id="servicios" class="overflow-hidden rounded-xl border border-[#e3eaf0] bg-white shadow-sm">
                <div class="panel-head flex flex-col gap-3 bg-[#315A72] px-5 py-4 text-white sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Mis servicios</h2>
                        <p class="text-sm text-slate-100">Consulta tus motocicletas y los servicios que aún no han sido entregados.</p>
                    </div>
                    <span class="w-fit rounded-full bg-white/15 px-3 py-1 text-sm font-semibold">
                        {{ $sinEntrega->count() }} {{ $sinEntrega->count() === 1 ? 'servicio' : 'servicios' }}
                    </span>
                </div>

                <div class="divide-y divide-[#e3eaf0]">
                    @forelse ($motosConServicios as $moto)
                        @php
                            $servicios = ($serviciosPorMoto[$moto->id] ?? collect())
                                ->filter(fn ($p) => $info[$p->id]['entrega'] === null)
                                ->values();
                        @endphp

                        <article class="px-5 py-5">
                            {{-- INFORMACIÓN DE LA MOTOCICLETA --}}
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <div class="flex min-w-0 flex-1 items-center gap-4">
                                    <span class="grid h-12 w-14 shrink-0 place-items-center rounded-lg bg-[#eef3f7] text-[#0b2a3f]">
                                        {!! $iconoMoto !!}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <h3 class="break-words font-bold text-[#0b2a3f]">
                                            {{ $moto->marca?->nombre_marca ?? 'Marca no disponible' }} {{ $moto->modelo }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-600">
                                            Placa:
                                            <span class="font-semibold text-slate-800">{{ $moto->placa ?: 'No disponible' }}</span>
                                        </p>
                                    </div>
                                </div>

                                <span class="w-fit rounded-full bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-800">
                                    {{ $servicios->count() }} {{ $servicios->count() === 1 ? 'servicio' : 'servicios' }}
                                </span>
                            </div>

                            {{-- SERVICIOS DE ESTA MOTOCICLETA --}}
                            @forelse ($servicios as $preorden)
                                @php $d = $info[$preorden->id]; @endphp

                                <button type="button"
                                        x-on:click="modalServicio = {{ (int) $preorden->id }}"
                                        class="mt-4 flex w-full flex-col gap-3 rounded-xl border border-[#e3eaf0] bg-slate-50 p-4 text-left transition hover:border-[#9bb8c6] hover:bg-white sm:flex-row sm:items-center">

                                    <span class="min-w-0 flex-1">
                                        <span class="block font-bold text-[#0b2a3f]">{{ $d['folioRecepcion'] }}</span>
                                        <span class="mt-1 block text-sm text-slate-600">{{ $d['etapa'] }}</span>
                                    </span>

                                    <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $pills[$d['tono']] }}">
                                        {{ $d['etapa'] }}
                                    </span>

                                    {{-- RESUMEN DE PAGOS --}}
                                    <span class="grid w-full grid-cols-3 gap-1.5 sm:w-auto sm:min-w-[300px]">
                                        <span class="rounded-lg bg-white px-2 py-1.5">
                                            <span class="block text-[9px] font-semibold text-slate-500">Total</span>
                                            <span class="block text-xs font-extrabold text-slate-900">
                                                ${{ number_format($d['totalServicio'], 0, ',', '.') }}
                                            </span>
                                        </span>

                                        <span class="rounded-lg bg-emerald-50 px-2 py-1.5">
                                            <span class="block text-[9px] font-semibold text-emerald-700">Pagado</span>
                                            <span class="block text-xs font-extrabold text-emerald-700">
                                                ${{ number_format($d['pagosConfirmados'], 0, ',', '.') }}
                                            </span>
                                        </span>

                                        <span class="rounded-lg px-2 py-1.5 {{ $d['saldo'] > 0 ? 'bg-amber-50' : 'bg-sky-50' }}">
                                            <span class="block text-[9px] font-semibold {{ $d['saldo'] > 0 ? 'text-amber-700' : 'text-sky-700' }}">Saldo</span>
                                            <span class="block text-xs font-extrabold {{ $d['saldo'] > 0 ? 'text-amber-700' : 'text-sky-700' }}">
                                                ${{ number_format($d['saldo'], 0, ',', '.') }}
                                            </span>
                                        </span>
                                    </span>

                                    <span class="shrink-0 text-xs font-semibold text-[#1f5f8b] sm:pl-2">
                                        Ver detalles &rarr;
                                    </span>
                                </button>
                            @empty
                                <p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-500">
                                    Esta motocicleta no tiene servicios pendientes de entrega.
                                </p>
                            @endforelse
                        </article>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-700">No tienes motocicletas registradas.</p>
                            <p class="mt-1 text-xs text-slate-500">Cuando el taller registre una motocicleta a tu nombre, aparecerá aquí.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- HISTORIAL --}}
            <section id="historial" class="overflow-hidden rounded-xl border border-dashed border-slate-300 bg-slate-50">
                <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-700">Historial de servicios</h2>
                        <p class="text-sm text-slate-500">
                            Motocicletas ya entregadas: {{ $motosEntregadas->count() }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($motosEntregadas as $moto)
                            <a href="{{ route('cliente.historial.moto.pdf', $moto->id) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="Descargar historial de {{ $moto->placa }}"
                               class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b2a3f] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#143d59]">

                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/>
                                </svg>

                                PDF historial de la moto
                                <span class="font-extrabold">{{ $moto->placa }}</span>
                            </a>
                        @endforeach

                        <button type="button"
                                x-on:click="historial = !historial"
                                :aria-expanded="historial"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">

                            <span x-text="historial ? 'Ocultar' : 'Mostrar'"></span>

                            <svg viewBox="0 0 24 24" class="h-5 w-5 transition-transform"
                                 :class="historial ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-cloak x-show="historial" class="divide-y divide-slate-200 border-t border-slate-200 bg-white">
                    @forelse ($entregadas as $preorden)
                        @php $d = $info[$preorden->id]; @endphp

                        <button type="button" x-on:click="modalServicio = {{ (int) $preorden->id }}"
                                class="flex w-full items-center gap-4 px-5 py-3.5 text-left hover:bg-slate-50">
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-bold text-slate-700">{{ $d['folioRecepcion'] }}</span>
                                <span class="block truncate text-sm text-slate-600">
                                    {{ $d['moto']?->marca?->nombre_marca ?? 'Motocicleta' }} {{ $d['moto']?->modelo ?? '' }}
                                    &mdash; Placa {{ $d['moto']?->placa ?? 'No disponible' }}
                                </span>
                            </span>

                            <span class="hidden text-sm text-slate-500 sm:block">
                                Entregada el {{ $fechaCorta($d['entrega']?->fecha_entrega) }}
                            </span>

                            <span class="hidden font-semibold text-slate-700 md:block">
                                ${{ number_format($d['totalServicio'], 0, ',', '.') }}
                            </span>

                            <span class="shrink-0 text-xs font-semibold text-[#1f5f8b]">Ver detalles &rarr;</span>
                        </button>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <p class="text-sm font-semibold text-slate-700">Todavía no tienes servicios entregados.</p>
                            <p class="mt-1 text-xs text-slate-500">Aparecerán aquí cuando el taller registre la entrega de la motocicleta.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- PIE DE PÁGINA --}}
            <footer class="flex flex-col items-center justify-between gap-2 border-t-[3px] border-[#f5b01b] bg-white px-4 py-4 text-sm text-slate-500 sm:flex-row sm:px-6">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'RAPTOR') }}. Todos los derechos reservados.</p>
                <p>Versión {{ config('app.version', '2.0') }}</p>
            </footer>
        </main>
    </div>

    {{-- MODALES --}}

    {{-- DATOS PERSONALES --}}
    <div x-cloak x-show="modalCuenta" x-transition.opacity x-on:click.self="modalCuenta = false"
         class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/55 p-4"
         role="dialog" aria-modal="true" aria-labelledby="titulo-cuenta">

        <div x-show="modalCuenta" x-transition class="my-auto w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="panel-head flex items-center justify-between px-6 py-5">
                <div>
                    <p class="text-xs font-semibold text-[#f6d9a8]">Portal de clientes</p>
                    <h2 id="titulo-cuenta" class="mt-1 text-xl font-extrabold">Mis datos personales</h2>
                </div>

                <button type="button" x-on:click="modalCuenta = false" aria-label="Cerrar ventana" class="rounded-lg p-2 hover:bg-white/15">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Nombre completo</p>
                    <p class="mt-1 break-words font-semibold text-slate-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">Correo electrónico</p>
                    <p class="mt-1 break-words font-semibold text-slate-800">{{ $cliente->correo_electronico }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">Documento</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500">Teléfono</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $cliente->telefono ?: 'No registrado' }}</p>
                </div>
            </div>

            <div class="border-t border-[#e3eaf0] bg-slate-50 px-6 py-4 text-right">
                <button type="button" x-on:click="modalCuenta = false" class="rounded-xl bg-[#0b2a3f] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#123e5a]">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- MIS MOTOCICLETAS --}}
    <div x-cloak x-show="modalMotos" x-transition.opacity x-on:click.self="modalMotos = false"
         class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/55 p-4"
         role="dialog" aria-modal="true" aria-labelledby="titulo-mis-motos">

        <div x-show="modalMotos" x-transition class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="panel-head flex items-center justify-between px-6 py-5">
                <div>
                    <p class="text-xs font-semibold text-[#f6d9a8]">Vehículos registrados</p>
                    <h2 id="titulo-mis-motos" class="mt-1 text-xl font-extrabold">Mis motocicletas</h2>
                    <p class="mt-1 text-sm text-white/80">
                        Total: {{ $motos->count() }} {{ $motos->count() === 1 ? 'motocicleta' : 'motocicletas' }}
                    </p>
                </div>

                <button type="button" x-on:click="modalMotos = false" aria-label="Cerrar ventana" class="rounded-lg p-2 hover:bg-white/15">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <div class="max-h-[65vh] space-y-4 overflow-y-auto p-6">
                @forelse ($motos as $moto)
                    <article class="rounded-xl border border-[#e3eaf0] bg-slate-50 p-4 sm:p-5">
                        <div class="flex items-start gap-4">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-[#e8f0f4] text-[#0b2a3f]">{!! $iconoMoto !!}</span>

                            <div class="min-w-0 flex-1">
                                <h3 class="break-words text-lg font-extrabold text-[#0b2a3f]">
                                    {{ $moto->marca?->nombre_marca ?? 'Marca no disponible' }} {{ $moto->modelo }}
                                </h3>

                                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div class="rounded-lg border border-[#e3eaf0] bg-white p-3">
                                        <p class="text-xs font-semibold text-slate-500">Placa</p>
                                        <p class="mt-1 font-extrabold tracking-widest text-[#0b2a3f]">{{ $moto->placa ?: 'No registrada' }}</p>
                                    </div>

                                    <div class="rounded-lg border border-[#e3eaf0] bg-white p-3">
                                        <p class="text-xs font-semibold text-slate-500">Año</p>
                                        <p class="mt-1 font-semibold text-slate-800">{{ $moto->anio ?: 'No registrado' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center">
                        <p class="font-bold text-[#0b2a3f]">No tienes motocicletas registradas.</p>
                        <p class="mt-1 text-sm text-slate-500">Si consideras que es un error, comunícate con el taller.</p>
                    </div>
                @endforelse
            </div>

            <div class="border-t border-[#e3eaf0] bg-slate-50 px-6 py-4 text-right">
                <button type="button" x-on:click="modalMotos = false" class="rounded-xl bg-[#0b2a3f] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#123e5a]">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- DETALLE DE CADA PREORDEN --}}
    @foreach ($todas as $preorden)
        @php
            $d = $info[$preorden->id];
            $diagnostico = $d['diagnostico'];
            $orden = $d['orden'];
            $entrega = $d['entrega'];
            $totalServicio = $d['totalServicio'];
            $pagosConfirmados = $d['pagosConfirmados'];
            $saldo = $d['saldo'];
            $estadoPreorden = $d['estado'];
        @endphp

        <div x-cloak
             x-show="modalServicio === {{ (int) $preorden->id }}"
             x-transition.opacity
             x-on:click.self="modalServicio = null"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/60 p-3 sm:p-6"
             role="dialog" aria-modal="true"
             aria-labelledby="titulo-historial-{{ $preorden->id }}">

            <div x-show="modalServicio === {{ (int) $preorden->id }}" x-transition
                 class="my-auto flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

                <div class="panel-head flex items-center justify-between px-5 py-4 sm:px-6">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-[#f6d9a8]">Detalles del servicio</p>
                        <h3 id="titulo-historial-{{ $preorden->id }}" class="mt-1 text-lg font-bold">
                            {{ $d['folioRecepcion'] }}
                        </h3>
                        <p class="mt-1 text-xs text-slate-200">
                            @if ($entrega)
                                Entrega registrada: {{ $entrega->fecha_entrega }}
                            @else
                                Consulta el estado actual de tu servicio.
                            @endif
                        </p>
                    </div>

                    <button type="button" x-on:click="modalServicio = null" aria-label="Cerrar detalles del servicio" class="ml-3 rounded-lg p-2 hover:bg-white/15">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <div class="space-y-6 overflow-y-auto p-5 sm:p-6">

                    {{-- ESTADO GENERAL --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="rounded-full px-3 py-1.5 text-sm font-bold {{ $pills[$d['tono']] }}">{{ $d['etapa'] }}</span>

                        <span class="text-sm text-slate-600">
                            {{ $d['moto']?->marca?->nombre_marca ?? 'Motocicleta' }} {{ $d['moto']?->modelo ?? '' }}
                            &mdash; Placa <span class="font-bold text-slate-800">{{ $d['moto']?->placa ?? 'No disponible' }}</span>
                        </span>
                    </div>

                    {{-- DOCUMENTOS PDF DEL SERVICIO --}}
                    @if ($entrega && $orden && $d['moto'])
                        <section class="flex flex-col gap-3 rounded-xl border border-[#e3eaf0] bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h4 class="font-bold text-[#0b2a3f]">Documentos del servicio</h4>
                                <p class="mt-1 text-xs text-slate-500">
                                    Consulta o conserva los informes de los servicios realizados.
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <a href="{{ route('cliente.historial.servicio.pdf', $orden->id) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b2a3f] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#123e5a]">

                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M8 15h8M8 18h6"/>
                                    </svg>

                                    PDF Informe del servicio
                                </a>
                            </div>
                        </section>
                    @endif

                    {{-- RESUMEN FINANCIERO --}}
                    <section class="grid grid-cols-3 gap-2">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                            <p class="text-[10px] font-semibold text-slate-500">Total</p>
                            <p class="text-sm font-extrabold text-slate-900">
                                ${{ number_format($totalServicio, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-2.5">
                            <p class="text-[10px] font-semibold text-emerald-700">Pagado</p>
                            <p class="text-sm font-extrabold text-emerald-700">
                                ${{ number_format($pagosConfirmados, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg border p-2.5 {{ $saldo > 0 ? 'border-amber-200 bg-amber-50' : 'border-sky-200 bg-sky-50' }}">
                            <p class="text-[10px] font-semibold {{ $saldo > 0 ? 'text-amber-700' : 'text-sky-700' }}">Saldo</p>
                            <p class="text-sm font-extrabold {{ $saldo > 0 ? 'text-amber-700' : 'text-sky-700' }}">
                                ${{ number_format($saldo, 0, ',', '.') }}
                            </p>
                        </div>
                    </section>

                    {{-- AVANCE DEL SERVICIO --}}
                    @if ($estadoPreorden !== 'rechazada')
                        @php
                            $prog = $d['progreso'];
                            $colorArco = ['azul' => '#1f5f8b', 'verde' => '#059669', 'ambar' => '#f5b01b'][$d['tono']] ?? '#1f5f8b';
                            $pasos = [
                                ['Recepción', 15],
                                ['Diagnóstico y presupuesto', 30],
                                ['Aprobación del cliente', 45],
                                ['Reparación', 90],
                                ['Pago', 95],
                                ['Entrega', 100],
                            ];
                            $actual = collect($pasos)->search(fn ($paso) => $prog < $paso[1]);
                        @endphp

                        <section class="rounded-xl border border-[#e3eaf0] bg-slate-50 p-4 sm:p-5">
                            <h4 class="font-bold text-[#0b2a3f]">Avance del servicio</h4>

                            <div class="mt-4 flex flex-col items-center gap-6 sm:flex-row sm:gap-10">
                                <div class="relative h-36 w-36 shrink-0">
                                    <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90" role="img"
                                         aria-label="Avance del servicio: {{ $prog }} por ciento">
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="#dfe7ee" stroke-width="14"/>
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="{{ $colorArco }}" stroke-width="14"
                                                stroke-linecap="round"
                                                stroke-dasharray="{{ round($circ * $prog / 100, 2) }} {{ round($circ, 2) }}"/>
                                    </svg>

                                    <div class="absolute inset-0 grid place-content-center text-center">
                                        <span class="text-3xl font-bold leading-none text-[#0b2a3f]">{{ $prog }}%</span>
                                        <span class="mt-1 text-xs text-slate-500">completado</span>
                                    </div>
                                </div>

                                <ol class="w-full space-y-2.5 text-sm">
                                    @foreach ($pasos as $i => $paso)
                                        @php
                                            $hecho = $prog >= $paso[1];
                                            $enCurso = $actual === $i;
                                        @endphp

                                        <li class="flex items-center gap-3">
                                            @if ($hecho)
                                                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-600 text-white">
                                                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                                                </span>
                                            @elseif ($enCurso)
                                                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full border-2 border-[#1f5f8b] bg-white">
                                                    <span class="h-2.5 w-2.5 rounded-full bg-[#1f5f8b]"></span>
                                                </span>
                                            @else
                                                <span class="h-6 w-6 shrink-0 rounded-full border-2 border-slate-300 bg-white"></span>
                                            @endif

                                            <span class="{{ $hecho ? 'text-slate-700' : ($enCurso ? 'font-bold text-[#0b2a3f]' : 'text-slate-400') }}">
                                                {{ $paso[0] }}
                                            </span>

                                            @if ($enCurso)
                                                <span class="rounded-full bg-sky-100 px-2 py-0.5 text-xs font-semibold text-sky-800">En curso</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </section>
                    @endif

                    {{-- DIAGNÓSTICO --}}
                    <section>
                        <h4 class="font-bold text-[#0b2a3f]">Diagnóstico y presupuesto</h4>

                        <div class="mt-3 rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold text-slate-500">Diagnóstico</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                                {{ $diagnostico?->descripcion ?: 'No hay descripción del diagnóstico disponible.' }}
                            </p>

                            @if ($preorden->descripcion)
                                <div class="mt-4 border-t border-[#e3eaf0] pt-4">
                                    <p class="text-xs font-semibold text-slate-500">Descripción de la preorden</p>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $preorden->descripcion }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-[#e3eaf0] p-4">
                                <p class="text-sm text-slate-500">Mano de obra</p>
                                <p class="mt-1 text-lg font-bold">${{ number_format((float) $preorden->mano_obra, 0, ',', '.') }}</p>
                            </div>

                            <div class="rounded-xl border border-[#e3eaf0] p-4">
                                <p class="text-sm text-slate-500">Total de la preorden</p>
                                <p class="mt-1 text-lg font-extrabold text-slate-900">${{ number_format((float) $preorden->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>

                    {{-- REPUESTOS --}}
                    <section class="border-t border-[#e3eaf0] pt-5">
                        <h4 class="font-bold text-[#0b2a3f]">Repuestos presupuestados</h4>

                        @if ($preorden->repuestos->isNotEmpty())
                            <div class="mt-3 overflow-x-auto rounded-xl border border-[#e3eaf0]">
                                <table class="w-full min-w-[440px] text-left text-sm">
                                    <thead class="bg-[#eaf2f6] text-xs text-[#0b2a3f]">
                                        <tr>
                                            <th class="px-3 py-3 font-bold">Repuesto</th>
                                            <th class="px-3 py-3 text-right font-bold">Cant.</th>
                                            <th class="px-3 py-3 text-right font-bold">Precio</th>
                                            <th class="px-3 py-3 text-right font-bold">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($preorden->repuestos as $detalle)
                                            <tr>
                                                <td class="px-3 py-3">{{ $detalle->repuesto->nombre ?? 'Repuesto' }}</td>
                                                <td class="px-3 py-3 text-right">{{ $detalle->cantidad }}</td>
                                                <td class="px-3 py-3 text-right">${{ number_format((float) $detalle->precio_unitario, 0, ',', '.') }}</td>
                                                <td class="px-3 py-3 text-right font-bold">${{ number_format((float) $detalle->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mt-3 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Esta preorden no tiene repuestos asociados.</p>
                        @endif
                    </section>

                    {{-- APROBACIÓN O RECHAZO --}}
                    @if ($estadoPreorden === 'pendiente')
                        <section class="rounded-xl border border-amber-200 bg-amber-50 p-4 sm:p-5">
                            <h4 class="font-bold text-amber-900">Tu decisión es necesaria</h4>

                            <p class="mt-2 text-sm leading-6 text-amber-800">
                                Revisa el diagnóstico, los repuestos y el valor total antes de responder.
                                Aprobar no inicia automáticamente la reparación: el taller debe crear la orden de trabajo.
                            </p>

                            <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                <form method="POST" action="{{ route('cliente.preorden.aprobar', $preorden->id) }}"
                                      onsubmit="return confirm('¿Confirmas que deseas aprobar esta preorden?');">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="w-full rounded-xl bg-[#0b2a3f] px-5 py-3 text-sm font-bold text-white hover:bg-[#123e5a] sm:w-auto">
                                        Aprobar preorden
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('cliente.preorden.rechazar', $preorden->id) }}"
                                      onsubmit="return confirm('¿Confirmas que deseas rechazar esta preorden?');">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="w-full rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-bold text-red-700 hover:bg-red-50 sm:w-auto">
                                        Rechazar preorden
                                    </button>
                                </form>
                            </div>
                        </section>
                    @endif

                    {{-- SEGUIMIENTO --}}
                    <section class="border-t border-[#e3eaf0] pt-5">
                        <h4 class="font-bold text-[#0b2a3f]">Avance de la reparación</h4>

                        @if ($estadoPreorden === 'rechazada')
                            <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-700">
                                Esta preorden fue rechazada. No se debe generar una orden de trabajo a partir de ella.
                            </div>

                        @elseif ($orden)
                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="rounded-xl border border-[#e3eaf0] p-4">
                                    <p class="text-xs font-semibold text-slate-500">Estado del trabajo</p>
                                    <p class="mt-2 font-bold text-[#0b2a3f]">{{ ucfirst(str_replace('_', ' ', $orden->estado)) }}</p>
                                </div>

                                <div class="rounded-xl border border-[#e3eaf0] p-4">
                                    <p class="text-xs font-semibold text-slate-500">Mecánico asignado</p>
                                    <p class="mt-2 font-bold">
                                        @if ($orden->mecanico)
                                            {{ $orden->mecanico->nombre }} {{ $orden->mecanico->apellido ?? '' }}
                                        @else
                                            No asignado
                                        @endif
                                    </p>
                                </div>

                                <div class="rounded-xl border border-[#e3eaf0] p-4">
                                    <p class="text-xs font-semibold text-slate-500">Inicio de reparación</p>
                                    <p class="mt-2 text-sm font-semibold">{{ $orden->fecha_inicio ?: 'Pendiente de registro' }}</p>
                                </div>

                                <div class="rounded-xl border border-[#e3eaf0] p-4">
                                    <p class="text-xs font-semibold text-slate-500">Finalización</p>
                                    <p class="mt-2 text-sm font-semibold">{{ $orden->fecha_fin ?: 'Aún no finalizada' }}</p>
                                </div>
                            </div>

                            @if ($orden->observaciones)
                                <div class="mt-3 rounded-xl bg-[#eef3f7] p-4">
                                    <p class="text-xs font-semibold text-slate-500">Observaciones del taller</p>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $orden->observaciones }}</p>
                                </div>
                            @endif

                        @elseif ($estadoPreorden === 'aprobada')
                            <div class="mt-3 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm leading-6 text-sky-900">
                                <p class="font-bold">Preorden aprobada</p>
                                <p class="mt-1">
                                    Has aprobado esta preorden. El taller debe crear la orden de trabajo para que puedas consultar el avance de la reparación.
                                </p>
                            </div>

                        @else
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                El seguimiento estará disponible cuando corresponda según el proceso del taller.
                            </div>
                        @endif

                        {{-- HISTORIAL DE PAGOS --}}
                        @if ($d['pagos']->isNotEmpty())
                            <section class="mt-5 rounded-xl border border-[#e3eaf0] p-4 sm:p-5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h5 class="font-bold text-[#0b2a3f]">Historial de pagos</h5>
                                        <p class="mt-1 text-xs text-slate-500">Solo los pagos confirmados se descuentan del saldo.</p>
                                    </div>

                                    <span class="w-fit rounded-full px-3 py-1.5 text-xs font-bold {{ $saldo > 0 ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800' }}">
                                        {{ $saldo > 0 ? 'Pago pendiente' : 'Saldo cubierto' }}
                                    </span>
                                </div>

                                <div class="mt-4 overflow-x-auto rounded-xl border border-[#e3eaf0]">
                                    <table class="w-full min-w-[440px] text-left text-sm">
                                        <thead class="bg-[#eaf2f6] text-xs text-[#0b2a3f]">
                                            <tr>
                                                <th class="px-3 py-3 font-bold">Fecha</th>
                                                <th class="px-3 py-3 font-bold">Medio</th>
                                                <th class="px-3 py-3 text-right font-bold">Valor</th>
                                                <th class="px-3 py-3 text-right font-bold">Estado</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-slate-100">
                                            @foreach ($d['pagos'] as $pago)
                                                <tr>
                                                    <td class="px-3 py-3">{{ $pago->fecha_pago ?: '—' }}</td>
                                                    <td class="px-3 py-3">{{ ucfirst($pago->tipo_pago) }}</td>
                                                    <td class="px-3 py-3 text-right">${{ number_format((float) $pago->valor, 0, ',', '.') }}</td>
                                                    <td class="px-3 py-3 text-right">
                                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $pago->estado === 'confirmado' ? 'bg-emerald-100 text-emerald-800' : ($pago->estado === 'rechazado' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800') }}">
                                                            {{ ucfirst($pago->estado) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        @else
                            <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                                Aún no hay pagos registrados para esta preorden.
                            </p>
                        @endif

                        {{-- ENTREGA --}}
                        @if ($orden)
                            <section class="mt-5 rounded-xl border border-[#e3eaf0] p-4 sm:p-5">
                                <h5 class="font-bold text-[#0b2a3f]">Entrega de la motocicleta</h5>
                                <p class="mt-1 text-xs text-slate-500">Estado de devolución del vehículo</p>

                                @if ($entrega)
                                    <div class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                        <p class="font-bold text-emerald-800">Entrega registrada el {{ $entrega->fecha_entrega }}.</p>

                                        @if ($entrega->observaciones)
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-emerald-800">{{ $entrega->observaciones }}</p>
                                        @endif
                                    </div>

                                @elseif ($saldo > 0)
                                    <p class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                                        La entrega está pendiente porque todavía existe un saldo por pagar.
                                    </p>

                                @elseif ($orden->estado !== 'finalizada')
                                    <p class="mt-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                        La reparación aún no figura como finalizada.
                                    </p>

                                @else
                                    <p class="mt-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                        La reparación está finalizada. El taller debe registrar la entrega cuando corresponda.
                                    </p>
                                @endif
                            </section>
                        @endif
                    </section>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 px-5 py-3 text-right sm:px-6">
                    <button type="button" x-on:click="modalServicio = null"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Cerrar detalles
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection