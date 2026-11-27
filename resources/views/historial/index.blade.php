@extends('layouts.app')

@section('title', 'Historial de servicios')
@section('page-title', 'Historial de servicios')

@section('content')

<div class="mx-auto max-w-7xl space-y-5">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            Historial de servicios
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Consulta los servicios finalizados y entregados, organizados por cliente y motocicleta.
        </p>
    </div>

    {{-- Resumen --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        {{-- Clientes --}}
        <div class="flex items-center gap-3 rounded-xl border border-[#dce8ed] bg-white p-4 shadow-sm">

            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" stroke-width="2" />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">Clientes con historial</p>
                <p class="mt-1 text-xl font-bold text-slate-800">{{ $cantidadClientes }}</p>
            </div>

        </div>

        {{-- Motocicletas --}}
        <div class="flex items-center gap-3 rounded-xl border border-[#dce8ed] bg-white p-4 shadow-sm">

            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                <svg viewBox="0 0 256 256" fill="currentColor" class="h-7 w-7 scale-110">
                    <path d="M216 120a41 41 0 0 0-6.6.55l-5.82-15.14A55.6 55.6 0 0 1 216 104a8 8 0 0 0 0-16h-19.12l-13.41-34.87A8 8 0 0 0 176 48h-32a8 8 0 0 0 0 16h26.51l9.23 24H152c-18.5 0-33.5 4.31-43.37 12.46a16 16 0 0 1-16.76 2.07c-10.58-4.81-73.29-30.12-73.8-30.26a8 8 0 0 0-5 15.19s55.5 21.94 66.53 32.94A55.67 55.67 0 0 1 95.43 152H79.2a40 40 0 1 0 0 16h52.12a31.91 31.91 0 0 0 30.74-23.1 56 56 0 0 1 26.59-33.72l5.82 15.13A40 40 0 1 0 216 120M40 168h22.62a24 24 0 1 1 0-16H40a8 8 0 0 0 0 16m176 16a24 24 0 0 1-15.58-42.23l8.11 21.1a8 8 0 1 0 14.94-5.74L215.35 136h.65a24 24 0 0 1 0 48" />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">Motocicletas</p>
                <p class="mt-1 text-xl font-bold text-slate-800">{{ $cantidadMotos }}</p>
            </div>

        </div>

        {{-- Servicios entregados --}}
        <div class="flex items-center gap-3 rounded-xl border border-[#dce8ed] bg-white p-4 shadow-sm">

            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-7 w-7">
                    <rect width="18" height="18" x="3" y="3" rx="2" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16 16 8M8 8h8v8" />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-slate-500">Servicios entregados</p>
                <p class="mt-1 text-xl font-bold text-slate-800">{{ $cantidadServicios }}</p>
            </div>

        </div>

    </div>

    {{-- Listado de clientes --}}
    <div class="space-y-4">

        @forelse ($clientesPreparados as $datosCliente)

            <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[850px] text-left text-sm">

                        {{-- Encabezados --}}
                        <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Recepción</th>
                                <th class="px-4 py-3 font-semibold">Fecha de entrega</th>
                                <th class="px-4 py-3 font-semibold">Total</th>
                                <th class="px-4 py-3 text-center font-semibold">Informe</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- Fila de datos del cliente y botón PDF --}}
                            <tr class="border-t border-[#dce8ed] bg-[#f8fafb] transition-colors hover:bg-[#eaf2f6]">

                                <td colspan="4" class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m12-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <h2 class="font-bold text-slate-800">
                                                {{ $datosCliente['cliente']->nombre }} {{ $datosCliente['cliente']->apellido }}
                                            </h2>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $datosCliente['cliente']->tipo_documento }}: {{ $datosCliente['cliente']->numero_documento }}
                                                <span class="mx-1">·</span>
                                                {{ $datosCliente['cliente']->telefono }}
                                            </p>
                                        </div>

                                        <div class="ml-auto hidden items-center gap-4 text-xs text-slate-500 sm:flex">
                                            <span>
                                                {{ $datosCliente['cantidadMotos'] }}
                                                {{ $datosCliente['cantidadMotos'] === 1 ? 'motocicleta' : 'motocicletas' }}
                                            </span>

                                            <span>
                                                {{ $datosCliente['cantidadServicios'] }}
                                                {{ $datosCliente['cantidadServicios'] === 1 ? 'servicio' : 'servicios' }}
                                            </span>
                                        </div>

                                        <div class="ml-auto sm:ml-2">
                                            <a
                                                href="{{ route('historial.cliente.pdf', ['idCliente' => $datosCliente['cliente']->id]) }}"
                                                target="_blank"
                                                class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/>
                                                </svg>

                                                PDF historial del cliente
                                            </a>
                                        </div>

                                    </div>

                                </td>

                            </tr>

                            {{-- Una fila independiente por cada servicio --}}
                            @forelse ($datosCliente['servicios'] as $item)

                                <tr class="border-t border-[#e5edf1] bg-white transition-colors hover:bg-[#eaf2f6]">

                                    {{-- Recepción y motocicleta --}}
                                    <td class="px-5 py-4">

                                        <p class="font-semibold text-slate-700">
                                            {{ $item['folio'] }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $item['moto']->marca?->nombre_marca ?? 'Marca no disponible' }}
                                            {{ $item['moto']->modelo }}
                                            <span class="mx-1">·</span>
                                            {{ $item['moto']->placa ?? 'Sin placa' }}
                                        </p>

                                    </td>

                                    {{-- Fecha de entrega --}}
                                    <td class="px-4 py-4">

                                        <p class="text-sm font-medium text-slate-600">
                                            {{ $item['fechaEntrega'] }}
                                        </p>

                                    </td>

                                    {{-- Total --}}
                                    <td class="px-4 py-4">

                                        <p class="font-bold text-slate-800">
                                            ${{ number_format($item['totalServicio'], 0, ',', '.') }}
                                        </p>

                                    </td>

                                    {{-- PDF individual --}}
                                    <td class="px-4 py-4 text-center">

                                        <a
                                            href="{{ route('historial.descargar', ['id' => $item['servicio']->getKey()]) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap rounded-lg border border-[#dce8ed] bg-white px-3 py-2 text-xs font-semibold text-[#315A72] transition hover:border-[#315A72] hover:bg-[#f0f5f7]"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/>
                                            </svg>

                                            PDF servicio
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr class="border-t border-[#e5edf1] bg-white">
                                    <td colspan="4" class="px-5 py-5 text-center text-sm text-slate-400">
                                        Este cliente todavía no tiene servicios entregados.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @empty

            {{-- Historial vacío --}}
            <div class="rounded-xl border border-[#dce8ed] bg-white px-5 py-14 text-center shadow-sm">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#f0f5f7] text-[#315A72]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M9 5h6m-6 4h6m-6 4h6m-8 7h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                    </svg>
                </div>

                <h3 class="mt-4 font-semibold text-slate-700">
                    No hay servicios en el historial
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Aquí aparecerán los clientes y sus motocicletas cuando tengan servicios finalizados y entregados.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
