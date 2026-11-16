@extends('layouts.app')

@section('title', 'Inventario')
@section('page-title', 'Gestión de inventarios')

@section('content')

<div class="mx-auto max-w-7xl space-y-4">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Gestión de inventarios
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Administración y seguimiento de las motocicletas recibidas en RAPTOR.
            </p>
        </div>

        <a
            href="{{ route('inventario.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#315A72] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#294c61]"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>

            Nuevo inventario
        </a>

    </div>

    {{-- Alertas gestionadas por el layout --}}
    <div id="alerts-container"></div>

    {{-- Tabla de inventarios --}}
    <div class="overflow-hidden rounded-xl border border-[#dce8ed] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-[#f0f5f7] text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Inventario</th>
                        <th class="px-5 py-4 font-semibold">Cliente</th>
                        <th class="px-5 py-4 font-semibold">Fecha de recepción</th>
                        <th class="px-5 py-4 font-semibold">Estado</th>
                        <th class="px-5 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                @forelse ($inventarios as $inventario)

                    {{-- Cada inventario controla su propio modal --}}
                    <tbody
                        x-data="{ modalAbierto: false }"
                        class="divide-y divide-[#e5edf1]"
                    >

                        {{-- Fila del inventario --}}
                        <tr class="transition hover:bg-[#eaf2f6]">

                            {{-- Recepción --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#f0f5f7] text-[#315A72]">
                                        <span class="block h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'list-check'])
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            RECEPCIÓN # {{ str_pad((string) $inventario->recepcion, 3, '0', STR_PAD_LEFT) }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $inventario->moto?->marca?->nombre_marca ?? 'Sin marca' }}
                                            {{ $inventario->moto?->modelo ?? '' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            PLACA: {{ $inventario->moto?->placa ?? '—' }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- Cliente --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-700">
                                    {{ $inventario->moto?->cliente?->nombre ?? '' }}
                                    {{ $inventario->moto?->cliente?->apellido ?? '' }}

                                    @if (!$inventario->moto?->cliente)
                                        —
                                    @endif
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $inventario->moto?->cliente?->tipo_documento ?? '' }}
                                    {{ $inventario->moto?->cliente?->numero_documento ?? '' }}
                                </p>
                            </td>

                            {{-- Fecha de recepción --}}
                            <td class="px-5 py-4 text-slate-600">
                                {{ $inventario->fecha_registro ?? '—' }}
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4">

                                @if ($inventario->estado_inventario === 'pendiente')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                        Pendiente
                                    </span>
                                @elseif ($inventario->estado_inventario === 'en_proceso')
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        En proceso
                                    </span>
                                @elseif ($inventario->estado_inventario === 'finalizado')
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Finalizado
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        Sin registrar
                                    </span>
                                @endif

                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Ver detalles --}}
                                    <button
                                        type="button"
                                        @click="modalAbierto = true"
                                        title="Ver detalles"
                                        aria-label="Ver detalles del inventario"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-[#315A72] transition hover:bg-[#eaf2f6] focus:outline-none focus:ring-2 focus:ring-[#315A72]"
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
                                                d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"
                                            />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('inventario.edit', $inventario->id) }}"
                                        title="Editar inventario"
                                        aria-label="Editar inventario"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
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

                                    {{-- Eliminar --}}
                                    <form
                                        action="{{ route('inventario.destroy', $inventario->id) }}"
                                        method="POST"
                                        class="flex items-center justify-center"
                                        onsubmit="return confirm('¿Está seguro de eliminar este inventario?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Eliminar inventario"
                                            aria-label="Eliminar inventario"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.8"
                                                stroke="currentColor"
                                                class="block h-5 w-5 shrink-0"
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

                        {{-- Modal del inventario --}}
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
                                        role="dialog"
                                        aria-modal="true"
                                        aria-label="Detalles del inventario"
                                        @click.stop
                                    >

                                        {{-- Encabezado del modal --}}
                                        <div class="flex items-center justify-between border-b border-[#dce8ed] bg-[#f0f5f7] px-6 py-5">

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-white text-[#315A72]">
                                                    <span class="block h-4 w-4">
                                                        @include('layouts.partials.icons', ['icon' => 'list-check'])
                                                    </span>
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="break-words text-lg font-bold text-slate-800">
                                                        Detalles del inventario
                                                    </h2>

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        RECEPCIÓN # {{ str_pad((string) $inventario->recepcion, 3, '0', STR_PAD_LEFT) }}
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

                                        {{-- Información del inventario --}}
                                        <div class="max-h-[65vh] overflow-y-auto p-6">

                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Información de la motocicleta
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                {{-- Marca --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Marca
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $inventario->moto?->marca?->nombre_marca ?? 'Sin marca' }}
                                                    </p>
                                                </div>

                                                {{-- Modelo --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Modelo
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $inventario->moto?->modelo ?? '—' }}
                                                    </p>
                                                </div>

                                                {{-- Placa --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Placa
                                                    </p>

                                                    <p class="mt-2 font-bold tracking-wider text-[#315A72]">
                                                        {{ $inventario->moto?->placa ?? '—' }}
                                                    </p>
                                                </div>

                                                {{-- ID --}}
                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Identificador del inventario
                                                    </p>

                                                    <p class="mt-2 font-semibold text-slate-800">
                                                        #{{ $inventario->id }}
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="my-6 border-t border-slate-100"></div>

                                            {{-- Información del cliente --}}
                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Información del cliente
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Nombre completo
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $inventario->moto?->cliente?->nombre ?? '' }}
                                                        {{ $inventario->moto?->cliente?->apellido ?? '' }}

                                                        @if (!$inventario->moto?->cliente)
                                                            —
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Documento
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $inventario->moto?->cliente?->tipo_documento ?? '' }}
                                                        {{ $inventario->moto?->cliente?->numero_documento ?? '—' }}
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="my-6 border-t border-slate-100"></div>

                                            {{-- Información de la recepción --}}
                                            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                Información de la recepción
                                            </h3>

                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Fecha de recepción
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        {{ $inventario->fecha_registro ?? '—' }}
                                                    </p>
                                                </div>

                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="text-xs font-medium text-slate-400">
                                                        Mecánico que recibe
                                                    </p>

                                                    <p class="mt-2 break-words font-semibold text-slate-800">
                                                        @if ($inventario->mecanico)
                                                            {{ $inventario->mecanico->nombre }}
                                                            {{ $inventario->mecanico->apellido }}
                                                        @else
                                                            —
                                                        @endif
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="my-6 border-t border-slate-100"></div>

                                            {{-- Condición general --}}
                                            <div class="mb-5">

                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                    Condición de la motocicleta
                                                </h3>

                                                @if ($inventario->condicion_general === 'bueno')
                                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                        Bueno
                                                    </span>
                                                @elseif ($inventario->condicion_general === 'regular')
                                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                        Regular
                                                    </span>
                                                @elseif ($inventario->condicion_general === 'malo')
                                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                        Malo
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                        Sin registrar
                                                    </span>
                                                @endif

                                            </div>

                                            {{-- Estado del inventario --}}
                                            <div class="mb-5">

                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                    Estado del inventario
                                                </h3>

                                                @if ($inventario->estado_inventario === 'pendiente')
                                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                        Pendiente
                                                    </span>
                                                @elseif ($inventario->estado_inventario === 'en_proceso')
                                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                        En proceso
                                                    </span>
                                                @elseif ($inventario->estado_inventario === 'finalizado')
                                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                        Finalizado
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                        Sin registrar
                                                    </span>
                                                @endif

                                            </div>

                                            <div class="my-6 border-t border-slate-100"></div>

                                            {{-- Descripción --}}
                                            <div>

                                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">
                                                    Descripción
                                                </h3>

                                                <div class="rounded-xl border border-[#dce8ed] p-4">
                                                    <p class="whitespace-pre-line break-words text-sm leading-relaxed text-slate-700">
                                                        {{ $inventario->descripcion ?: 'Sin descripción' }}
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Pie del modal --}}
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#dce8ed] bg-slate-50 px-6 py-4">

                                            {{-- Eliminar --}}
                                            <form
                                                action="{{ route('inventario.destroy', $inventario->id) }}"
                                                method="POST"
                                                class="flex items-center justify-center"
                                                onsubmit="return confirm('¿Está seguro de eliminar este inventario?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Eliminar inventario"
                                                    aria-label="Eliminar inventario"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="block h-5 w-5 shrink-0"
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
                                                    href="{{ route('inventario.edit', $inventario->id) }}"
                                                    title="Editar inventario"
                                                    aria-label="Editar inventario"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#315A72] text-white transition hover:bg-[#294c61]"
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
                                                    title="Cerrar"
                                                    aria-label="Cerrar modal"
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

                    <tbody>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                No hay inventarios registrados.
                            </td>
                        </tr>
                    </tbody>
                @endforelse

            </table>

        </div>
    </div>

</div>

@endsection