@extends('layouts.app')

@section('title', 'Diagnósticos')
@section('page-title', 'Gestión de diagnósticos')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de diagnósticos
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra los diagnósticos realizados a las motocicletas.
            </p>
        </div>

        <a
            href="{{ route('diagnostico.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-md bg-[#315A72] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>

            Nuevo diagnóstico
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de diagnósticos --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Diagnóstico</th>
                        <th class="px-5 py-4 font-semibold">Motocicleta</th>
                        <th class="px-5 py-4 font-semibold">Placa</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($diagnosticos as $diagnostico)

                    @php
                        $folioRecepcion = $diagnostico->inventario?->recepcion !== null
                            ? 'RECEPCIÓN # ' . str_pad(
                                (string) $diagnostico->inventario->recepcion,
                                3,
                                '0',
                                STR_PAD_LEFT
                            )
                            : 'RECEPCIÓN SIN FOLIO';
                    @endphp

                    {{-- Cada diagnóstico controla su propio modal --}}
                    <tbody
                        x-data="{ modalAbierto: false }"
                        class="divide-y divide-[#e5edf1]"
                    >

                        {{-- Fila del diagnóstico --}}
                        <tr class="transition hover:bg-[#e8edf1]">

                            {{-- Folio de recepción --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">

                                    <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        <span class="block h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'square-mouse-pointer'])
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $folioRecepcion }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Diagnóstico registrado
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Motocicleta --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700">
                                    {{ $diagnostico->inventario?->moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                    {{ $diagnostico->inventario?->moto?->modelo ?? '' }}
                                </p>
                            </td>

                            {{-- Placa --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $diagnostico->inventario?->moto?->placa ?? 'No registrada' }}
                            </td>
                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles del diagnóstico"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[#315A72] transition hover:bg-[#e8edf1] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-[18px] w-[18px]"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"
                                            />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('diagnostico.edit', $diagnostico->id) }}"
                                        title="Editar"
                                        aria-label="Editar diagnóstico"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition hover:bg-[#e8edf1] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="block h-[18px] w-[18px]"
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
                                        action="{{ route('diagnostico.destroy', $diagnostico->id) }}"
                                        method="POST"
                                        class="flex items-center justify-center"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este diagnóstico?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar"
                                            aria-label="Eliminar diagnóstico"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 transition hover:bg-[#e8edf1] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="block h-[18px] w-[18px] shrink-0"
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

                        {{-- Modal del diagnóstico --}}
                        <tr x-show="modalAbierto" x-cloak>
                            <td colspan="5" class="!p-0">

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
                                        class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                                        @click.stop
                                    >

                                        {{-- Encabezado --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                            <div class="flex min-w-0 items-center gap-2">

                                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                    @include('layouts.partials.icons', ['icon' => 'square-mouse-pointer'])
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        {{ $folioRecepcion }}
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Información del diagnóstico
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

                                        {{-- Información completa --}}
                                        <div class="max-h-[65vh] overflow-y-auto p-6">

                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Información del diagnóstico
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Folio de recepción --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Folio de recepción
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
                                                        {{ $diagnostico->inventario?->moto?->marca?->nombre_marca ?? 'Marca no disponible' }}
                                                        {{ $diagnostico->inventario?->moto?->modelo ?? '' }}
                                                    </p>

                                                    <p class="mt-1 text-sm text-slate-500">
                                                        Placa:
                                                        {{ $diagnostico->inventario?->moto?->placa ?? 'No registrada' }}
                                                    </p>
                                                </div>

                                                {{-- Propietario --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Propietario
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $diagnostico->inventario?->moto?->cliente?->nombre ?? '' }}
                                                        {{ $diagnostico->inventario?->moto?->cliente?->apellido ?? '' }}
                                                    </p>
                                                </div>

                                                {{-- Documento --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Documento del propietario
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $diagnostico->inventario?->moto?->cliente?->numero_documento ?? 'No disponible' }}
                                                    </p>
                                                </div>

                                                {{-- Mecánico --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Mecánico responsable
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $diagnostico->mecanico
                                                            ? $diagnostico->mecanico->nombre . ' ' . $diagnostico->mecanico->apellido
                                                            : 'No asignado' }}
                                                    </p>
                                                </div>
                                                {{-- Fecha --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Fecha del diagnóstico
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $diagnostico->fecha_diagnostico ?: 'No registrada' }}
                                                    </p>
                                                </div>

                                                {{-- Descripción --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4 sm:col-span-2">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Descripción
                                                    </p>

                                                    <p class="mt-2 whitespace-pre-line break-words text-slate-700">
                                                        {{ $diagnostico->descripcion ?: 'Sin descripción' }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('diagnostico.destroy', $diagnostico->id) }}"
                                                method="POST"
                                                class="flex items-center justify-center"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este diagnóstico?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Eliminar diagnóstico"
                                                    aria-label="Eliminar diagnóstico"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 transition hover:bg-[#e8edf1] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-[18px] w-[18px] shrink-0"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3"
                                                        />
                                                    </svg>
                                                </button>
                                            </form>

                                            <div class="flex flex-wrap items-center gap-2">

                                                {{-- Editar --}}
                                                <a
                                                    href="{{ route('diagnostico.edit', $diagnostico->id) }}"
                                                    title="Editar diagnóstico"
                                                    aria-label="Editar diagnóstico"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61]"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-[18px] w-[18px]"
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

                                                {{-- Cerrar --}}
                                                <button
                                                    type="button"
                                                    @click="modalAbierto = false"
                                                    title="Cerrar"
                                                    aria-label="Cerrar modal"
                                                    class="rounded-lg border border-[#dce8ed] bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-[#e8edf1]"
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

                    <tbody>
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                                No hay diagnósticos registrados.
                            </td>
                        </tr>
                    </tbody>

                @endforelse

            </table>

        </div>
    </div>

    {{-- Continuar al módulo Preorden --}}
    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">

        <p class="text-xs text-amber-900">
            Ahora continúa con el módulo
            <span class="font-semibold">Preorden</span>
            para preparar el presupuesto de reparación de la motocicleta.
        </p>

        <div class="mt-2">
            <a
                href="{{ route('preorden.index') }}"
                class="inline-flex items-center gap-1.5 rounded-md bg-[#315A72] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#294c61]"
            >
                Ir a Preorden

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

    </div>

</div>

@endsection
