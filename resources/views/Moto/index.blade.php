@extends('layouts.app')

@section('title', 'Motos')
@section('page-title', 'Gestión de motocicletas')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de motocicletas
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Administración de las motos registradas en RAPTOR.
            </p>
        </div>

        <a
            href="{{ route('moto.create') }}"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#315A72] px-2.5 py-1.5 text-sm font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Nueva moto
        </a>

    </div>

    {{-- Mensajes manejado desde el layout--}}
    <div id="alerts-container" class="space-y-2">

    </div>

    {{-- Tabla de motocicletas --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Motocicleta</th>
                        <th class="px-5 py-4 font-semibold">Placa</th>
                        <th class="px-5 py-4 font-semibold">Cliente</th>
                        <th class="px-5 py-4 font-semibold">Año</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($motos as $moto)

                    {{-- Cada moto controla su propio modal --}}
                    <tbody x-data="{ modalAbierto: false }"
                           class="divide-y divide-[#e5edf1]">

                        {{-- Fila de la moto --}}
                        <tr class="transition hover:bg-[#eaf2f6]">

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-xl bg-[#f0f5f7] text-[#315A72]">
                                        @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $moto->marca->nombre_marca }}
                                            {{ $moto->modelo }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            MOTO #{{ $moto->id }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-md border border-[#dce8ed] bg-[#f0f5f7] px-2.5 py-1 text-xs font-bold tracking-wider text-[#315A72]">
                                    {{ $moto->placa }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $moto->cliente->nombre }}
                                {{ $moto->cliente->apellido }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $moto->anio }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles de la motocicleta"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                            text-[#315A72] hover:bg-[#eaf2f6]
                                            focus:outline-none focus:ring-2 focus:ring-[#315A72]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor"
                                            class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('moto.edit', $moto->id) }}"
                                        title="Editar"
                                        aria-label="Editar motocicleta"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                            text-blue-600 hover:bg-blue-50
                                            focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor"
                                            class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 2.651 2.651M7.5 16.5l4.125-.825L19.5 7.8a1.875 1.875 0 0 0-2.651-2.651l-7.875 7.875L8.15 17.15" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 13.5v5.25A1.5 1.5 0 0 1 18 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5V6A1.5 1.5 0 0 1 5.25 4.5H12" />
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('moto.destroy', $moto->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar esta moto?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar"
                                            aria-label="Eliminar motocicleta"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                                text-red-600 hover:bg-red-50
                                                focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.8" stroke="currentColor"
                                                class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.75 6.75h16.5M9.75 6.75V4.5h4.5v2.25m-8.25 0 .75 12.75h10.5l.75-12.75M10 10.5v5.25m4-5.25v5.25" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                        {{-- Modal de la moto --}}
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

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                    @include('layouts.partials.icons', ['icon' => 'motorcycle'])
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        {{ $moto->marca->nombre_marca }}
                                                        {{ $moto->modelo }}
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        Información de la motocicleta #{{ $moto->id }}
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Cerrar --}}
                                            <button
                                                type="button"
                                                @click="modalAbierto = false"
                                                aria-label="Cerrar modal"
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
                                                Datos de la motocicleta y su propietario
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Marca --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Marca
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $moto->marca->nombre_marca }}
                                                    </p>
                                                </div>

                                                {{-- Modelo --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Modelo
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $moto->modelo }}
                                                    </p>
                                                </div>

                                                {{-- Identificador --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Identificador de la moto
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        #{{ $moto->id }}
                                                    </p>
                                                </div>

                                                {{-- Placa --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Placa
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $moto->placa }}
                                                    </p>
                                                </div>

                                                {{-- Año --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Año
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        {{ $moto->anio }}
                                                    </p>
                                                </div>

                                                {{-- Cliente --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Propietario registrado
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $moto->cliente->nombre }}
                                                        {{ $moto->cliente->apellido }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('moto.destroy', $moto->id) }}"
                                                method="POST"
                                                class="flex items-center justify-center"
                                                onsubmit="return confirm('¿Está seguro de eliminar esta moto?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                
                                                    type="submit"
                                                    title="Eliminar moto"
                                                    aria-label="Eliminar moto"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg
                                                        border border-red-200 bg-white text-red-600
                                                        transition hover:bg-red-50"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-5 w-5 shrink-0">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3" />
                                                    </svg>
</>
                                                </button>
                                            </form>

                                            <div class="flex flex-wrap items-center gap-2">

                                                {{-- Editar --}}
                                                <a
                                                    href="{{ route('moto.edit', $moto->id) }}"
                                                    class="rounded-lg bg-[#315A72] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#294c61]"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-5 w-5"
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