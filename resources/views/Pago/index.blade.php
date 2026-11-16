
@extends('layouts.app')

@section('title', 'Pagos')
@section('page-title', 'Gestión de pagos')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-lg font-bold text-gray-800">
                Gestión de pagos
            </h1>

            <p class="mt-1 text-xs text-gray-500">
                Consulta y administra los pagos de las órdenes de trabajo.
            </p>
        </div>

        <a href="{{ route('pago.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#26485c]">

            <span>Registrar pago</span>
            <span class="text-base leading-none">+</span>

        </a>

    </div>

    {{-- ========================================================= --}}
    {{-- RESUMEN SENCILLO POR RECEPCIÓN --}}
    {{-- ========================================================= --}}

    <div class="space-y-3">

        @forelse ($resumenRecepciones as $preorden)

            @php
                $inventario = $preorden->diagnostico?->inventario;
                $moto = $inventario?->moto;
                $cliente = $moto?->cliente;

                $ordenTrabajo = $preorden->ordenTrabajo;

                $recepcion = $inventario?->recepcion;

                $folioRecepcion = 'RECEPCIÓN # ' . str_pad(
                    (string) ($recepcion ?? ''),
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                $totalAprobado = (float) ($preorden->total ?? 0);

                $totalPagado = (float) (
                    $preorden->pagos
                        ->where('estado', 'confirmado')
                        ->sum('valor')
                );

                $saldoPendiente = max(0, $totalAprobado - $totalPagado);

                if (!$ordenTrabajo) {
                    $estadoFinanciero = 'Pendiente de OT';
                    $clasesEstado = 'text-amber-700';
                } elseif ($totalPagado <= 0) {
                    $estadoFinanciero = 'Sin abonos';
                    $clasesEstado = 'text-gray-500';
                } elseif ($saldoPendiente > 0.009) {
                    $estadoFinanciero = 'Pago parcial';
                    $clasesEstado = 'text-amber-700';
                } else {
                    $estadoFinanciero = 'Pagado';
                    $clasesEstado = 'text-blue-700';
                }
            @endphp

            <div class="rounded-xl border border-[#dce8ed] bg-white px-4 py-3 shadow-sm transition hover:bg-[#eaf2f6]">

                {{-- Recepción y estado --}}
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-xs font-bold text-[#315A72]">
                            {{ $folioRecepcion }}
                        </p>

                        <p class="mt-1 text-xs text-gray-600">
                            {{ $moto?->placa ?? 'Sin placa' }}
                            ·
                            {{ $cliente?->nombre ?? 'Sin cliente' }}
                            {{ $cliente?->apellido ?? '' }}
                        </p>
                    </div>

                    <span class="text-xs font-semibold {{ $clasesEstado }}">
                        {{ $estadoFinanciero }}
                    </span>

                </div>

                {{-- Valores --}}
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3">

                    <div>
                        <p class="text-[11px] text-gray-500">
                            Total aprobado
                        </p>

                        <p class="text-sm font-semibold text-gray-800">
                            ${{ number_format($totalAprobado, 2, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] text-gray-500">
                            Pagado
                        </p>

                        <p class="text-sm font-semibold text-green-700">
                            ${{ number_format($totalPagado, 2, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] text-gray-500">
                            Saldo pendiente
                        </p>

                        <p class="text-sm font-semibold {{ $saldoPendiente > 0.009 ? 'text-amber-700' : 'text-blue-700' }}">
                            ${{ number_format($saldoPendiente, 2, ',', '.') }}
                        </p>
                    </div>

                </div>

                {{-- Acción --}}
                @if ($saldoPendiente > 0.009)

                    <div class="mt-3 flex justify-end border-t border-[#e5edf1] pt-2">

                        <a href="{{ route('pago.create', [
                                'id_preorden' => $preorden->id,
                                'id_ordentrabajo' => $ordenTrabajo?->id
                            ]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#26485c]">

                            Registrar pago
                            <span class="text-sm">+</span>

                        </a>

                    </div>

                @elseif (!$ordenTrabajo)

                    <p class="mt-3 border-t border-[#e5edf1] pt-2 text-xs text-amber-700">
                        Esta preorden todavía no tiene una orden de trabajo.
                    </p>

                @endif

            </div>

        @empty

            <div class="rounded-xl border border-[#dce8ed] bg-white px-4 py-6 text-center">

                <p class="text-xs text-gray-500">
                    No hay preórdenes aprobadas.
                </p>

            </div>

        @endforelse

    </div>

    {{-- ========================================================= --}}
    {{-- TABLA DE PAGOS REGISTRADOS --}}
    {{-- ========================================================= --}}

    <div class="space-y-3 pt-2">

        <div>
            <h2 class="text-sm font-bold text-gray-800">
                Historial de pagos
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Consulta y administra los pagos individuales registrados.
            </p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-[#dce8ed] bg-white shadow-sm">

            <table class="min-w-full divide-y divide-[#e5edf1] text-xs">

                <thead class="bg-[#f0f5f7]">

                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Pago
                        </th>

                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Fecha
                        </th>

                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Motocicleta
                        </th>

                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Cliente
                        </th>

                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Valor
                        </th>

                        <th class="px-4 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Estado
                        </th>

                        <th class="px-4 py-3 text-center text-[11px] font-semibold uppercase tracking-wider text-[#315A72]">
                            Acciones
                        </th>
                    </tr>

                </thead>

                @forelse ($pagos as $pago)

                    @php
                        /*
                        * El pago se relaciona directamente con la preorden.
                        * La OT puede existir o todavía no haberse generado.
                        */
                        $preorden = $pago->preorden;

                        $inventario = $preorden?->diagnostico?->inventario;
                        $moto = $inventario?->moto;
                        $cliente = $moto?->cliente;

                        $ordenTrabajo = $pago->ordenTrabajo;

                        /*
                        * Folio unificado de recepción.
                        */
                        $recepcion = $inventario?->recepcion;

                        $folioRecepcion = $recepcion
                            ? 'RECEPCIÓN # ' . str_pad((string) $recepcion, 3, '0', STR_PAD_LEFT)
                            : 'RECEPCIÓN sin folio';

                        /*
                        * Resumen económico de la preorden.
                        * Los pagos confirmados se consultan desde la preorden,
                        * independientemente de que exista una OT.
                        */
                        $totalOT = (float) ($preorden?->total ?? 0);

                        $totalPagado = (float) (
                            $preorden?->pagos
                                ?->where('estado', 'confirmado')
                                ->sum('valor') ?? 0
                        );

                        $saldoPendiente = max(0, $totalOT - $totalPagado);

                        $estadoPago = [
                            'pendiente' => [
                                'texto' => 'Pendiente',
                                'clases' => 'bg-amber-100 text-amber-800',
                            ],
                            'confirmado' => [
                                'texto' => 'Confirmado',
                                'clases' => 'bg-blue-100 text-blue-800',
                            ],
                            'rechazado' => [
                                'texto' => 'Rechazado',
                                'clases' => 'bg-red-100 text-red-800',
                            ],
                        ];

                        $estadoActual = $estadoPago[$pago->estado] ?? [
                            'texto' => ucfirst($pago->estado ?? 'Sin estado'),
                            'clases' => 'bg-gray-100 text-gray-700',
                        ];

                        $tipoPago = [
                            'efectivo' => 'Efectivo',
                            'transferencia' => 'Transferencia',
                            'tarjeta' => 'Tarjeta',
                        ];
                    @endphp

                    <tbody x-data="{
                        modalAbierto: false,
                        confirmarAbierto: false
                    }"
                    class="divide-y divide-[#e5edf1]">

                        {{-- Fila del pago --}}
                        <tr class="transition hover:bg-[#eaf1f4]">

                            {{-- Pago --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <span class="block h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'hand-coins'])
                                        </span>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">
                                            {{ $folioRecepcion }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Fecha --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $pago->fecha_pago
                                        ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y')
                                        : 'Sin fecha' }}
                                </span>
                            </td>

                            {{-- Motocicleta --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $moto?->placa ?? 'Sin placa' }}
                                </span>
                            </td>

                            {{-- Cliente --}}
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-700">
                                    {{ $cliente?->nombre ?? 'Sin cliente' }}
                                    {{ $cliente?->apellido ?? '' }}
                                </span>
                            </td>

                            {{-- Valor --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="text-xs font-semibold text-gray-800">
                                    ${{ number_format($pago->valor, 2, ',', '.') }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $estadoActual['clases'] }}">
                                    {{ $estadoActual['texto'] }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                <div class="flex items-center justify-center gap-3">

                                    {{-- Ver detalles --}}
                                    <button type="button"
                                            @click="modalAbierto = true"
                                            class="rounded-lg p-1.5 text-[#315A72] transition hover:bg-[#eaf2f6]"
                                            title="Ver detalles"
                                            aria-label="Ver detalles del pago">

                                        <svg class="h-[18px] w-[18px]"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>

                                    {{-- Editar --}}
                                    <a href="{{ route('pago.edit', $pago->id) }}"
                                       class="rounded-lg p-1.5 text-blue-600 transition hover:bg-blue-50"
                                       title="Editar"
                                       aria-label="Editar pago">

                                        <svg class="h-[18px] w-[18px]"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />

                                        </svg>

                                    </a>

                                    {{-- Eliminar --}}
                                    @if ($pago->estado !== 'confirmado')

                                        <form action="{{ route('pago.destroy', $pago->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este pago?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg p-1.5 text-red-600 transition hover:bg-red-50"
                                                    title="Eliminar"
                                                    aria-label="Eliminar pago">

                                                <svg class="h-[18px] w-[18px]"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                                                </svg>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        {{-- Modal de detalles --}}
                        <tr x-cloak
                            x-show="modalAbierto"
                            @keydown.escape.window="modalAbierto = false">

                            <td colspan="7" class="!p-0">

                                <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/50 p-4"
                                     @click.self="modalAbierto = false">

                                    <div x-show="modalAbierto"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

                                        {{-- Encabezado del modal --}}
                                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">

                                            <div class="flex items-center gap-3">

                                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                                    <span class="block h-5 w-5">
                                                        @include('layouts.partials.icons', ['icon' => 'hand-coins'])
                                                    </span>
                                                </div>

                                                <div>
                                                    <h2 class="text-base font-bold text-gray-800">
                                                        Detalles del pago
                                                    </h2>

                                                    <p class="text-xs text-gray-500">
                                                        Información del abono registrado
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Cerrar --}}
                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                title="Cerrar"
                                                aria-label="Cerrar modal"
                                                class="ml-3 flex-shrink-0 rounded-lg p-2 text-gray-500 transition hover:bg-white hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                            >
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>

                                            </button>

                                        </div>

                                        {{-- Contenido --}}
                                        <div class="space-y-5 px-5 py-4">

                                            {{-- Información del pago --}}
                                            <div>
                                                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-[#315A72]">
                                                    Información del pago
                                                </h3>

                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                                    <div class="rounded-lg bg-gray-50 p-3">
                                                        <p class="text-[11px] text-gray-500">
                                                            Folio de recepción
                                                        </p>

                                                        <p class="mt-1 text-xs font-semibold text-gray-800">
                                                            {{ $folioRecepcion }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg bg-gray-50 p-3">
                                                        <p class="text-[11px] text-gray-500">
                                                            Fecha del pago
                                                        </p>

                                                        <p class="mt-1 text-xs font-semibold text-gray-800">
                                                            {{ $pago->fecha_pago
                                                                ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y')
                                                                : 'Sin fecha' }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg bg-gray-50 p-3">
                                                        <p class="text-[11px] text-gray-500">
                                                            Tipo de pago
                                                        </p>

                                                        <p class="mt-1 text-xs font-semibold text-gray-800">
                                                            {{ $tipoPago[$pago->tipo_pago] ?? ucfirst($pago->tipo_pago ?? 'No especificado') }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-lg bg-gray-50 p-3">
                                                        <p class="text-[11px] text-gray-500">
                                                            Estado
                                                        </p>

                                                        <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $estadoActual['clases'] }}">
                                                            {{ $estadoActual['texto'] }}
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- Motocicleta y cliente --}}
                                            <div>
                                                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-[#315A72]">
                                                    Datos de la motocicleta y el cliente
                                                </h3>

                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                                    <div>
                                                        <p class="text-[11px] text-gray-500">
                                                            Placa
                                                        </p>

                                                        <p class="mt-1 text-xs font-medium text-gray-800">
                                                            {{ $moto?->placa ?? 'Sin placa' }}
                                                        </p>
                                                    </div>

                                                    <div class="sm:col-span-2">
                                                        <p class="text-[11px] text-gray-500">
                                                            Cliente
                                                        </p>

                                                        <p class="mt-1 text-xs font-medium text-gray-800">
                                                            {{ $cliente?->nombre ?? 'Sin cliente' }}
                                                            {{ $cliente?->apellido ?? '' }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- Resumen económico de la orden --}}
                                            <div>
                                                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-[#315A72]">
                                                    Resumen económico de la orden
                                                </h3>

                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                                                    <div class="rounded-xl border border-gray-200 bg-white p-3">
                                                        <p class="text-[11px] text-gray-500">
                                                            Total de la orden
                                                        </p>

                                                        <p class="mt-2 text-base font-bold text-gray-800">
                                                            ${{ number_format($totalOT, 2, ',', '.') }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl border border-green-100 bg-green-50 p-3">
                                                        <p class="text-[11px] text-green-700">
                                                            Total pagado
                                                        </p>

                                                        <p class="mt-2 text-base font-bold text-green-800">
                                                            ${{ number_format($totalPagado, 2, ',', '.') }}
                                                        </p>

                                                        <p class="mt-1 text-[11px] text-green-700">
                                                            Solo pagos confirmados
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl border border-amber-100 bg-amber-50 p-3">
                                                        <p class="text-[11px] text-amber-700">
                                                            Saldo pendiente
                                                        </p>

                                                        <p class="mt-2 text-base font-bold text-amber-800">
                                                            ${{ number_format($saldoPendiente, 2, ',', '.') }}
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- Valor individual del pago --}}
                                            <div class="rounded-xl border border-[#dbe7ed] bg-[#f0f5f7] p-3">

                                                <p class="text-xs font-medium text-[#315A72]">
                                                    Valor del pago
                                                </p>

                                                <p class="mt-1 text-xl font-bold text-[#315A72]">
                                                    ${{ number_format($pago->valor, 2, ',', '.') }}
                                                </p>

                                            </div>

                                            {{-- Observaciones --}}
                                            @if ($pago->observaciones)

                                                <div>
                                                    <h3 class="mb-2 text-xs font-semibold uppercase tracking-wide text-[#315A72]">
                                                        Observaciones
                                                    </h3>

                                                    <p class="whitespace-pre-line break-words text-xs text-gray-600">
                                                        {{ $pago->observaciones }}
                                                    </p>
                                                </div>

                                            @endif

                                        </div>

                                        {{-- Pie del modal de detalles --}}
                                        <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 px-5 py-3">

                                            <button type="button"
                                                    @click="modalAbierto = false"
                                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">
                                                Cerrar
                                            </button>

                                            {{-- Confirmar únicamente pagos pendientes --}}
                                            @if ($pago->estado === 'pendiente')
                                                <button type="button"
                                                        @click="confirmarAbierto = true"
                                                        class="rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#26485c]">
                                                    Confirmar pago
                                                </button>
                                            @endif

                                            <a href="{{ route('pago.edit', $pago->id) }}"
                                               class="rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#26485c]">
                                                Editar pago
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </td>

                        </tr>

                        {{-- Modal de confirmación --}}
                        <tr x-cloak
                            x-show="confirmarAbierto"
                            @keydown.escape.window="confirmarAbierto = false">

                            <td colspan="7" class="!p-0">

                                <div class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/50 p-4"
                                     @click.self="confirmarAbierto = false">

                                    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
                                         role="dialog"
                                         aria-modal="true"
                                         aria-labelledby="titulo-confirmacion-{{ $pago->id }}">

                                        {{-- Encabezado --}}
                                        <div class="border-b border-gray-100 px-5 py-4">

                                            <h2 id="titulo-confirmacion-{{ $pago->id }}"
                                                class="text-base font-bold text-gray-800">
                                                Confirmar pago
                                            </h2>

                                            <p class="mt-2 text-xs text-gray-500">
                                                ¿Confirmas que el dinero de este pago fue recibido?
                                            </p>

                                        </div>

                                        {{-- Información de confirmación --}}
                                        <div class="space-y-3 px-5 py-4">

                                            <div class="rounded-xl border border-[#dbe7ed] bg-[#f0f5f7] p-3">

                                                <p class="text-xs font-medium text-[#315A72]">
                                                    Valor del pago
                                                </p>

                                                <p class="mt-1 text-xl font-bold text-[#315A72]">
                                                    ${{ number_format($pago->valor, 2, ',', '.') }}
                                                </p>

                                            </div>

                                            <div class="rounded-lg bg-gray-50 p-3">

                                                <div class="flex justify-between gap-3 text-xs">
                                                    <span class="text-gray-500">
                                                        Saldo actual
                                                    </span>

                                                    <span class="font-semibold text-gray-800">
                                                        ${{ number_format($saldoPendiente, 2, ',', '.') }}
                                                    </span>
                                                </div>

                                            </div>

                                            <p class="text-xs text-gray-600">
                                                Al confirmar, este importe se contará como recibido
                                                y se actualizará el saldo pendiente de la orden.
                                            </p>

                                        </div>

                                        {{-- Acciones --}}
                                        <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 px-5 py-3">

                                            <button type="button"
                                                    @click="confirmarAbierto = false"
                                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">
                                                Cancelar
                                            </button>

                                            <form action="{{ route('pago.confirmar', $pago->id) }}"
                                                  method="POST">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="rounded-lg bg-[#315A72] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#26485c]">
                                                    Sí, confirmar
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                @empty

                    <tbody>
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center">
                                <p class="text-xs font-semibold text-slate-700">
                                    No hay pagos registrados.
                                </p>

                                <p class="mt-1 text-[11px] text-slate-500">
                                    Utiliza el botón Registrar pago para agregar el primer pago.
                                </p>
                            </td>
                        </tr>
                    </tbody>

                @endforelse

            </table>

        </div>

    </div>

</div>

@endsection
