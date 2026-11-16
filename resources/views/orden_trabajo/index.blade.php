@extends('layouts.app')

@section('title', 'Órdenes de trabajo')
@section('page-title', 'Gestión de órdenes de trabajo')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de órdenes de trabajo
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y seguimiento de las reparaciones.
            </p>
        </div>

        <a
            href="{{ route('orden_trabajo.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#315A72] px-3 py-2 text-sm font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Nueva orden de trabajo
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <div class="flex items-start gap-2">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v4m0 4h.01M10.3 3.86 2.5 17.5A2 2 0 0 0 4.23 20.5h15.54a2 2 0 0 0 1.73-3L13.73 3.86a2 2 0 0 0-3.46 0Z"/>
                </svg>

                <div>
                    <p class="font-semibold">
                        No se pudo realizar la operación.
                    </p>

                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabla de órdenes de trabajo --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Orden de trabajo</th>
                        <th class="px-5 py-4 font-semibold">Motocicleta</th>
                        <th class="px-5 py-4 font-semibold">Cliente</th>
                        <th class="px-5 py-4 font-semibold">Estado</th>
                        <th class="px-5 py-4 font-semibold">Saldo pendiente</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($ordenesTrabajo as $ordenTrabajo)

                    @php
                        // La orden utiliza exclusivamente su propia preorden.
                        $preorden = $ordenTrabajo->preorden;


                        // Configuración visual de los estados.
                        $estadoConfiguracion = [
                            'abierta' => [
                                'texto' => 'Abierta',
                                'clases' => 'bg-amber-100 text-amber-800',
                            ],
                            'en_proceso' => [
                                'texto' => 'En proceso',
                                'clases' => 'bg-blue-100 text-blue-800',
                            ],
                            'finalizada' => [
                                'texto' => 'Finalizada',
                                'clases' => 'bg-emerald-100 text-emerald-800',
                            ],
                        ];

                        $estadoActual = $estadoConfiguracion[$ordenTrabajo->estado]
                            ?? [
                                'texto' => ucfirst(str_replace('_', ' ', $ordenTrabajo->estado ?? '')),
                                'clases' => 'bg-slate-100 text-slate-700',
                            ];

                        // Obtener la motocicleta desde la preorden correspondiente.
                        $moto = $preorden?->diagnostico?->inventario?->moto
                            ?? $ordenTrabajo->inventario?->moto;

                        $cliente = $moto?->cliente;
                        $mecanico = $ordenTrabajo->mecanico;

                        // Folio único de la recepción.
                        $recepcion = $preorden?->diagnostico?->inventario?->recepcion
                            ?? $ordenTrabajo->inventario?->recepcion;

                        $folioRecepcion = $recepcion
                            ? 'RECEPCIÓN # ' . str_pad((string) $recepcion, 3, '0', STR_PAD_LEFT)
                            : 'RECEPCIÓN';
                    @endphp

                    {{-- Cada orden controla su propio modal --}}
                    <tbody
                        x-data="{ modalAbierto: false }"
                        class="divide-y divide-[#e5edf1]"
                    >

                        {{-- Fila de la orden --}}
                        <tr class="transition hover:bg-[#eaf1f4]">

                            {{-- Identificador --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <span class="block h-5 w-5">
                                            @include('layouts.partials.icons', ['icon' => 'file-cog'])
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $folioRecepcion }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Orden de trabajo
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Motocicleta --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700">
                                    {{ $moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                    {{ $moto?->modelo ?? '' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $moto?->placa ?? 'Sin placa' }}
                                </p>
                            </td>

                            {{-- Cliente --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $cliente?->nombre ?? 'Sin nombre' }}
                                {{ $cliente?->apellido ?? '' }}
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $estadoActual['clases'] }}">
                                    {{ $estadoActual['texto'] }}
                                </span>
                            </td>

                            {{-- Saldo --}}
                            <td class="px-5 py-4">
                                <span class="font-bold {{ $ordenTrabajo->saldo_pendiente > 0 ? 'text-orange-700' : 'text-emerald-600' }}">
                                    ${{ number_format($ordenTrabajo->saldo_pendiente, 2, ',', '.') }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Registrar pago --}}
                                    @if ($ordenTrabajo->saldo_pendiente > 0)

                                        <a
                                            href="{{ route('pago.create', ['id_ordentrabajo' => $ordenTrabajo->id]) }}"
                                            title="Registrar pago"
                                            aria-label="Registrar pago de la orden {{ $ordenTrabajo->id }}"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-white px-2 py-1.5 text-xs font-semibold text-orange-800 transition hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                class="h-4 w-4 flex-shrink-0">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m2 16 6 6" />
                                                <circle cx="16" cy="9" r="2.9" stroke-width="2" />
                                                <circle cx="6" cy="5" r="3" stroke-width="2" />
                                            </svg>

                                            <span>Registrar pago</span>
                                        </a>

                                    @else

                                        <button
                                            type="button"
                                            title="No hay saldo pendiente"
                                            onclick="alert('Esta orden de trabajo no tiene saldo pendiente. No es necesario registrar otro pago.')"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-100 px-2 py-1.5 text-xs font-semibold text-slate-400"
                                        >
                                            <span>Sin saldo</span>
                                        </button>

                                    @endif

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles de la orden de trabajo"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="h-5 w-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"
                                            />
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('orden_trabajo.edit', $ordenTrabajo->id) }}"
                                        title="Editar"
                                        aria-label="Editar orden de trabajo"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="h-5 w-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"
                                            />
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('orden_trabajo.destroy', $ordenTrabajo->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar esta orden de trabajo?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar"
                                            aria-label="Eliminar orden de trabajo"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"
                                                />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                        {{-- Modal de la orden de trabajo --}}
                        <tr x-show="modalAbierto" x-cloak>
                            <td colspan="6" class="!p-0">

                                <div
                                    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/60 p-4"
                                    @click.self="modalAbierto = false"
                                    @keydown.escape.window="modalAbierto = false"
                                >

                                    <div
                                        x-show="modalAbierto"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="my-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                        @click.stop
                                    >

                                        {{-- Encabezado del modal --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                    <span class="block h-5 w-5">
                                                        @include('layouts.partials.icons', ['icon' => 'file-cog'])
                                                    </span>
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        {{ $folioRecepcion }}
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Orden de trabajo
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Cerrar --}}
                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                title="Cerrar"
                                                aria-label="Cerrar modal"
                                                class="ml-3 flex-shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-slate-800"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                        </div>

                                        {{-- Información completa --}}
                                        <div class="max-h-[65vh] overflow-y-auto p-6">

                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Datos de la reparación
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Recepción --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Número de recepción
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $folioRecepcion }}
                                                    </p>
                                                </div>

                                                {{-- Motocicleta --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Motocicleta
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                                        {{ $moto?->modelo ?? '' }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-slate-500">
                                                        Placa: {{ $moto?->placa ?? 'Sin placa' }}
                                                    </p>
                                                </div>

                                                {{-- Cliente --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Cliente
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $cliente?->nombre ?? 'Sin nombre' }}
                                                        {{ $cliente?->apellido ?? '' }}
                                                    </p>
                                                </div>

                                                {{-- Mecánico --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Mecánico responsable
                                                    </p>
                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $mecanico?->nombre ?? 'Sin nombre' }}
                                                        {{ $mecanico?->apellido ?? '' }}
                                                    </p>
                                                </div>

                                                {{-- Estado --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Estado de la orden
                                                    </p>
                                                    <div class="mt-2">
                                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $estadoActual['clases'] }}">
                                                            {{ $estadoActual['texto'] }}
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Fecha de inicio --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Fecha de inicio
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $ordenTrabajo->fecha_inicio ? \Carbon\Carbon::parse($ordenTrabajo->fecha_inicio)->format('d/m/Y') : 'No registrada' }}
                                                    </p>
                                                </div>

                                                {{-- Fecha de finalización --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Fecha de finalización
                                                    </p>
                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $ordenTrabajo->fecha_fin ? \Carbon\Carbon::parse($ordenTrabajo->fecha_fin)->format('d/m/Y') : 'Pendiente' }}
                                                    </p>
                                                </div>

                                                {{-- Observaciones --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Observaciones
                                                    </p>
                                                    <p class="mt-2 whitespace-pre-line break-words text-slate-700">
                                                        {{ $ordenTrabajo->observaciones ?: 'Sin observaciones' }}
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Resumen financiero --}}
                                            <h3 class="mb-4 mt-6 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Resumen financiero
                                            </h3>

                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                                                <div class="rounded-xl border border-[#dce8ed] bg-[#f8fafb] p-4">
                                                    <p class="text-sm text-slate-500">
                                                        Total de la orden
                                                    </p>
                                                    <p class="mt-2 text-xl font-bold text-slate-800">
                                                        ${{ number_format($ordenTrabajo->total_ot, 2, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                                                    <p class="text-sm text-emerald-700">
                                                        Total pagado
                                                    </p>
                                                    <p class="mt-2 text-xl font-bold text-emerald-700">
                                                        ${{ number_format($ordenTrabajo->total_pagado, 2, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-xl border {{ $ordenTrabajo->saldo_pendiente > 0 ? 'border-orange-200 bg-orange-50' : 'border-emerald-100 bg-emerald-50' }} p-4">
                                                    <p class="text-sm {{ $ordenTrabajo->saldo_pendiente > 0 ? 'text-orange-800' : 'text-emerald-700' }}">
                                                        Saldo pendiente
                                                    </p>

                                                    <p class="mt-2 text-xl font-bold {{ $ordenTrabajo->saldo_pendiente > 0 ? 'text-orange-800' : 'text-emerald-700' }}">
                                                        ${{ number_format($ordenTrabajo->saldo_pendiente, 2, ',', '.') }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Registrar pago --}}
                                            @if ($ordenTrabajo->saldo_pendiente > 0)

                                                <a
                                                    href="{{ route('pago.create', ['id_ordentrabajo' => $ordenTrabajo->id]) }}"
                                                    title="Registrar pago"
                                                    aria-label="Registrar pago de la orden"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-white px-2 py-1.5 text-xs font-semibold text-orange-800 transition hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                                >
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        class="h-4 w-4 flex-shrink-0">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="m2 16 6 6" />
                                                        <circle cx="16" cy="9" r="2.9" stroke-width="2" />
                                                        <circle cx="6" cy="5" r="3" stroke-width="2" />
                                                    </svg>

                                                    <span>Registrar pago</span>
                                                </a>

                                            @else

                                                <button
                                                    type="button"
                                                    onclick="alert('Esta orden de trabajo no tiene saldo pendiente. No es necesario registrar otro pago.')"
                                                    title="No hay saldo pendiente"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-100 px-2 py-1.5 text-xs font-semibold text-slate-400"
                                                >
                                                    Sin saldo pendiente
                                                </button>

                                            @endif

                                            <div class="flex flex-wrap items-center gap-2">

                                                {{-- Editar --}}
                                                <a
                                                    href="{{ route('orden_trabajo.edit', $ordenTrabajo->id) }}"
                                                    title="Editar orden de trabajo"
                                                    aria-label="Editar orden de trabajo"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61]"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                              d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                              d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12"/>
                                                    </svg>
                                                </a>

                                                {{-- Cerrar --}}
                                                <button
                                                    type="button"
                                                    @click="modalAbierto = false"
                                                    class="rounded-lg border border-[#dce8ed] bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                                                >
                                                    Cerrar
                                                </button>

                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </td>
                        </tr>

                    </tbody>

                @empty

                    {{-- Estado vacío intencionalmente sin contenido --}}

                @endforelse

            </table>

        </div>
    </div>

</div>

@endsection
